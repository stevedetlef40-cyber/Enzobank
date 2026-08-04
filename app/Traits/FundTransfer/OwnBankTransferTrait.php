<?php

namespace App\Traits\FundTransfer;

use App\Constants\GlobalConst;
use App\Constants\PaymentGatewayConst;
use App\Models\Beneficiary;
use App\Models\TemporaryData;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserWallet;
use App\Notifications\User\FundTransfer\OwnBankReceiverNotification;
use App\Notifications\User\FundTransfer\OwnBankSenderNotification;
use App\Notifications\User\FundTransfer\OwnBankTransferBlockedNotification;
use App\Providers\Admin\BasicSettingsProvider;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait OwnBankTransferTrait
{
    /**
     * Own Bank Transfer Select
     *
     * @param  App\Models\Beneficiary  $beneficiary
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ownBankTransferSelect(Request $request, Beneficiary $beneficiary)
    {
        if (auth()->user()->own_bank_transfer_blocked) {
            $beneficiary_name = $beneficiary->info->account_holder_name ?? $beneficiary->info->account_number ?? 'Unknown';
            try {
                auth()->user()->notify(new OwnBankTransferBlockedNotification(auth()->user(), $beneficiary_name));
                \Log::info('Own bank transfer blocked notification sent to user_id: '.auth()->user()->id);
            } catch (Exception $e) {
                \Log::error('Failed to send own bank transfer blocked notification to user_id: '.auth()->user()->id.' - '.$e->getMessage());
            }

            return back()->with(['error' => ['Own bank (EnzoBank to EnzoBank) transfer has been temporarily blocked. Please contact support on WhatsApp for activation.']]);
        }

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
     *  Own Bank Transfer Submit
     *
     * @param  $token
     * @return \Illuminate\Http\Response
     */
    public function ownBankTransferSubmit($validated, $fees_and_charge, $temp_data)
    {
        if (auth()->user()->own_bank_transfer_blocked) {
            $beneficiary_name = $temp_data->data->beneficiary->account_holder_name ?? $temp_data->data->beneficiary->account_number ?? 'Unknown';
            try {
                auth()->user()->notify(new OwnBankTransferBlockedNotification(auth()->user(), $beneficiary_name));
                \Log::info('Own bank transfer blocked notification sent to user_id: '.auth()->user()->id);
            } catch (Exception $e) {
                \Log::error('Failed to send own bank transfer blocked notification to user_id: '.auth()->user()->id.' - '.$e->getMessage());
            }
            $temp_data->delete();

            return redirect()->route('user.fund-transfer.index')->with(['error' => ['Own bank (EnzoBank to EnzoBank) transfer has been temporarily blocked. Please contact support on WhatsApp for activation.']]);
        }

        $user_wallet = UserWallet::active()->where('user_id', Auth::id())
            ->whereHas('currency', function ($query) use ($validated) {
                $query->where('code', $validated['currency']);
            })
            ->first();
        if (! $user_wallet) {
            return back()->with(['error' => ['Your selected currency wallet was not found']]);
        }
        $charge_calculation = $this->transferCharges($validated['amount'], $fees_and_charge, $user_wallet);

        $sender_wallet = UserWallet::active()->where('user_id', Auth::id())->first();
        if (! $sender_wallet) {
            return back()->with(['error' => ['Your wallet not found']]);
        }

        if ($charge_calculation['payable'] > $sender_wallet->balance) {
            return back()->with(['error' => ['Your wallet balance is insufficient']]);
        }

        $limit_response = transactionDailyAndMonthlyLimitCheck($charge_calculation, $fees_and_charge, $type = PaymentGatewayConst::TYPE_OWN_BANK_TRANSFER); // Daily And Monthly Limit Check
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
    public function ownBankTransferPreviewSubmit(TemporaryData $temp_data)
    {
        if (auth()->user()->own_bank_transfer_blocked) {
            $beneficiary_name = $temp_data->data->beneficiary->account_holder_name ?? $temp_data->data->beneficiary->account_number ?? 'Unknown';
            try {
                auth()->user()->notify(new OwnBankTransferBlockedNotification(auth()->user(), $beneficiary_name));
                \Log::info('Own bank transfer blocked notification sent to user_id: '.auth()->user()->id);
            } catch (Exception $e) {
                \Log::error('Failed to send own bank transfer blocked notification to user_id: '.auth()->user()->id.' - '.$e->getMessage());
            }
            $temp_data->delete();

            return redirect()->route('user.fund-transfer.index')->with(['error' => ['Own bank (EnzoBank to EnzoBank) transfer has been temporarily blocked. Please contact support on WhatsApp for activation.']]);
        }

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

        $receiver = User::active()->where('email', $temp_data->data->beneficiary->email)->first();
        if (! $receiver) {
            return back()->with(['error' => ['Receiver not found']]);
        }
        $receiver_wallet = UserWallet::active()->where('user_id', $receiver->id)
            ->whereHas('currency', function ($query) use ($sender_currency) {
                $query->where('code', $sender_currency);
            })
            ->first();
        if (! $receiver_wallet) {
            return back()->with(['error' => ['Receiver wallet not found for this currency']]);
        }
        if ($charges->payable > $sender_wallet->balance) {
            return back()->with(['error' => ['Your wallet balance is insufficient']]);
        }

        $trx_id = generateTrxString('transactions', 'trx_id', 'FT-', 14);
        // Make Transaction
        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'type' => PaymentGatewayConst::TYPE_OWN_BANK_TRANSFER,
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
                'receiver_type' => GlobalConst::USER,
                'receiver_id' => $sender_wallet->user->id,
                'payment_currency' => $sender_wallet->currency->code,
                'remark' => $temp_data->data->remark ?? '',
                'receiver_id' => $receiver->id,
                'details' => ['beneficiary' => $temp_data->data->beneficiary, 'charges' => $charges],
                'status' => PaymentGatewayConst::STATUSSUCCESS,
                'attribute' => GlobalConst::SEND,
                'created_at' => now(),
            ]);

            DB::table('user_wallets')->where('id', $sender_wallet->id)->update([
                'balance' => ($sender_wallet->balance - $charges->payable),
            ]);

            DB::table('user_wallets')->where('id', $receiver_wallet->id)->update([
                'balance' => ($receiver_wallet->balance + $charges->request_amount),
            ]);

            $this->createTransactionDeviceRecord($transaction->id);

            $temp_data->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('user.fund-transfer.index')->with(['error' => ['Something went wrong! Please try again']]);
        }

        try {
            user_notification_data_save($sender_wallet->user->id, $type = PaymentGatewayConst::TYPE_OWN_BANK_TRANSFER, $title = 'Fund Transfer', $transaction->id, $charges->request_amount, $gateway = null, $currency = $charges->sender_currency, $message = 'Fund Transfer Successful.');
            user_notification_data_save($receiver->id, $type, $title = 'Fund Received', $transaction->id, $charges->request_amount, $gateway = null, $currency = $charges->sender_currency, $message = 'You received '.get_amount($charges->request_amount, $charges->sender_currency).' from '.$sender_wallet->user->fullname);

            $basic_settings = BasicSettingsProvider::get();
            if ($basic_settings->email_notification) {
                try {
                    $sender_wallet->user->notify(new OwnBankSenderNotification($sender_wallet->user, $transaction));
                    \Log::info('Own bank transfer sender email sent to user_id: '.$sender_wallet->user->id.' trx_id: '.$transaction->trx_id);
                } catch (Exception $e) {
                    \Log::error('Failed to send own bank transfer sender email to user_id: '.$sender_wallet->user->id.' - '.$e->getMessage());
                }
                try {
                    $receiver_wallet->user->notify(new OwnBankReceiverNotification($receiver, $transaction));
                    \Log::info('Own bank transfer receiver email sent to user_id: '.$receiver->id.' trx_id: '.$transaction->trx_id);
                } catch (Exception $e) {
                    \Log::error('Failed to send own bank transfer receiver email to user_id: '.$receiver_wallet->user->id.' - '.$e->getMessage());
                }
            }
        } catch (Exception $e) {
            \Log::error('Own bank transfer notification error: '.$e->getMessage());
        }

        return redirect()->route('user.fund-transfer.transaction.success', $transaction->trx_id)->with(['success' => ['Fund transfer successfully done!']]);
    }
}
