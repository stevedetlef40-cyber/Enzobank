<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPlan;
use App\Models\UserInvestment;
use App\Models\EarningsLog;
use App\Models\UserWallet;
use App\Models\Transaction;
use App\Constants\PaymentGatewayConst;
use App\Constants\GlobalConst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class InvestmentController extends Controller
{
    /**
     * All user investments (admin review queue)
     */
    public function index(Request $request)
    {
        $page_title = 'Investments';
        $query = UserInvestment::with(['user', 'plan'])->latest('id');

        if ($request->filled('status') && in_array($request->status, ['pending', 'active', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }

        $investments = $query->paginate(15)->withQueryString();

        $counts = [
            'pending'   => UserInvestment::where('status', 'pending')->count(),
            'active'    => UserInvestment::where('status', 'active')->count(),
            'completed' => UserInvestment::where('status', 'completed')->count(),
            'cancelled' => UserInvestment::where('status', 'cancelled')->count(),
            'total'     => UserInvestment::count(),
        ];

        return view('admin.sections.investments.index', compact('page_title', 'investments', 'counts'));
    }

    /**
     * Approve a pending investment
     */
    public function approve($id)
    {
        $investment = UserInvestment::with('plan')->findOrFail($id);

        if ($investment->status !== 'pending') {
            return back()->with(['error' => ['This investment is not pending.']]);
        }

        $profit = max(0, floatval($investment->expected_return) - floatval($investment->amount));

        DB::beginTransaction();
        try {
            $investment->status = 'active';
            $investment->save();

            // Pending earning entry (credited manually once matured)
            EarningsLog::firstOrCreate(
                ['investment_id' => $investment->id, 'type' => 'pending'],
                [
                    'user_id'      => $investment->user_id,
                    'amount'       => $profit,
                    'credited_at'  => $investment->maturity_date,
                ]
            );

            DB::commit();

            return back()->with(['success' => ['Investment approved successfully!']]);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
    }

    /**
     * Reject a pending investment
     */
    public function reject($id)
    {
        $investment = UserInvestment::findOrFail($id);

        if ($investment->status !== 'pending') {
            return back()->with(['error' => ['This investment is not pending.']]);
        }

        try {
            $investment->status = 'cancelled';
            $investment->save();

            return back()->with(['success' => ['Investment rejected.']]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
    }

    /**
     * Mark an active investment's earnings as credited
     */
    public function credit($id)
    {
        $investment = UserInvestment::with('plan')->findOrFail($id);

        if ($investment->status !== 'active') {
            return back()->with(['error' => ['Only active investments can be credited.']]);
        }

        $earned = max(0, floatval($investment->expected_return) - floatval($investment->amount));
        if ($earned <= 0) {
            return back()->with(['error' => ['Nothing to credit for this investment.']]);
        }

        DB::beginTransaction();
        try {
            $investment->status = 'completed';
            $investment->save();

            $earning = EarningsLog::where('investment_id', $investment->id)->first();
            if ($earning) {
                $earning->type = 'credited';
                $earning->credited_at = now();
                $earning->save();
            } else {
                EarningsLog::create([
                    'user_id'      => $investment->user_id,
                    'investment_id' => $investment->id,
                    'amount'       => $earned,
                    'type'         => 'credited',
                    'credited_at'  => now(),
                ]);
            }

            // Credit the user's default wallet
            $wallet = UserWallet::where('user_id', $investment->user_id)->active()->first();
            if ($wallet) {
                $wallet->balance += $earned;
                $wallet->save();
            }

            $trx_id = generateTrxString('transactions', 'trx_id', 'INV-', 14);
            Transaction::create([
                'type'              => PaymentGatewayConst::TYPEINVEST,
                'trx_id'            => $trx_id,
                'user_type'         => GlobalConst::USER,
                'user_id'           => $investment->user_id,
                'request_amount'    => $earned,
                'request_currency'  => 'USD',
                'available_balance' => $wallet ? $wallet->balance : 0,
                'status'            => PaymentGatewayConst::STATUSSUCCESS,
                'attribute'         => GlobalConst::RECEIVED,
                'details'           => json_encode([
                    'plan_name' => $investment->plan->name ?? '',
                    'note'      => 'Investment earnings credited',
                    'amount'    => $earned,
                ]),
            ]);

            DB::commit();

            return back()->with(['success' => ['Earnings credited to user wallet!']]);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
    }

    /**
     * Investment plans management
     */
    public function plans()
    {
        $page_title = 'Investment Plans';
        $plans = InvestmentPlan::latest('id')->paginate(15);

        return view('admin.sections.investments.plans', compact('page_title', 'plans'));
    }

    public function planStore(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'min_amount'    => 'required|numeric|min:0',
            'max_amount'    => 'nullable|numeric|gt:min_amount',
            'roi_percent'   => 'required|numeric|min:0|max:1000',
            'duration_days' => 'required|integer|min:1',
            'is_active'     => 'required|boolean',
        ]);

        InvestmentPlan::create($request->only('name', 'min_amount', 'max_amount', 'roi_percent', 'duration_days', 'is_active'));

        return back()->with(['success' => ['Plan created successfully!']]);
    }

    public function planUpdate(Request $request, $id)
    {
        $plan = InvestmentPlan::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:100',
            'min_amount'    => 'required|numeric|min:0',
            'max_amount'    => 'nullable|numeric|gt:min_amount',
            'roi_percent'   => 'required|numeric|min:0|max:1000',
            'duration_days' => 'required|integer|min:1',
            'is_active'     => 'required|boolean',
        ]);

        $plan->update($request->only('name', 'min_amount', 'max_amount', 'roi_percent', 'duration_days', 'is_active'));

        return back()->with(['success' => ['Plan updated successfully!']]);
    }

    public function planStatus($id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        $plan->is_active = ! $plan->is_active;
        $plan->save();

        return back()->with(['success' => ['Plan ' . ($plan->is_active ? 'activated' : 'deactivated') . '!']]);
    }

    public function planDelete($id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        $plan->delete();

        return back()->with(['success' => ['Plan deleted successfully!']]);
    }
}
