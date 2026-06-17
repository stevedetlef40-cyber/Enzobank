<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanPaymentController extends Controller
{
    public function payNext(Request $request)
    {
        $request->validate([
            'loan_id' => ['required','integer','exists:loans,id'],
            'amount' => ['required','numeric','min:0.01'],
        ]);
        $loan = Loan::where('user_id', Auth::id())->findOrFail($request->loan_id);
        $payment = $loan->payments()->whereIn('status',['due','late'])->orderBy('due_date')->first();
        if (!$payment) {
            return back()->with('error', __('No due installments'));
        }
        $amount = (float) $request->amount;
        DB::transaction(function () use ($loan, $payment, $amount) {
            $allocFee = min($amount, max(0.0, (float) $payment->fee_due - (float) $payment->fee_paid));
            $amount -= $allocFee;
            $payment->fee_paid += $allocFee;
            $allocInt = min($amount, max(0.0, (float) $payment->interest_due - (float) $payment->interest_paid));
            $amount -= $allocInt;
            $payment->interest_paid += $allocInt;
            $allocPrin = min($amount, max(0.0, (float) $payment->principal_due - (float) $payment->principal_paid));
            $amount -= $allocPrin;
            $payment->principal_paid += $allocPrin;
            $payment->amount_paid = (float) $payment->fee_paid + (float) $payment->interest_paid + (float) $payment->principal_paid;
            if ($payment->amount_paid + 0.0001 >= $payment->amount_due) {
                $payment->status = 'paid';
            }
            $loan->balance_principal = max(0.0, (float) $loan->balance_principal - $allocPrin);
            $loan->accrued_interest = max(0.0, (float) $loan->accrued_interest - $allocInt);
            $payment->remaining_principal = $loan->balance_principal;
            $payment->save();
            $loan->save();
        });
        return back()->with('success', __('Payment recorded'));
    }
}

