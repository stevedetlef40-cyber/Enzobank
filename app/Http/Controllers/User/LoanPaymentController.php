<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanPaymentController extends Controller
{
    public function payNext(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|integer|exists:loans,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            // @todo: implement full loan payment processing
            DB::commit();

            return redirect()->back()->with(['success' => ['Payment processed successfully.']]);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => ['Payment failed. Please try again.']]);
        }
    }
}
