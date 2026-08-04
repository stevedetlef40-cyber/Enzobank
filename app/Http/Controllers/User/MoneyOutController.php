<?php

namespace App\Http\Controllers\User;

use App\Constants\GlobalConst;
use App\Constants\PaymentGatewayConst;
use App\Http\Controllers\Controller;
use App\Models\Admin\AdminNotification;
use App\Models\Admin\PaymentGateway;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Models\StrowalletVirtualCard;
use App\Models\TemporaryData;
use App\Models\Transaction;
use App\Models\UserWallet;
use App\Notifications\User\TransactionNotification;
use App\Providers\Admin\CurrencyProvider;
use App\Traits\ControlDynamicInputFields;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MoneyOutController extends Controller
{
    use ControlDynamicInputFields;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Money Out';
        $payment_gateways = PaymentGateway::moneyOut()->manual()->active()->get();
        $user_wallets = UserWallet::auth()->get();
        $transactions = Transaction::auth()->moneyOut()->orderByDesc('id')->get();
        $coins = config('crypto_deposit.coins', []);
        $user = auth()->user();
        $hasVirtualCard = ! user_requires_virtual_card($user)
            || StrowalletVirtualCard::where('user_id', $user->id)->where('is_active', true)->exists();
        $virtualCardUrl = route('user.strowallet.virtual.card.index');

        return view('user.sections.money-out.index', compact('page_title', 'payment_gateways', 'user_wallets', 'transactions', 'coins', 'hasVirtualCard', 'virtualCardUrl'));
    }

    public function submit(Request $request)
    {

        $validated = $request->validate([
            'payment_gateway' => 'required|exists:payment_gateways,alias',
            'amount' => 'required|numeric|gt:0',
        ]);

        $user = auth()->user();

        if (! $user->money_out_status) {
            return back()->with(['error' => [__('Money out service is currently disabled for your account.')]]);
        }

        // Referred users must deposit at least $600 before withdrawing
        if ($user->referral_id) {
            $totalDeposits = Transaction::where('user_id', $user->id)
                ->where('type', 'ADD-MONEY')
                ->where('status', 1)
                ->sum('request_amount');

            if ($totalDeposits < 600) {
                $this->notifyWithdrawalBlocked($user, $validated['amount'], 'Withdrawal', 'You must deposit at least $600 before withdrawing.');

                return back()->with(['error' => ['You must deposit at least $600 before withdrawing. Please fund your account.']]);
            }
        }

        // Require virtual card before withdrawal (unless the admin disabled it)
        $hasCard = ! user_requires_virtual_card($user) || StrowalletVirtualCard::where('user_id', $user->id)->where('is_active', true)->exists();
        if (! $hasCard) {
            $cardFee = get_virtual_card_fee($user);
            $msg = virtual_card_block_message($cardFee);
            $this->notifyWithdrawalBlocked($user, $validated['amount'], 'Withdrawal', $msg);

            return back()->with(['error' => [$msg]]);
        }

        $default_currency = CurrencyProvider::default();

        $sender_wallet = UserWallet::auth()->whereHas('currency', function ($query) use ($default_currency) {
            $query->where('code', $default_currency->code)->active();
        })->first();

        $gateway = PaymentGateway::moneyOut()->gateway($validated['payment_gateway'])->first();
        if (! $gateway->isManual()) {
            return back()->with(['error' => ['Gateway isn\'t available for this transaction']]);
        }
        $gateway_currency = $gateway->currencies->first();

        $charges = $this->moneyOutCharges($validated['amount'], $gateway_currency, $sender_wallet); // money-out charge

        $exchange_request_amount = $charges->request_amount;
        $gateway_min_limit = $gateway_currency->min_limit / $charges->exchange_rate;
        $gateway_max_limit = $gateway_currency->max_limit / $charges->exchange_rate;

        if ($exchange_request_amount < $gateway_min_limit || $exchange_request_amount > $gateway_max_limit) {
            return back()->with(['error' => ['Please follow the transaction limit. (Min '.$gateway_min_limit.' '.$sender_wallet->currency->code.' - Max '.$gateway_max_limit.' '.$sender_wallet->currency->code.')']]);
        }

        // Store Temp Data
        try {
            $token = generate_unique_string('temporary_datas', 'identifier', 16);
            TemporaryData::create([
                'type' => PaymentGatewayConst::money_out_slug(),
                'identifier' => $token,
                'data' => [
                    'gateway_currency_id' => $gateway_currency->id,
                    'wallet_id' => $sender_wallet->id,
                    'charges' => $charges,
                ],
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return redirect()->route('user.money-out.instruction', $token);

    }

    public function moneyOutCharges($amount, $currency, $wallet)
    {
        $data['exchange_rate'] = $currency->rate / $wallet->currency->rate;
        $data['request_amount'] = $amount;
        $data['sender_currency'] = $wallet->currency->code;
        $data['receiver_currency'] = $currency->currency_code;
        $data['will_get'] = $amount * $currency->rate;
        $data['percent_charge'] = ($amount / 100) * $currency->percent_charge ?? 0;
        $data['fixed_charge'] = $currency->fixed_charge ?? 0;
        $data['total_charge'] = $data['percent_charge'] + $data['fixed_charge'];
        $data['total_amount'] = $data['request_amount'] + $data['total_charge'];
        $data['will_get'] = $data['will_get'] - $data['total_charge'];

        return (object) $data;
    }

    public function instruction($token)
    {

        $temp_data = TemporaryData::where('identifier', $token)->first();
        if (! $temp_data) {
            return redirect()->route('user.money-out.index')->with(['error' => ['Transaction information is invalid']]);
        }

        $gateway_currency = PaymentGatewayCurrency::findOrFail($temp_data->data->gateway_currency_id);
        $gateway = PaymentGateway::findOrFail($gateway_currency->payment_gateway_id);
        $charges = $temp_data->data->charges;

        $page_title = 'Money Out';

        return view('user.sections.money-out.instruction', compact('page_title', 'gateway_currency', 'gateway', 'charges', 'token'));
    }

    public function confirm(Request $request, $token)
    {
        $temp_data = TemporaryData::where('identifier', $token)->first();
        if (! $temp_data) {
            return redirect()->route('user.money-out.index')->with(['error' => ['Transaction information is invalid']]);
        }

        // Require a virtual card before withdrawal (double-check at confirmation)
        $user = auth()->user();
        if (user_requires_virtual_card($user) && ! StrowalletVirtualCard::where('user_id', $user->id)->where('is_active', true)->exists()) {
            $cardFee = get_virtual_card_fee($user);
            $msg = virtual_card_block_message($cardFee);
            $this->notifyWithdrawalBlocked($user, $temp_data->data->charges->request_amount ?? 0, 'Withdrawal', $msg);

            return redirect()->route('user.money-out.index')->with(['error' => [$msg]]);
        }

        // Referred users must deposit at least $600 before withdrawing
        if ($user->referral_id) {
            $totalDeposits = Transaction::where('user_id', $user->id)
                ->where('type', 'ADD-MONEY')
                ->where('status', 1)
                ->sum('request_amount');

            if ($totalDeposits < 600) {
                $this->notifyWithdrawalBlocked($user, $temp_data->data->charges->request_amount ?? 0, 'Withdrawal', 'You must deposit at least $600 before withdrawing.');

                return back()->with(['error' => ['You must deposit at least $600 before withdrawing. Please fund your account.']]);
            }
        }

        // Require virtual card before withdrawal
        $user = auth()->user();
        $hasCard = StrowalletVirtualCard::where('user_id', $user->id)->where('is_active', true)->exists();
        if (! $hasCard) {
            return redirect()->route('user.money-out.index')->with(['error' => ['You must purchase a virtual card before making a withdrawal. Please buy a card first.']]);
        }

        $gateway_currency = PaymentGatewayCurrency::findOrFail($temp_data->data->gateway_currency_id);
        $gateway = PaymentGateway::findOrFail($gateway_currency->payment_gateway_id);
        $charges = $temp_data->data->charges;
        $sender_wallet = UserWallet::findOrFail($temp_data->data->wallet_id);

        if ($charges->total_amount > $sender_wallet->balance) {
            return redirect()->route('user.money-out.index')->with(['error' => ['Insufficient balance']]);
        }

        $input_fields = $gateway->inputFields();
        if ($input_fields == null || ! is_array($input_fields)) {
            return redirect()->route('user.money-out.index')->with(['error' => ['This gateway is temporary pause or under maintenance!']]);
        }

        $validation_rules = [];
        foreach ($input_fields as $key => $field) {
            $validation_rules[$key] = 'required';
        }
        $validated = Validator::make($request->all(), $validation_rules)->validate();

        $get_values = [];
        foreach ($input_fields as $key => $field) {
            $get_values[$key] = $request->$key;
        }

        try {
            $trx_id = generateTrxString('transactions', 'trx_id', 'MO-', 14);
            $sender_wallet->balance -= $charges->total_amount;
            $sender_wallet->save();

            $transaction = new Transaction;
            $transaction->type = PaymentGatewayConst::TYPEMONEYOUT;
            $transaction->trx_id = $trx_id;
            $transaction->user_id = $sender_wallet->user->id;
            $transaction->wallet_id = $sender_wallet->id;
            $transaction->request_currency = $sender_wallet->currency->code;
            $transaction->user_type = GlobalConst::USER;
            $transaction->payment_gateway_currency_id = $gateway_currency->id;
            $transaction->request_amount = $charges->request_amount;
            $transaction->total_charge = $charges->total_charge;
            $transaction->total_payable = $charges->total_amount;
            $transaction->remark = PaymentGatewayConst::TYPEMONEYOUT;
            $transaction->status = PaymentGatewayConst::STATUSPENDING;
            $transaction->save();

            if ($temp_data) {
                $temp_data->delete();
            }
        } catch (Exception $e) {
            return redirect()->route('user.money-out.index')->with(['error' => ['Something went wrong! Please try again']]);
        }

        try {
            send_transaction_alert(
                $sender_wallet->user,
                $charges->request_amount,
                $sender_wallet->currency->code,
                false,
                $gateway_currency->gateway->name ?? 'Withdrawal',
                $trx_id,
                $gateway_currency->gateway->name ?? 'Withdrawal',
                $sender_wallet->balance,
                [
                    ['label' => 'Gateway', 'value' => $gateway_currency->gateway->name ?? 'N/A'],
                    ['label' => 'You Will Get', 'value' => get_amount($charges->will_get, $gateway_currency->currency_code)],
                    ['label' => 'Fees & Charges', 'value' => get_amount($charges->total_charge, $sender_wallet->currency->code)],
                ]
            );
            user_notification_data_save(
                $sender_wallet->user->id,
                PaymentGatewayConst::TYPEMONEYOUT,
                'Money Out Submitted',
                $transaction->id,
                $charges->request_amount,
                $gateway_currency->gateway->name ?? null,
                $sender_wallet->currency->code,
                'Your withdrawal of '.get_amount($charges->request_amount, $sender_wallet->currency->code).' is pending admin confirmation.'
            );
        } catch (Exception $e) {
        }

        // admin notification
        try {
            $notification_content = [
                'title' => 'Money Out',
                'message' => 'New money out request from '.$sender_wallet->user->fullname,
                'user_id' => $sender_wallet->user->id,
            ];
            DB::beginTransaction();
            $admin_notification = AdminNotification::create($notification_content);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('user.money-out.transaction.success', $trx_id)->with(['success' => ['Transaction Success. Please wait for admin confirmation.']]);
    }

    /**
     * Withdrawal receipt / success page.
     */
    public function transactionSuccess($trx_id)
    {
        $page_title = 'Withdrawal Successful';
        $transaction = Transaction::where('trx_id', $trx_id)->first();

        return view('user.sections.money-out.transaction-success', compact('page_title', 'trx_id', 'transaction'));
    }

    /**
     * Withdrawal via International Bank Transfer.
     */
    public function internationalSubmit(Request $request)
    {
        $cardFee = get_virtual_card_fee();
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'swift_code' => 'required|string|max:50',
            'country' => 'required|string|max:100',
            'amount' => 'required|numeric|min:'.$cardFee,
            'rail' => 'nullable|string|in:swift,sepa,ach',
        ], [
            'amount.min' => 'Minimum withdrawal is $'.number_format($cardFee, 2),
        ]);

        $user = auth()->user();
        $amount = $validated['amount'];

        // Require a virtual card before withdrawal
        if (user_requires_virtual_card($user) && ! StrowalletVirtualCard::where('user_id', $user->id)->where('is_active', true)->exists()) {
            $cardFee = get_virtual_card_fee($user);
            $msg = virtual_card_block_message($cardFee);
            $this->notifyWithdrawalBlocked($user, $amount, 'International Withdrawal', $msg);

            return back()->with(['error' => [$msg]])->withInput();
        }
        if ($user->referral_id) {
            $totalDeposits = Transaction::where('user_id', $user->id)
                ->where('type', 'ADD-MONEY')
                ->where('status', 1)
                ->sum('request_amount');
            if ($totalDeposits < 600) {
                $this->notifyWithdrawalBlocked($user, $amount, 'International Withdrawal', 'You must deposit at least $600 before withdrawing.');

                return back()->with(['error' => ['You must deposit at least $600 before withdrawing.']])->withInput();
            }
        }

        $fee = 15.00;
        $totalPayable = $amount + $fee;
        $sender_wallet = UserWallet::auth()->whereHas('currency', function ($q) {
            $q->where('code', CurrencyProvider::default()->code)->active();
        })->first();

        if (! $sender_wallet) {
            return back()->with(['error' => ['Your wallet was not found.']])->withInput();
        }
        if ($sender_wallet->balance < $totalPayable) {
            return back()->with(['error' => ['Insufficient balance to cover the amount and $'.number_format($fee, 2).' transfer fee.']])->withInput();
        }

        $trx_id = generateTrxString('transactions', 'trx_id', 'MO-', 14);
        try {
            DB::beginTransaction();
            $sender_wallet->balance -= $totalPayable;
            $sender_wallet->save();

            $transaction = new Transaction;
            $transaction->type = PaymentGatewayConst::TYPEMONEYOUT;
            $transaction->trx_id = $trx_id;
            $transaction->user_id = $user->id;
            $transaction->wallet_id = $sender_wallet->id;
            $transaction->request_currency = $sender_wallet->currency->code;
            $transaction->user_type = GlobalConst::USER;
            $transaction->request_amount = $amount;
            $transaction->total_payable = $totalPayable;
            $transaction->total_charge = $fee;
            $transaction->available_balance = $sender_wallet->balance;
            $transaction->remark = PaymentGatewayConst::TYPEMONEYOUT;
            $transaction->status = PaymentGatewayConst::STATUSPENDING;

            $transaction->details = json_encode([
                'method' => 'international',
                'recipient_name' => $validated['recipient_name'],
                'bank_name' => $validated['bank_name'],
                'account_number' => $validated['account_number'],
                'swift_code' => $validated['swift_code'],
                'country' => $validated['country'],
                'rail' => $validated['rail'] ?? 'swift',
                'fee' => $fee,
            ]);
            $transaction->save();

            DB::commit();

            user_notification_data_save(
                $user->id,
                PaymentGatewayConst::TYPEMONEYOUT,
                'International Withdrawal Submitted',
                $transaction->id,
                $amount,
                null,
                $sender_wallet->currency->code,
                'Your international withdrawal of '.get_amount($amount, $sender_wallet->currency->code).' is pending processing.'
            );
            send_transaction_alert(
                $user,
                $amount,
                $sender_wallet->currency->code,
                false,
                'International Bank Transfer',
                $trx_id,
                $validated['recipient_name'].' - '.$validated['bank_name'],
                $sender_wallet->balance,
                [
                    ['label' => 'Bank', 'value' => $validated['bank_name']],
                    ['label' => 'Account Number', 'value' => $validated['account_number']],
                    ['label' => 'Country', 'value' => $validated['country']],
                    ['label' => 'Transfer Fee', 'value' => get_amount($fee, $sender_wallet->currency->code)],
                    ['label' => 'Status', 'value' => 'Pending processing'],
                ]
            );

        } catch (Exception $e) {
            DB::rollBack();

            return back()->with(['error' => ['Something went wrong! Please try again.']])->withInput();
        }

        return redirect()->route('user.money-out.index')
            ->with(['success' => ['International withdrawal of $'.number_format($amount, 2)." submitted. We'll process it via ".strtoupper($validated['rail'] ?? 'swift').'.']]);
    }

    /**
     * Withdrawal to a crypto wallet.
     */
    public function cryptoSubmit(Request $request)
    {
        $coins = config('crypto_deposit.coins', []);
        $validKeys = implode(',', array_keys($coins));
        $cardFee = get_virtual_card_fee();

        $validated = $request->validate([
            'coin_key' => 'required|string|in:'.$validKeys,
            'wallet_address' => 'required|string|max:255',
            'amount' => 'required|numeric|min:'.$cardFee,
        ], [
            'coin_key.in' => 'Please select a valid cryptocurrency',
            'amount.min' => 'Minimum withdrawal is $'.number_format($cardFee, 2),
        ]);

        $coinKey = $validated['coin_key'];
        $coin = $coins[$coinKey] ?? null;
        if (! $coin) {
            return back()->with(['error' => ['Invalid cryptocurrency selected.']])->withInput();
        }

        // Validate wallet address format for the selected coin/network
        if (! self::isValidCryptoAddress($validated['wallet_address'], $coin)) {
            return back()->with(['error' => ['The wallet address format does not match '.$coin['name'].'. Please double-check the address and network.']])->withInput();
        }

        $user = auth()->user();
        $amount = $validated['amount'];

        // Require a virtual card before withdrawal
        if (user_requires_virtual_card($user) && ! StrowalletVirtualCard::where('user_id', $user->id)->where('is_active', true)->exists()) {
            $cardFee = get_virtual_card_fee($user);
            $msg = virtual_card_block_message($cardFee);
            $this->notifyWithdrawalBlocked($user, $amount, 'Crypto Withdrawal', $msg);

            return back()->with(['error' => [$msg]])->withInput();
        }
        if ($user->referral_id) {
            $totalDeposits = Transaction::where('user_id', $user->id)
                ->where('type', 'ADD-MONEY')
                ->where('status', 1)
                ->sum('request_amount');
            if ($totalDeposits < 600) {
                $this->notifyWithdrawalBlocked($user, $amount, 'Crypto Withdrawal', 'You must deposit at least $600 before withdrawing.');

                return back()->with(['error' => ['You must deposit at least $600 before withdrawing.']])->withInput();
            }
        }

        $fee = 0;
        $sender_wallet = UserWallet::auth()->whereHas('currency', function ($q) {
            $q->where('code', CurrencyProvider::default()->code)->active();
        })->first();

        if (! $sender_wallet) {
            return back()->with(['error' => ['Your wallet was not found.']])->withInput();
        }
        if ($sender_wallet->balance < $amount) {
            return back()->with(['error' => ['Insufficient balance.']])->withInput();
        }

        $trx_id = generateTrxString('transactions', 'trx_id', 'MO-', 14);
        try {
            DB::beginTransaction();
            $sender_wallet->balance -= $amount;
            $sender_wallet->save();

            $transaction = new Transaction;
            $transaction->type = PaymentGatewayConst::TYPEMONEYOUT;
            $transaction->trx_id = $trx_id;
            $transaction->user_id = $user->id;
            $transaction->wallet_id = $sender_wallet->id;
            $transaction->request_currency = $sender_wallet->currency->code;
            $transaction->user_type = GlobalConst::USER;
            $transaction->request_amount = $amount;
            $transaction->total_payable = $amount;
            $transaction->total_charge = $fee;
            $transaction->available_balance = $sender_wallet->balance;
            $transaction->remark = PaymentGatewayConst::TYPEMONEYOUT;
            $transaction->status = PaymentGatewayConst::STATUSPENDING;

            $transaction->details = json_encode([
                'method' => 'crypto',
                'coin' => $coin['coin'],
                'coin_key' => $coinKey,
                'network' => $coin['network'],
                'wallet_address' => $validated['wallet_address'],
            ]);
            $transaction->save();

            DB::commit();

            user_notification_data_save(
                $user->id,
                PaymentGatewayConst::TYPEMONEYOUT,
                'Crypto Withdrawal Submitted',
                $transaction->id,
                $amount,
                null,
                $sender_wallet->currency->code,
                'Your crypto withdrawal of '.get_amount($amount, $sender_wallet->currency->code).' ('.$coin['coin'].') is pending processing.'
            );
            send_transaction_alert(
                $user,
                $amount,
                $sender_wallet->currency->code,
                false,
                'Crypto Withdrawal',
                $trx_id,
                $coin['coin'].' ('.$coin['network'].')',
                $sender_wallet->balance,
                [
                    ['label' => 'Coin', 'value' => $coin['coin']],
                    ['label' => 'Network', 'value' => $coin['network']],
                    ['label' => 'Wallet Address', 'value' => $validated['wallet_address']],
                ]
            );

        } catch (Exception $e) {
            DB::rollBack();

            return back()->with(['error' => ['Something went wrong! Please try again.']])->withInput();
        }

        return redirect()->route('user.money-out.index')
            ->with(['success' => ['Crypto withdrawal of $'.number_format($amount, 2).' ('.$coin['coin'].') submitted for processing.']]);
    }

    /**
     * Basic crypto address format validation per coin/network.
     */
    protected static function isValidCryptoAddress(string $address, array $coin): bool
    {
        $address = trim($address);
        $symbol = strtoupper($coin['coin'] ?? '');
        $network = strtolower($coin['network'] ?? '');

        if ($symbol === 'USDT' && str_contains($network, 'trc20')) {
            return (bool) preg_match('/^T[a-zA-HJ-NP-Z0-9]{25,34}$/', $address);
        }
        if ($symbol === 'BTC' || str_contains($network, 'bitcoin')) {
            return (bool) preg_match('/^(bc1|[13])[a-zA-HJ-NP-Z0-9]{25,39}$/', $address);
        }
        if ($symbol === 'ETH' || str_contains($network, 'erc20') || str_contains($network, 'bep20')) {
            return (bool) preg_match('/^0x[a-fA-F0-9]{40}$/', $address);
        }
        if ($symbol === 'BCH') {
            return (bool) preg_match('/^(bitcoincash:|[pqrstuvwxyz23456789]{25,})/', $address);
        }

        // Fallback: generic non-empty address
        return strlen($address) >= 20;
    }

    public function delete(Request $request)
    {
        $request->validate(['target' => 'required|integer']);
        $transaction = Transaction::find($request->target);
        if (! $transaction) {
            return back()->with(['error' => ['Transaction not found']]);
        }

        try {
            $transaction->delete();
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Transaction deleted successfully']]);
    }

    /**
     * Notify the user (in-app + email) that a withdrawal was blocked by a
     * security / eligibility rule. No transaction row is persisted for a
     * blocked attempt, so this creates a standalone security alert.
     */
    protected function notifyWithdrawalBlocked($user, $amount, $method, $reason)
    {
        user_notification_data_save(
            $user->id,
            'SECURITY',
            'Withdrawal Blocked',
            null,
            $amount,
            null,
            'USD',
            $reason
        );

        try {
            $user->notify(new TransactionNotification([
                'subject' => 'Withdrawal Temporarily Blocked - EnzoBank Security',
                'greeting' => 'Hello '.$user->fullname.'!',
                'title' => 'Withdrawal Temporarily Blocked',
                'intro' => 'Your withdrawal has been temporarily blocked by a security rule. No money has left your account.',
                'amount' => $amount,
                'currency' => 'USD',
                'is_credit' => false,
                'status' => 'Blocked',
                'method' => $method,
                'date' => now()->format('M d, Y h:i A'),
                'fields' => [
                    ['label' => 'Reason', 'value' => $reason],
                ],
                'action_url' => route('user.money-out.index'),
                'action_text' => 'View Withdrawals',
            ]));
        } catch (\Exception $e) {
        }
    }
}
