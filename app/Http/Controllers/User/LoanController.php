<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPlan;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\LoanProduct;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $page_title = __('Loans');
        $query = Loan::with(['product', 'payments', 'wallet', 'investmentPlan'])
            ->where('user_id', Auth::id())
            ->when($request->get('q'), function ($q) use ($request) {
                $term = $request->get('q');
                $q->where(function ($sub) use ($term) {
                    $sub->where('status', 'like', "%{$term}%")
                        ->orWhere('approval_status', 'like', "%{$term}%")
                        ->orWhereHas('product', function ($p) use ($term) {
                            $p->where('name', 'like', "%{$term}%");
                        });
                });
            })
            ->orderByDesc('created_at');

        $loans = $query->paginate(10);

        // ── Analytics for Loan Dashboard ──
        $userId = Auth::id();
        $allLoans = Loan::where('user_id', $userId)->get();
        $totalPrincipal = $allLoans->sum('principal');
        $totalBalance = $allLoans->sum('balance_principal');
        $totalFunded = $allLoans->sum('funded_amount');
        $totalPaid = $totalPrincipal - $totalBalance;
        $payoffPercent = $totalPrincipal > 0 ? round(($totalPaid / $totalPrincipal) * 100, 1) : 0;

        $activeLoans = $allLoans->where('status', Loan::STATUS_ACTIVE);
        $activeCount = $activeLoans->count();
        $pendingCount = $allLoans->where('status', Loan::STATUS_PENDING)->count();
        $closedCount = $allLoans->where('status', Loan::STATUS_CLOSED)->count();

        $nextPayment = LoanPayment::whereHas('loan', fn ($q) => $q->where('user_id', $userId))
            ->where('status', 'due')
            ->orderBy('due_date')
            ->first();

        $avgRate = $activeLoans->count() > 0 ? $activeLoans->avg('interest_rate') : 0;

        $allPayments = LoanPayment::whereHas('loan', fn ($q) => $q->where('user_id', $userId))->get();
        $onTimeCount = $allPayments->where('status', 'paid')->count();
        $totalDue = $allPayments->whereIn('status', ['paid', 'due', 'late'])->count();
        $onTimeRate = $totalDue > 0 ? round(($onTimeCount / $totalDue) * 100, 1) : 100;

        $monthlyPayments = $allPayments->where('status', 'paid')->sum('amount_due');
        $utilization = $totalPrincipal > 0 ? round(($totalBalance / $totalPrincipal) * 100, 1) : 0;

        // Loan Health Score
        $healthScore = round(
            ($onTimeRate * 0.4) +
            ((100 - min($utilization, 100)) * 0.25) +
            ((100 - min(($monthlyPayments > 0 ? 30 : 0), 100)) * 0.2) +
            (min($payoffPercent, 100) * 0.15)
        ) / 10;
        $healthScore = min(max($healthScore, 0), 10);
        $healthLabel = $healthScore >= 8 ? 'Strong repayment standing' : ($healthScore >= 5 ? 'Fair standing' : 'Needs improvement');
        $rankLabel = $healthScore >= 8 ? 'Good' : ($healthScore >= 5 ? 'Fair' : 'At Risk');

        // Investment wallet stats
        $totalLoanWalletBalance = \App\Models\LoanWallet::where('user_id', $userId)
            ->where('status', \App\Models\LoanWallet::STATUS_ACTIVE)
            ->sum('balance');
        $totalInvestedFromLoans = \App\Models\LoanWallet::where('user_id', $userId)
            ->where('status', \App\Models\LoanWallet::STATUS_ACTIVE)
            ->sum('invested_amount');
        $totalEarningsAvailable = \App\Models\LoanWallet::where('user_id', $userId)
            ->where('status', \App\Models\LoanWallet::STATUS_ACTIVE)
            ->sum('earnings_balance');

        return view('user.sections.loans.index', compact(
            'page_title', 'loans',
            'totalPrincipal', 'totalBalance', 'totalFunded', 'totalPaid', 'payoffPercent',
            'activeCount', 'pendingCount', 'closedCount',
            'nextPayment', 'avgRate', 'onTimeRate', 'utilization',
            'healthScore', 'healthLabel', 'rankLabel',
            'totalLoanWalletBalance', 'totalInvestedFromLoans', 'totalEarningsAvailable'
        ));
    }

    public function stats()
    {
        $userId = Auth::id();
        $allLoans = Loan::where('user_id', $userId)->get();
        $totalPrincipal = $allLoans->sum('principal');
        $totalFunded = $allLoans->sum('funded_amount');

        $totalLoanWalletBalance = \App\Models\LoanWallet::where('user_id', $userId)
            ->where('status', \App\Models\LoanWallet::STATUS_ACTIVE)
            ->sum('balance');
        $totalInvestedFromLoans = \App\Models\LoanWallet::where('user_id', $userId)
            ->where('status', \App\Models\LoanWallet::STATUS_ACTIVE)
            ->sum('invested_amount');
        $totalEarningsAvailable = \App\Models\LoanWallet::where('user_id', $userId)
            ->where('status', \App\Models\LoanWallet::STATUS_ACTIVE)
            ->sum('earnings_balance');

        return response()->json([
            'totalPrincipal' => $totalPrincipal,
            'totalFunded' => $totalFunded,
            'totalLoanWalletBalance' => $totalLoanWalletBalance,
            'totalInvestedFromLoans' => $totalInvestedFromLoans,
            'totalEarningsAvailable' => $totalEarningsAvailable,
        ]);
    }

    public function create()
    {
        $page_title = __('Apply for Investment Loan');
        $products = LoanProduct::where('status', true)
            ->where('loan_type', Loan::TYPE_INVESTMENT)
            ->with('investmentPlan')
            ->orderBy('name')
            ->get();
        $investmentPlans = InvestmentPlan::where('is_active', true)->orderBy('name')->get();
        $countries = $this->worldCountries();
        $currencies = $this->worldCurrencies();

        return view('user.sections.loans.create', compact('page_title', 'products', 'investmentPlans', 'countries', 'currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_product_id' => ['nullable', 'exists:loan_products,id'],
            'investment_plan_id' => ['nullable', 'exists:investment_plans,id'],
            'principal' => ['required', 'numeric', 'min:100'], // Minimum $100
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:50'],
            'term_months' => ['required', 'integer', 'min:3', 'max:120'],
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
            'interest_method' => ['nullable', 'in:simple,compound,amortized'],
            'payment_frequency' => ['nullable', 'in:monthly,biweekly,weekly'],
            'rate_type' => ['nullable', 'in:fixed,variable'],
            'country' => ['nullable', 'string', 'max:100'],
            'currency' => ['nullable', 'string', 'max:10'],
            'purpose' => ['nullable', 'string', 'max:500'],
        ]);

        // If using a loan product, use its terms
        if ($request->loan_product_id) {
            $product = LoanProduct::findOrFail($request->loan_product_id);
            $interest_rate = $product->interest_rate;
            $term_months = $product->term_months;

            // Validate principal against product limits
            $request->validate([
                'principal' => ['required', 'numeric', 'min:'.$product->min_amount, 'max:'.$product->max_amount],
            ]);
        } else {
            $interest_rate = $request->interest_rate;
            $term_months = $request->term_months;
        }

        $loan = Loan::create([
            'user_id' => Auth::id(),
            'loan_product_id' => $request->loan_product_id,
            'investment_plan_id' => $request->investment_plan_id,
            'country' => $request->country,
            'currency' => $request->currency ?: 'USD',
            'principal' => $request->principal,
            'interest_rate' => $interest_rate,
            'term_months' => $term_months,
            'start_date' => $request->start_date,
            'balance_principal' => $request->principal,
            'status' => Loan::STATUS_PENDING,
            'approval_status' => Loan::APPROVAL_PENDING_REVIEW,
            'loan_type' => Loan::TYPE_INVESTMENT,
            'interest_method' => $request->input('interest_method', 'amortized'),
            'payment_frequency' => $request->input('payment_frequency', 'monthly'),
            'rate_type' => $request->input('rate_type', 'fixed'),
            'grace_days' => $request->input('grace_days', 15),
            'late_fee_type' => $request->input('late_fee_type', 'percent'),
            'late_fee_value' => $request->input('late_fee_value', 5), // 5%
            'early_settlement_fee_percent' => $request->input('early_settlement_fee_percent', 2),
            'withdrawal_restricted' => true,
            'deposit_required_for_withdrawal' => true,
            'withdrawal_fee_percent' => 2.5,
            'purpose' => $request->purpose,
        ]);

        // Don't generate schedule yet - wait for admin approval and funding

        return redirect()->route('user.loans.index')->with('success', __('Loan application submitted for review. You will be notified once approved.'));
    }

    public function show($id)
    {
        $loan = Loan::with(['product', 'payments', 'wallet', 'fundings', 'investmentPlan'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $page_title = __('Loan Details').' - '.$loan->product?->name ?? 'Custom';

        // Calculate earnings stats
        $totalEarnings = $loan->earnings()->where('status', 'credited')->sum('amount');
        $withdrawnEarnings = $loan->earnings()->where('status', 'withdrawn')->sum('amount');
        $availableEarnings = $totalEarnings - $withdrawnEarnings;

        // Wallet info
        $wallet = $loan->wallet->first();
        $walletBalance = $wallet ? $wallet->balance : 0;
        $walletInvested = $wallet ? $wallet->invested_amount : 0;

        return view('user.sections.loans.show', compact(
            'page_title', 'loan',
            'totalEarnings', 'withdrawnEarnings', 'availableEarnings',
            'walletBalance', 'walletInvested'
        ));
    }

    public function schedule($id)
    {
        $loan = Loan::with('payments')->where('user_id', Auth::id())->findOrFail($id);
        $page_title = __('Repayment Schedule').' - '.$loan->product?->name ?? 'Custom';

        return view('user.sections.loans.schedule', compact('page_title', 'loan'));
    }

    public function withdrawEarnings(Request $request, $id)
    {
        $loan = Loan::with(['wallet'])->where('user_id', Auth::id())->findOrFail($id);

        if (! $loan->canWithdrawEarnings()) {
            return back()->with('error', __('You must make a deposit before withdrawing loan earnings. Please deposit funds to your wallet first.'));
        }

        $wallet = $loan->wallet->first();
        if (! $wallet || $wallet->earnings_balance <= 0) {
            return back()->with('error', __('No earnings available for withdrawal.'));
        }

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:'.$wallet->earnings_balance],
            'wallet_id' => ['required', 'exists:user_wallets,id'],
        ]);

        $amount = $request->amount;
        $fee = $loan->calculateWithdrawalFee($amount);
        $netAmount = $amount - $fee;

        // Create transaction record
        $targetWallet = UserWallet::where('user_id', Auth::id())->findOrFail($request->wallet_id);

        \App\Models\Transaction::create([
            'user_id' => Auth::id(),
            'user_type' => \App\Constants\GlobalConst::USER,
            'wallet_id' => $targetWallet->id,
            'type' => 'loan_earnings_withdrawal',
            'trx_id' => 'LEW-'.strtoupper(\Illuminate\Support\Str::random(12)),
            'request_amount' => $amount,
            'request_currency' => $loan->currency ?? 'USD',
            'exchange_rate' => 1,
            'percent_charge' => $loan->withdrawal_fee_percent,
            'fixed_charge' => 0,
            'total_charge' => $fee,
            'total_payable' => $amount,
            'receive_amount' => $netAmount,
            'receiver_type' => \App\Constants\GlobalConst::USER,
            'receiver_id' => Auth::id(),
            'available_balance' => $targetWallet->balance + $netAmount,
            'payment_currency' => $loan->currency ?? 'USD',
            'remark' => 'Loan earnings withdrawal',
            'status' => \App\Constants\PaymentGatewayConst::STATUSSUCCESS,
            'is_deposit_for_loan_withdrawal' => false,
        ]);

        // Update target wallet
        $targetWallet->increment('balance', $netAmount);

        // Update loan wallet
        $wallet->withdrawEarnings($amount);

        // Record earning withdrawal
        \App\Models\LoanEarning::where('loan_wallet_id', $wallet->id)
            ->where('status', 'credited')
            ->orderBy('credited_at')
            ->limit(1)
            ->update([
                'status' => 'withdrawn',
                'withdrawn_at' => now(),
            ]);

        return back()->with('success', __('Earnings withdrawn successfully. Fee: ').$loan->currency.' '.number_format($fee, 2).'. Net: '.$loan->currency.' '.number_format($netAmount, 2));
    }

    public function edit($id)
    {
        $loan = Loan::where('user_id', Auth::id())->findOrFail($id);

        // Only allow editing if still pending review
        if ($loan->approval_status !== Loan::APPROVAL_PENDING_REVIEW) {
            return redirect()->route('user.loans.index')->with('error', __('Cannot edit loan after review has started.'));
        }

        $page_title = __('Edit Loan Application');
        $products = LoanProduct::where('status', true)->orderBy('name')->get();
        $countries = $this->worldCountries();
        $currencies = $this->worldCurrencies();

        return view('user.sections.loans.edit', compact('page_title', 'loan', 'products', 'countries', 'currencies'));
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::where('user_id', Auth::id())->findOrFail($id);

        if ($loan->approval_status !== Loan::APPROVAL_PENDING_REVIEW) {
            return redirect()->route('user.loans.index')->with('error', __('Cannot edit loan after review has started.'));
        }

        $request->validate([
            'interest_rate' => ['required', 'numeric', 'min:0'],
            'term_months' => ['required', 'integer', 'min:1', 'max:480'],
            'start_date' => ['nullable', 'date'],
            'interest_method' => ['nullable', 'in:simple,compound,amortized'],
            'payment_frequency' => ['nullable', 'in:monthly,biweekly,weekly'],
            'rate_type' => ['nullable', 'in:fixed,variable'],
            'rate_schedule' => ['nullable', 'array'],
            'grace_days' => ['nullable', 'integer', 'min:0', 'max:60'],
            'late_fee_type' => ['nullable', 'in:percent,flat'],
            'late_fee_value' => ['nullable', 'numeric', 'min:0'],
            'early_settlement_fee_percent' => ['nullable', 'numeric', 'min:0'],
            'country' => ['nullable', 'string', 'max:100'],
            'currency' => ['nullable', 'string', 'max:10'],
        ]);

        $loan->update([
            'interest_rate' => $request->interest_rate,
            'country' => $request->country,
            'currency' => $request->currency ?: 'USD',
            'term_months' => $request->term_months,
            'start_date' => $request->start_date,
            'interest_method' => $request->input('interest_method', $loan->interest_method),
            'payment_frequency' => $request->input('payment_frequency', $loan->payment_frequency),
            'rate_type' => $request->input('rate_type', $loan->rate_type),
            'rate_schedule' => $request->input('rate_schedule', $loan->rate_schedule),
            'grace_days' => $request->input('grace_days', $loan->grace_days),
            'late_fee_type' => $request->input('late_fee_type', $loan->late_fee_type),
            'late_fee_value' => $request->input('late_fee_value', $loan->late_fee_value),
            'early_settlement_fee_percent' => $request->input('early_settlement_fee_percent', $loan->early_settlement_fee_percent),
        ]);

        return redirect()->route('user.loans.index')->with('success', __('Loan application updated.'));
    }

    // Helper methods
    public function worldCountries()
    {
        $json = file_get_contents(resource_path('world/countries.json'));

        return collect(json_decode($json, true))->pluck('name')->sort()->values()->toArray();
    }

    public function worldCurrencies()
    {
        $json = file_get_contents(resource_path('world/countries.json'));

        return collect(json_decode($json, true))->pluck('currency')->unique()->sort()->values()->toArray();
    }
}
