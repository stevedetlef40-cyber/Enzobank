<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Currency;
use App\Models\Loan;
use App\Models\LoanFunding;
use App\Models\LoanWallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $page_title = __('Loan Applications');
        $query = Loan::with(['user', 'product', 'wallet', 'fundings.admin', 'investmentPlan'])
            ->when($request->get('q'), function ($q) use ($request) {
                $term = $request->get('q');
                $q->where(function ($sub) use ($term) {
                    $sub->where('status', 'like', "%{$term}%")
                        ->orWhere('approval_status', 'like', "%{$term}%")
                        ->orWhereHas('user', function ($u) use ($term) {
                            $u->where('firstname', 'like', "%{$term}%")
                                ->orWhere('lastname', 'like', "%{$term}%")
                                ->orWhere('email', 'like', "%{$term}%");
                        })
                        ->orWhereHas('product', function ($p) use ($term) {
                            $p->where('name', 'like', "%{$term}%");
                        });
                });
            })
            ->when($request->get('approval_status'), function ($q) use ($request) {
                $q->where('approval_status', $request->get('approval_status'));
            })
            ->when($request->get('status'), function ($q) use ($request) {
                $q->where('status', $request->get('status'));
            })
            ->orderByDesc('created_at');

        $loans = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Loan::count(),
            'pending_review' => Loan::where('approval_status', Loan::APPROVAL_PENDING_REVIEW)->count(),
            'approved' => Loan::where('approval_status', Loan::APPROVAL_APPROVED)->count(),
            'funded' => Loan::where('approval_status', Loan::APPROVAL_FUNDED)->count(),
            'rejected' => Loan::where('approval_status', Loan::APPROVAL_REJECTED)->count(),
            'active' => Loan::where('status', Loan::STATUS_ACTIVE)->count(),
        ];

        return view('admin.sections.loans.index', compact('page_title', 'loans', 'stats'));
    }

    public function show($id)
    {
        $loan = Loan::with(['user', 'product', 'wallet', 'fundings.admin', 'investmentPlan', 'payments'])->findOrFail($id);
        $page_title = __('Loan Details').' - '.$loan->product?->name ?? 'Custom';

        return view('admin.sections.loans.show', compact('page_title', 'loan'));
    }

    public function approve(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->approval_status !== Loan::APPROVAL_PENDING_REVIEW) {
            return back()->with('error', __('Loan is not pending review.'));
        }

        $request->validate([
            'approved_amount' => ['required', 'numeric', 'min:0.01', 'max:'.$loan->principal],
            'investment_plan_id' => ['nullable', 'exists:investment_plans,id'],
            'origination_fee_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'service_fee_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'withdrawal_fee_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $loan->update([
            'approval_status' => Loan::APPROVAL_APPROVED,
            'approved_by_admin_id' => auth()->guard('admin')->id(),
            'approved_at' => now(),
            'funded_amount' => $request->approved_amount,
            'investment_plan_id' => $request->investment_plan_id,
            'origination_fee_percent' => $request->origination_fee_percent ?? 1.0,
            'service_fee_percent' => $request->service_fee_percent ?? 0.5,
            'withdrawal_fee_percent' => $request->withdrawal_fee_percent ?? 2.5,
            'admin_notes' => $request->admin_notes,
        ]);

        // Create loan wallet for the user
        $this->createLoanWallet($loan);

        return back()->with('success', __('Loan approved successfully. You can now fund the loan.'));
    }

    public function reject(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->approval_status !== Loan::APPROVAL_PENDING_REVIEW) {
            return back()->with('error', __('Loan is not pending review.'));
        }

        $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        $loan->update([
            'approval_status' => Loan::APPROVAL_REJECTED,
            'rejection_reason' => $request->rejection_reason,
            'status' => Loan::STATUS_CLOSED,
        ]);

        return back()->with('success', __('Loan application rejected.'));
    }

    public function fund(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->approval_status !== Loan::APPROVAL_APPROVED) {
            return back()->with('error', __('Loan must be approved before funding.'));
        }

        if ($loan->isFullyFunded()) {
            return back()->with('error', __('Loan is already fully funded.'));
        }

        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:'.($loan->principal - $loan->funded_amount)],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($loan, $request) {
            $amount = $request->amount;
            $originationFee = round($amount * (($loan->origination_fee_percent ?? 1.0) / 100), 4);
            $netAmount = $amount - $originationFee;

            // Create funding record
            LoanFunding::create([
                'loan_id' => $loan->id,
                'admin_id' => auth()->guard('admin')->id(),
                'amount' => $amount,
                'fee_deducted' => $originationFee,
                'net_amount' => $netAmount,
                'notes' => $request->notes,
                'status' => LoanFunding::STATUS_COMPLETED,
            ]);

            // Update loan
            $loan->increment('funded_amount', $netAmount);
            if ($loan->funded_amount >= $loan->principal) {
                $loan->update([
                    'approval_status' => Loan::APPROVAL_FUNDED,
                    'funded_at' => now(),
                    'funded_by_admin_id' => auth()->guard('admin')->id(),
                    'status' => Loan::STATUS_ACTIVE,
                    'balance_principal' => $loan->principal,
                ]);
            }

            // Credit loan wallet
            $wallet = $loan->wallet()->first();
            if ($wallet) {
                $wallet->increment('balance', $netAmount);
            }
        });

        return back()->with('success', __('Loan funded successfully.'));
    }

    public function disburse(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->approval_status !== Loan::APPROVAL_FUNDED && $loan->approval_status !== Loan::APPROVAL_APPROVED) {
            return back()->with('error', __('Loan must be approved and funded before disbursement.'));
        }

        $wallet = $loan->wallet()->first();
        if (! $wallet) {
            return back()->with('error', __('Loan wallet not found.'));
        }

        // Generate payment schedule
        app(\App\Services\LoanCalculator::class)->generateSchedule($loan->fresh());

        return back()->with('success', __('Loan disbursed and payment schedule generated.'));
    }

    private function createLoanWallet(Loan $loan): LoanWallet
    {
        $currency = Currency::where('code', $loan->currency)->first()
            ?? Currency::where('code', 'USD')->first()
            ?? Currency::first();

        return LoanWallet::create([
            'loan_id' => $loan->id,
            'user_id' => $loan->user_id,
            'currency_id' => $currency->id,
            'balance' => 0,
            'invested_amount' => 0,
            'earnings_balance' => 0,
            'withdrawn_earnings' => 0,
            'status' => LoanWallet::STATUS_ACTIVE,
        ]);
    }
}
