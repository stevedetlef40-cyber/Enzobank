<?php

namespace App\Traits\FundTransfer;

use App\Constants\GlobalConst;
use App\Constants\PaymentGatewayConst;
use App\Models\Beneficiary;
use App\Models\StrowalletVirtualCard;
use App\Models\TemporaryData;
use App\Models\Transaction;
use App\Models\UserWallet;
use App\Notifications\User\FundTransfer\OtherBankSenderNotification;
use App\Providers\Admin\BasicSettingsProvider;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait OtherBankTransferTrait
{
    /**
     * Other Bank Transfer Select
     *
     * @param  App\Models\Beneficiary  $beneficiary
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function otherBankTransferSelect(Request $request, Beneficiary $beneficiary)
    {
        $data['beneficiary'] = $beneficiary->info;
        $temp_identifier = generate_unique_string('temporary_datas', 'identifier', 60);
        try {
            TemporaryData::create([
                'type' => GlobalConst::FUND_TRANSFER,
                'identifier' => $temp_identifier,
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong!. Please try again.']]);
        }

        return redirect()->route('user.fund-transfer.create', $temp_identifier);
    }

    /**
     *  Other Bank Transfer Submit
     *
     * @param  $token
     * @return \Illuminate\Http\Response
     */
    public function otherBankTransferSubmit($validated, $fees_and_charge, $temp_data)
    {

        $user_wallet = UserWallet::active()->where('user_id', Auth::id())
            ->whereHas('currency', function ($query) use ($validated) {
                $query->where('code', $validated['currency']);
            })
            ->first();
        if (! $user_wallet) {
            return back()->with(['error' => ['Your selected currency wallet was not found']]);
        }
        $charge_calculation = $this->transferCharges($validated['amount'], $fees_and_charge, $user_wallet);
        $limit_response = transactionDailyAndMonthlyLimitCheck($charge_calculation, $fees_and_charge, $type = PaymentGatewayConst::TYPE_OTHER_BANK_TRANSFER); // Daily And Monthly Limit Check
        if ($limit_response instanceof RedirectResponse) {
            return $limit_response;
        }

        $update_data = (array) $temp_data->data;
        $update_data['charges'] = $charge_calculation;
        $update_data['remark'] = $validated['remarks'];

        try {
            $temp_data->update(['data' => $update_data]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong!. Please try again.']]);
        }

        return redirect()->route('user.fund-transfer.preview', $validated['temp_token']);
    }

    /**
     *  Own Bank Transfer Submit
     *
     * @param  App\Models\TemporaryData  $temp_data
     * @return \Illuminate\Http\Response
     */
    public function otherBankTransferPreviewSubmit(TemporaryData $temp_data)
    {

        $charges = $temp_data->data->charges;
        $sender_currency = $charges->sender_currency ?? null;

        $sender_wallet = UserWallet::active()->where('user_id', Auth::id())
            ->whereHas('currency', function ($query) use ($sender_currency) {
                $query->where('code', $sender_currency);
            })
            ->first();
        if (! $sender_wallet) {
            return back()->with(['error' => ['Your wallet not found']]);
        }
        if ($charges->payable > $sender_wallet->balance) {
            return back()->with(['error' => ['Your wallet balance is insufficient']]);
        }

        // Other-bank transfers require a virtual card first, unless the
        // admin has disabled the card requirement for this user.
        $user = $sender_wallet->user;
        if (user_requires_virtual_card($user) && ! StrowalletVirtualCard::where('user_id', $user->id)->where('is_active', true)->exists()) {
            $msg = notify_virtual_card_blocked($user, $charges->request_amount ?? 0, 'International Bank Transfer', $charges->sender_currency ?? 'USD');

            return back()->with(['error' => [$msg]]);
        }

        $trx_id = generateTrxString('transactions', 'trx_id', 'FT-', 14);
        // Make Transaction
        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'type' => PaymentGatewayConst::TYPE_OTHER_BANK_TRANSFER,
                'trx_id' => $trx_id,
                'user_type' => GlobalConst::USER,
                'user_id' => $sender_wallet->user->id,
                'wallet_id' => $sender_wallet->id,
                'request_amount' => $charges->request_amount,
                'request_currency' => $charges->sender_currency,
                'exchange_rate' => $sender_wallet->currency->rate,
                'percent_charge' => $charges->percent_charge,
                'fixed_charge' => $charges->fixed_charge,
                'total_charge' => $charges->total_charge,
                'total_payable' => $charges->payable,
                'available_balance' => $sender_wallet->balance - $charges->payable,
                'receive_amount' => $charges->request_amount,
                'payment_currency' => $sender_wallet->currency->code,
                'remark' => $temp_data->data->remark ?? null,
                'details' => ['beneficiary' => $temp_data->data->beneficiary, 'charges' => $charges],
                'status' => PaymentGatewayConst::STATUSPENDING,
                'attribute' => GlobalConst::SEND,
                'created_at' => now(),
            ]);

            DB::table('user_wallets')->where('id', $sender_wallet->id)->update([
                'balance' => ($sender_wallet->balance - $charges->payable),
            ]);
            $this->createTransactionDeviceRecord($transaction->id);

            $temp_data->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('user.fund-transfer.index')->with(['error' => ['Something went wrong! Please try again']]);
        }

        try {
            user_notification_data_save($sender_wallet->user->id, $type = PaymentGatewayConst::TYPE_OTHER_BANK_TRANSFER, $title = 'Fund Transfer', $transaction->id, $charges->request_amount, $gateway = null, $currency = $charges->sender_currency, $message = 'Fund Transfer Successful.');

            $basic_settings = BasicSettingsProvider::get();
            if ($basic_settings->email_notification) {
                try {
                    $sender_wallet->user->notify(new OtherBankSenderNotification($sender_wallet->user, $transaction));
                    \Log::info('Other bank transfer sender email sent to user_id: '.$sender_wallet->user->id.' trx_id: '.$transaction->trx_id);
                } catch (Exception $e) {
                    \Log::error('Failed to send other bank transfer sender email to user_id: '.$sender_wallet->user->id.' - '.$e->getMessage());
                }
            }
        } catch (Exception $e) {
            \Log::error('Other bank transfer notification error: '.$e->getMessage());
        }

        return redirect()->route('user.fund-transfer.transaction.success', $transaction->trx_id)->with(['success' => ['Fund transfer successfully done!']]);
    }
}
