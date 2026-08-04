<?php

namespace App\Http\Controllers\User;

use App\Constants\PaymentGatewayConst;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\UserWallet;
use App\Notifications\User\StatementNotification;
use App\Providers\Admin\BasicSettingsProvider;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StatementController extends Controller
{
    /**
     * Bank statement page (filter form + results table).
     *
     * @method GET
     */
    public function index()
    {
        $page_title = 'Bank Statement';

        return view('user.sections.statement', compact('page_title'));
    }

    /**
     * Build the filtered transaction query from request filters.
     */
    private function statementQuery(Request $request)
    {
        $query = Transaction::auth();

        if ($request->filled('trx_id')) {
            $query->where('trx_id', 'like', '%'.$request->trx_id.'%');
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereDate('created_at', '>=', $request->from_date)
                ->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('type') && $request->type != '*') {
            if ($request->type == 'FUND-TRANSFER') {
                $query->whereIn('type', [
                    PaymentGatewayConst::TYPE_OTHER_BANK_TRANSFER,
                    PaymentGatewayConst::TYPE_OWN_BANK_TRANSFER,
                ]);
            } else {
                $query->where('type', $request->type);
            }
        }

        if ($request->filled('status') && $request->status != '*') {
            $query->where('status', $request->status);
        }

        return $query;
    }

    /**
     * Filter transactions and render the results table
     * (or export as PDF when submit_type = EXPORT).
     *
     * @method GET
     */
    public function filterStatement(Request $request)
    {
        $page_title = 'Bank Statement';

        $transactions = $this->statementQuery($request)->orderBy('id', 'desc')->get();

        if ($request->submit_type == 'EXPORT') {
            return $this->download($transactions, $request);
        }

        return view('user.sections.statement', compact('page_title', 'transactions'));
    }

    /**
     * Dedicated endpoint to download the filtered statement as a PDF.
     *
     * @method GET
     */
    public function export(Request $request)
    {
        $transactions = $this->statementQuery($request)->orderBy('id', 'desc')->get();

        return $this->download($transactions, $request);
    }

    /**
     * Build and stream the bank statement PDF document.
     */
    public function download($transactions, Request $request)
    {
        $transferTypes = [
            PaymentGatewayConst::TYPE_OWN_BANK_TRANSFER,
            PaymentGatewayConst::TYPE_OTHER_BANK_TRANSFER,
            PaymentGatewayConst::TYPE_MOBILE_WALLET_TRANSFER,
        ];

        $total_transaction = $transactions->count();
        $total_add_money = $transactions->where('type', PaymentGatewayConst::TYPEADDMONEY)->sum('request_amount');
        $total_money_out = $transactions->where('type', PaymentGatewayConst::TYPEMONEYOUT)->sum('request_amount');
        $fund_transfer = $transactions->where('user_id', Auth::id())->whereIn('type', $transferTypes)->sum('request_amount');
        $fund_received = $transactions->where('receiver_id', Auth::id())->whereIn('type', $transferTypes)->sum('request_amount');
        $user_wallet = UserWallet::auth()->first();
        $date = [
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'type' => $request->type,
            'status' => $request->status,
        ];

        $pdf = Pdf::loadView('user.sections.pdf.statement', compact(
            'transactions',
            'total_transaction',
            'total_add_money',
            'total_money_out',
            'fund_transfer',
            'fund_received',
            'user_wallet',
            'date'
        ))->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        $basic_settings = BasicSettingsProvider::get();
        $pdf_download_name = $basic_settings->site_name.'-statement.pdf';

        // Save PDF to storage for email attachment
        $pdfContent = $pdf->output();
        $storagePath = 'statements/'.Auth::id().'/'.$pdf_download_name;
        Storage::disk('local')->put($storagePath, $pdfContent);

        // Email the statement to the user
        $periodLabel = $request->from_date && $request->to_date
            ? $request->from_date.' to '.$request->to_date
            : 'All Time';
        try {
            Auth::user()->notify(new StatementNotification(
                storage_path('app/'.$storagePath),
                $periodLabel
            ));
        } catch (\Exception $e) {
            // Log but don't fail the download
            \Log::warning('Failed to email statement: '.$e->getMessage());
        }

        return $pdf->download($pdf_download_name);
    }
}
