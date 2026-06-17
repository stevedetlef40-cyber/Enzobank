<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\LoanProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LoanCalculator;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $page_title = __('Loans');
        $query = Loan::with(['product'])
            ->where('user_id', Auth::id())
            ->when($request->get('q'), function ($q) use ($request) {
                $term = $request->get('q');
                $q->where(function ($sub) use ($term) {
                    $sub->where('status', 'like', "%{$term}%")
                        ->orWhereHas('product', function ($p) use ($term) {
                            $p->where('name', 'like', "%{$term}%");
                        });
                });
            })
            ->orderByDesc('created_at');

        $loans = $query->paginate(10);

        return view('user.sections.loans.index', compact('page_title', 'loans'));
    }

    public function create()
    {
        $page_title = __('Apply Loan');
        $products = LoanProduct::where('status', true)->orderBy('name')->get();
        return view('user.sections.loans.create', compact('page_title', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_product_id' => ['nullable', 'exists:loan_products,id'],
            'principal'       => ['required', 'numeric', 'min:0.01'],
            'interest_rate'   => ['required', 'numeric', 'min:0'],
            'term_months'     => ['required', 'integer', 'min:1', 'max:480'],
            'start_date'      => ['nullable', 'date'],
            'interest_method' => ['nullable', 'in:simple,compound,amortized'],
            'payment_frequency' => ['nullable', 'in:monthly,biweekly,weekly'],
            'rate_type'       => ['nullable', 'in:fixed,variable'],
            'rate_schedule'   => ['nullable', 'array'],
            'grace_days'      => ['nullable', 'integer', 'min:0', 'max:60'],
            'late_fee_type'   => ['nullable', 'in:percent,flat'],
            'late_fee_value'  => ['nullable', 'numeric', 'min:0'],
            'early_settlement_fee_percent' => ['nullable', 'numeric', 'min:0'],
        ]);

        $loan = Loan::create([
            'user_id'         => Auth::id(),
            'loan_product_id' => $request->loan_product_id,
            'principal'       => $request->principal,
            'interest_rate'   => $request->interest_rate,
            'term_months'     => $request->term_months,
            'start_date'      => $request->start_date,
            'balance_principal' => $request->principal,
            'status'          => 'pending',
            'interest_method' => $request->input('interest_method', 'amortized'),
            'payment_frequency' => $request->input('payment_frequency', 'monthly'),
            'rate_type'       => $request->input('rate_type', 'fixed'),
            'rate_schedule'   => $request->input('rate_schedule'),
            'grace_days'      => $request->input('grace_days', 0),
            'late_fee_type'   => $request->input('late_fee_type', 'percent'),
            'late_fee_value'  => $request->input('late_fee_value', 0),
            'early_settlement_fee_percent' => $request->input('early_settlement_fee_percent', 0),
        ]);

        app(LoanCalculator::class)->generateSchedule($loan);

        return redirect()->route('user.loans.index')->with('success', __('Loan application submitted.'));
    }

    public function edit($id)
    {
        $loan = Loan::where('user_id', Auth::id())->findOrFail($id);
        $page_title = __('Edit Loan');
        $products = LoanProduct::where('status', true)->orderBy('name')->get();
        return view('user.sections.loans.edit', compact('page_title', 'loan', 'products'));
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'interest_rate'   => ['required', 'numeric', 'min:0'],
            'term_months'     => ['required', 'integer', 'min:1', 'max:480'],
            'start_date'      => ['nullable', 'date'],
            'status'          => ['required', 'in:pending,active,closed,defaulted'],
            'interest_method' => ['nullable', 'in:simple,compound,amortized'],
            'payment_frequency' => ['nullable', 'in:monthly,biweekly,weekly'],
            'rate_type'       => ['nullable', 'in:fixed,variable'],
            'rate_schedule'   => ['nullable', 'array'],
            'grace_days'      => ['nullable', 'integer', 'min:0', 'max:60'],
            'late_fee_type'   => ['nullable', 'in:percent,flat'],
            'late_fee_value'  => ['nullable', 'numeric', 'min:0'],
            'early_settlement_fee_percent' => ['nullable', 'numeric', 'min:0'],
        ]);

        $loan->update([
            'interest_rate' => $request->interest_rate,
            'term_months'   => $request->term_months,
            'start_date'    => $request->start_date,
            'status'        => $request->status,
            'interest_method' => $request->input('interest_method', $loan->interest_method),
            'payment_frequency' => $request->input('payment_frequency', $loan->payment_frequency),
            'rate_type'       => $request->input('rate_type', $loan->rate_type),
            'rate_schedule'   => $request->input('rate_schedule', $loan->rate_schedule),
            'grace_days'      => $request->input('grace_days', $loan->grace_days),
            'late_fee_type'   => $request->input('late_fee_type', $loan->late_fee_type),
            'late_fee_value'  => $request->input('late_fee_value', $loan->late_fee_value),
            'early_settlement_fee_percent' => $request->input('early_settlement_fee_percent', $loan->early_settlement_fee_percent),
        ]);

        app(LoanCalculator::class)->generateSchedule($loan->fresh());

        return redirect()->route('user.loans.index')->with('success', __('Loan updated.'));
    }

    public function destroy($id)
    {
        $loan = Loan::where('user_id', Auth::id())->findOrFail($id);
        if (in_array($loan->status, ['active'])) {
            return redirect()->back()->with('error', __('Active loans cannot be deleted.'));
        }
        $loan->delete();
        return redirect()->route('user.loans.index')->with('success', __('Loan deleted.'));
    }

    public function schedule($id)
    {
        $loan = Loan::with('payments')->where('user_id', Auth::id())->findOrFail($id);
        $page_title = __('Repayment Schedule');
        return view('user.sections.loans.schedule', compact('page_title','loan'));
    }

    public function stats()
    {
        $userId = Auth::id();
        $loansForLate = Loan::where('user_id', $userId)->get();
        $calc = app(LoanCalculator::class);
        foreach ($loansForLate as $ln) {
            $calc->applyLateFees($ln, now());
        }
        $totalPrincipal = Loan::where('user_id', $userId)->sum('principal');
        $totalBalance = Loan::where('user_id', $userId)->sum('balance_principal');
        $activeCount = Loan::where('user_id', $userId)->where('status', 'active')->count();
        $pendingCount = Loan::where('user_id', $userId)->where('status', 'pending')->count();
        $closedCount = Loan::where('user_id', $userId)->where('status', 'closed')->count();

        $upcoming = LoanPayment::whereHas('loan', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'due')->orderBy('due_date')->limit(6)->get(['due_date', 'amount_due']);

        return response()->json([
            'total_principal' => $totalPrincipal,
            'total_balance'   => $totalBalance,
            'active'          => $activeCount,
            'pending'         => $pendingCount,
            'closed'          => $closedCount,
            'upcoming'        => $upcoming,
        ]);
    }
}
