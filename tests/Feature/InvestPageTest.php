<?php

namespace Tests\Feature;

use App\Models\Admin\Admin;
use App\Models\CryptoWallet;
use App\Models\EarningsLog;
use App\Models\InvestmentPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInvestment;
use App\Models\UserWallet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class InvestPageTest extends TestCase
{
    public function test_invest_new_page_renders()
    {
        $user = User::where('email', 'test-one@enzobank.org')->firstOrFail();
        $response = $this->actingAs($user)->get(route('user.invest.new'));
        $response->assertStatus(200);
        $response->assertSee('New Investment Plan', false);
        $response->assertSee('Basic Deluxe', false);
        $response->assertSee('Proceed to Deposit', false);
        $response->assertSee('Bitcoin', false);
        $response->assertSee('TRC20', false);
    }

    public function test_invest_portfolio_page_renders()
    {
        $user = User::where('email', 'test-one@enzobank.org')->firstOrFail();
        $response = $this->actingAs($user)->get(route('user.invest.portfolio'));
        $response->assertStatus(200);
        $response->assertSee('Total Invested', false);
        $response->assertSee('Active Plans', false);
        $response->assertSee('Total Earnings', false);
    }

    public function test_invest_earnings_page_renders()
    {
        $user = User::where('email', 'test-one@enzobank.org')->firstOrFail();
        $response = $this->actingAs($user)->get(route('user.invest.earnings'));
        $response->assertStatus(200);
        $response->assertSee('Total Earned', false);
        $response->assertSee('Credited', false);
    }

    public function test_admin_invest_review_page_renders()
    {
        $admin = Admin::find(1);
        $this->assertNotNull($admin, 'super admin (id=1) must exist to view review queue');
        $response = $this->actingAs($admin, 'admin')->get(route('admin.invest.index'));
        $response->assertStatus(200);
        $response->assertSee('Investments', false);
        $response->assertSee('Pending', false);
    }

    public function test_admin_invest_plans_page_renders()
    {
        $admin = Admin::find(1);
        $this->assertNotNull($admin, 'super admin (id=1) must exist to manage plans');
        $response = $this->actingAs($admin, 'admin')->get(route('admin.invest.plans'));
        $response->assertStatus(200);
        $response->assertSee('Add Plan', false);
        $response->assertSee('Plan Name', false);
        $response->assertSee('Basic Deluxe', false);
    }

    public function test_admin_approve_and_credit_flow_end_to_end()
    {
        $user = User::where('email', 'test-one@enzobank.org')->firstOrFail();
        $plan = InvestmentPlan::where('name', 'Basic Deluxe')->firstOrFail();
        $admin = Admin::find(1);
        $this->assertNotNull($admin);

        // Create a dedicated pending investment (cleaned up below)
        $investment = UserInvestment::create([
            'user_id'          => $user->id,
            'plan_id'          => $plan->id,
            'amount'           => 100.00,
            'payment_method'   => 'BTC',
            'wallet_address_used' => 'PLACEHOLDER-BTC-CONFIGURE-IN-ADMIN',
            'tx_hash'          => 'TESTTX-'.Str::random(16),
            'status'           => 'pending',
            'expected_return'  => 115.00,
            'maturity_date'    => now()->addDays(30),
        ]);

        $wallet = UserWallet::where('user_id', $user->id)->active()->first();
        $this->assertNotNull($wallet, 'test user must have an active wallet');
        $balanceBefore = (float) $wallet->balance;

        try {
            // Approve
            $this->actingAs($admin, 'admin')->post(route('admin.invest.approve', $investment->id));
            $investment->refresh();
            $this->assertEquals('active', $investment->status);
            $this->assertDatabaseHas('earnings_logs', [
                'investment_id' => $investment->id,
                'type'          => 'pending',
                'user_id'       => $user->id,
            ]);

            // Credit
            $this->actingAs($admin, 'admin')->post(route('admin.invest.credit', $investment->id));
            $investment->refresh();
            $this->assertEquals('completed', $investment->status);
            $this->assertDatabaseHas('earnings_logs', [
                'investment_id' => $investment->id,
                'type'          => 'credited',
            ]);
            $wallet->refresh();
            $this->assertEquals($balanceBefore + 15.00, (float) $wallet->balance);
            $this->assertDatabaseHas('transactions', [
                'user_id' => $user->id,
                'type'    => 'INVESTMENT',
                'request_amount' => 15.00,
            ]);
        } finally {
            // Restore wallet and clean up the dedicated rows
            $wallet->refresh();
            if ((float) $wallet->balance > $balanceBefore) {
                $wallet->balance = $balanceBefore;
                $wallet->save();
            }
            Transaction::where('user_id', $user->id)
                ->where('type', 'INVESTMENT')
                ->where('trx_id', 'like', 'INV-%')
                ->where('request_amount', 15.00)
                ->delete();
            EarningsLog::where('investment_id', $investment->id)->delete();
            $investment->delete();
        }
    }

    public function test_user_submit_proof_creates_pending_investment()
    {
        $user = User::where('email', 'test-one@enzobank.org')->firstOrFail();
        $plan = InvestmentPlan::where('name', 'Basic Deluxe')->firstOrFail();
        $wallet = CryptoWallet::where('symbol', 'BTC')->where('network', 'BTC')->firstOrFail();

        $response = $this->actingAs($user)->post(route('user.invest.submit.proof'), [
            'plan_id'            => $plan->id,
            'amount'             => 100.00,
            'method'             => 'BTC',
            'network'            => 'BTC',
            'wallet_address_used' => $wallet->wallet_address,
            'tx_hash'            => 'TESTTX-'.Str::random(16),
            'proof'              => UploadedFile::fake()->create('proof.png', 100, 'image/png'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_investments', [
            'user_id'  => $user->id,
            'plan_id'  => $plan->id,
            'amount'   => 100.00,
            'status'   => 'pending',
        ]);

        // Cleanup the dedicated rows + uploaded proof file + notification
        $investment = UserInvestment::where('user_id', $user->id)
            ->where('tx_hash', 'like', 'TESTTX-%')
            ->latest('id')->first();
        if ($investment) {
            $transaction = Transaction::where('user_id', $user->id)
                ->where('type', 'INVESTMENT')
                ->where('trx_id', 'like', 'INV-%')
                ->latest('id')->first();
            if ($transaction) {
                \App\Models\UserNotification::where('transaction_id', $transaction->id)->delete();
                $transaction->delete();
            }
            if ($investment->proof_url && file_exists(public_path($investment->proof_url))) {
                unlink(public_path($investment->proof_url));
            }
            $investment->delete();
        }
    }
}
