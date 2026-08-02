<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('Transaction Receipt') }} | {{ $basic_settings->site_name }}</title>
<style>
    * { box-sizing: border-box; }
    body {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        color: #1F2937;
        margin: 0;
        padding: 0;
        font-size: 12px;
        line-height: 1.5;
    }
    .page {
        padding: 0;
        position: relative;
    }

    /* ── Top color band ── */
    .top-band {
        height: 10px;
        background: linear-gradient(90deg, #0B2A5B 0%, #1D4ED8 50%, #0B2A5B 100%);
    }

    /* ── Watermark ── */
    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -260px 0 0 -260px;
        width: 520px;
        height: 520px;
        border: 6px solid rgba(11, 42, 91, 0.06);
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        z-index: 0;
        pointer-events: none;
    }
    .watermark::before {
        content: "";
        position: absolute;
        inset: 12px;
        border: 2px solid rgba(11, 42, 91, 0.04);
        border-radius: 50%;
    }
    .watermark-name {
        font-size: 34px;
        font-weight: 900;
        color: rgba(11, 42, 91, 0.05);
        letter-spacing: 2px;
        text-transform: uppercase;
    }
    .watermark-sub {
        font-size: 13px;
        color: rgba(11, 42, 91, 0.04);
        letter-spacing: 4px;
        text-transform: uppercase;
        margin-top: 6px;
    }

    .content { position: relative; z-index: 1; padding: 26px 44px 20px; }

    /* ── Header ── */
    .doc-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding-bottom: 14px;
        border-bottom: 2px solid #0B2A5B;
        position: relative;
    }
    .doc-header::after {
        content: "";
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 90px;
        height: 2px;
        background: #F59E0B;
    }
    .brand { display: flex; align-items: center; gap: 14px; }
    .brand-logo {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0B2A5B, #1D4ED8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }
    .brand-name { font-size: 22px; font-weight: 800; color: #0B2A5B; letter-spacing: 0.3px; }
    .brand-tag { font-size: 9px; color: #6B7280; letter-spacing: 2px; text-transform: uppercase; margin-top: 3px; }
    .brand-reg { font-size: 8px; color: #9CA3AF; margin-top: 3px; letter-spacing: 0.5px; }
    .doc-title { text-align: right; }
    .doc-title h1 {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        color: #0B2A5B;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .doc-title .doc-sub {
        font-size: 10px;
        color: #6B7280;
        margin-top: 4px;
        letter-spacing: 0.5px;
    }
    .doc-title .receipt-no {
        display: inline-block;
        margin-top: 8px;
        padding: 4px 14px;
        background: #F0F4FA;
        border: 1px solid #D1D9E8;
        border-radius: 100px;
        font-size: 10px;
        font-weight: 700;
        color: #0B2A5B;
        font-family: monospace;
        letter-spacing: 0.5px;
    }

    /* ── Status ribbon ── */
    .status-ribbon {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 12px;
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid;
        font-size: 11px;
    }
    .status-ribbon.success { background: #EFF6FF; border-color: #93C5FD; color: #1D4ED8; }
    .status-ribbon.pending { background: #FFFBEB; border-color: #FDE68A; color: #92400E; }
    .status-ribbon.hold { background: #EFF6FF; border-color: #BFDBFE; color: #1D4ED8; }
    .status-ribbon.rejected { background: #FEF2F2; border-color: #FECACA; color: #B91C1C; }
    .ribbon-left { display: flex; align-items: center; gap: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; }
    .ribbon-dot { width: 8px; height: 8px; border-radius: 50%; background: currentColor; }
    .ribbon-right { font-weight: 600; }

    /* ── Party blocks (Sender / Recipient) ── */
    .parties {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-top: 14px;
    }
    .party-block {
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        padding: 12px 14px;
        background: #FAFBFD;
    }
    .party-label {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #6B7280;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .party-label .icon {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }
    .party-label .icon.out { background: #FEE2E2; color: #B91C1C; }
    .party-label .icon.in { background: #DBEAFE; color: #1D4ED8; }
    .party-row { display: flex; justify-content: space-between; gap: 12px; padding: 3px 0; font-size: 11px; }
    .party-row .k { color: #9CA3AF; flex-shrink: 0; }
    .party-row .v { font-weight: 600; color: #111827; text-align: right; word-break: break-all; }
    .party-row .v.mono { font-family: monospace; font-size: 10px; }

    /* ── Amount summary ── */
    .amount-box {
        margin-top: 14px;
        border: 1.5px solid #0B2A5B;
        border-radius: 12px;
        overflow: hidden;
    }
    .amount-box-head {
        background: #0B2A5B;
        color: #fff;
        padding: 10px 18px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .amount-box-body {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        background: #F8FAFC;
    }
    .amt-cell { padding: 10px 14px; text-align: center; border-right: 1px solid #E5E7EB; }
    .amt-cell:last-child { border-right: none; }
    .amt-label { font-size: 8px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #6B7280; }
    .amt-value { font-size: 17px; font-weight: 800; margin-top: 4px; }
    .amt-value.credit { color: #1D4ED8; }
    .amt-value.debit { color: #B91C1C; }
    .amt-value.balance { color: #0B2A5B; }
    .amt-value.currency { font-size: 10px; font-weight: 600; color: #6B7280; }

    /* ── Details table ── */
    .details-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: #0B2A5B;
        margin: 16px 0 8px;
    }
    table.details { width: 100%; border-collapse: collapse; font-size: 11px; }
    table.details thead th {
        background: #0B2A5B;
        color: #FFFFFF;
        text-align: left;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 7px 10px;
    }
    table.details thead th.num { text-align: right; }
    table.details tbody td { padding: 7px 10px; border-bottom: 1px solid #EEF1F5; vertical-align: top; }
    table.details tbody tr:nth-child(even) { background: #FAFBFD; }
    .detail-key { font-weight: 700; color: #111827; white-space: nowrap; width: 30%; }
    .detail-value { color: #374151; word-break: break-word; }
    .detail-value.highlight { color: #1D4ED8; font-family: monospace; }
    .detail-value.num { text-align: right; font-weight: 700; font-variant-numeric: tabular-nums; }

    /* ── Signature / stamp section ── */
    .doc-footer {
        margin-top: 24px;
        padding-top: 16px;
        border-top: 2px solid #E5E7EB;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 24px;
        align-items: flex-start;
    }
    .sign-block { text-align: left; }
    .sign-label {
        font-size: 9px;
        color: #6B7280;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 10px;
        font-weight: 700;
    }
    .sign-line-wrap { position: relative; }
    .sign-line { width: 100%; max-width: 230px; border-top: 1.5px solid #374151; }
    .sign-name { font-size: 12px; font-weight: 700; color: #111827; margin-top: 6px; }
    .sign-title { font-size: 10px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }
    .sign-date { font-size: 9px; color: #9CA3AF; margin-top: 6px; font-family: monospace; }

    .stamp-block { text-align: center; }
    .bank-seal {
        width: 92px;
        height: 92px;
        margin: 0 auto;
        border: 3px solid #0B2A5B;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #FAFBFD, #EDF2FA);
        position: relative;
    }
    .bank-seal::before {
        content: "";
        position: absolute;
        inset: 5px;
        border: 1px dashed #0B2A5B;
        border-radius: 50%;
    }
    .seal-inner { position: relative; z-index: 1; text-align: center; }
    .seal-bank-name { font-size: 10px; font-weight: 800; color: #0B2A5B; line-height: 1.2; letter-spacing: 0.3px; }
    .seal-divider { width: 36px; height: 1px; background: #0B2A5B; margin: 5px auto; }
    .seal-text { font-size: 7px; font-weight: 700; color: #0B2A5B; text-transform: uppercase; letter-spacing: 1px; line-height: 1.3; }
    .stamp-date { font-size: 8px; color: #6B7280; margin-top: 8px; font-family: monospace; }

    .verify-block { text-align: right; }
    .verify-label { font-size: 9px; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px; font-weight: 700; }
    .verify-box {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 12px 16px;
        background: #F8FAFC;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 9px;
        color: #6B7280;
    }
    .verify-box .ref { font-family: monospace; font-weight: 700; color: #0B2A5B; letter-spacing: 0.5px; }
    .verify-qr {
        width: 46px;
        height: 46px;
        background: #fff;
        border: 1.5px solid #111;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        color: #111;
        letter-spacing: 0.5px;
    }

    /* ── Legal / disclaimer ── */
    .legal-block {
        margin-top: 14px;
        padding: 10px 14px;
        background: #F9FAFC;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 8px;
        color: #6B7280;
        line-height: 1.6;
    }
    .legal-block p { margin: 0 0 6px; }
    .legal-block p:last-child { margin-bottom: 0; }
    .legal-block strong { color: #374151; }

    /* ── Page footer ── */
    .page-footer {
        margin-top: 14px;
        padding-top: 10px;
        border-top: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 8px;
        color: #9CA3AF;
    }
    .page-footer .page-info { font-family: monospace; letter-spacing: 0.5px; }

    /* ── Responsive ── */
    @media only screen and (max-width: 600px) {
        .content { padding: 24px 18px 20px; }
        .parties { grid-template-columns: 1fr; }
        .doc-footer { grid-template-columns: 1fr; gap: 20px; }
        .stamp-block { text-align: left; }
        .bank-seal { margin: 0; }
        .verify-block { text-align: left; }
    }

    @media print {
        .top-band, .bank-seal, .amount-box-head, table.details thead th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .status-ribbon { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
</head>
<body>
<div class="page">
    <div class="top-band"></div>
    <div class="watermark">
        <div class="watermark-name">{{ $basic_settings->site_name }}</div>
        <div class="watermark-sub">{{ __('International Banking') }}</div>
    </div>

    <div class="content">
    @php
        $user = Auth::user();
        $details = $transaction->details;
        if (is_string($details)) { $details = json_decode($details); }
        $isCredit = in_array($transaction->type, ['ADD-MONEY','BONUS','COMMISSION','CAPITAL-RETURN','TRANSFER-MONEY','Salary Disbursement','Salary-Disbursement'])
            && (!in_array($transaction->type, ['TRANSFER-MONEY','OWN-BANK-TRANSFER','OTHER-BANK-TRANSFER']) || ($transaction->receiver_id ?? null) == Auth::id());
        $txLabel = match($transaction->type) {
            'ADD-MONEY' => 'Deposit', 'MONEY-OUT' => 'Withdrawal', 'WITHDRAW' => 'Withdrawal',
            'BONUS' => 'Referral Bonus', 'COMMISSION' => 'Commission',
            'OWN-BANK-TRANSFER' => 'Own Account Transfer', 'OTHER-BANK-TRANSFER' => 'Bank Transfer',
            'TRANSFER-MONEY' => 'Transfer', 'MONEY-EXCHANGE' => 'Currency Exchange',
            'ADD-SUBTRACT-BALANCE' => 'Balance Adjustment', 'MAKE-PAYMENT' => 'Payment',
            'CAPITAL-RETURN' => 'Capital Return', 'VIRTUAL-CARD' => 'Virtual Card',
            'MOBILE-WALLET-TRANSFER' => 'Mobile Wallet', 'Salary Disbursement' => 'Salary',
            default => ucwords(str_replace(['-', '_'], ' ', strtolower($transaction->type))),
        };
        $statusClass = match((int) $transaction->status) { 1 => 'success', 3 => 'hold', 4 => 'rejected', default => 'pending' };
        $statusText = match((int) $transaction->status) { 1 => 'Completed', 2 => 'Pending', 3 => 'On Hold', 4 => 'Rejected', 5 => 'Waiting', default => 'Unknown' };
        $stmtId = 'RX-' . strtoupper(substr(md5($transaction->trx_id . $transaction->id), 0, 10));

        // Party / counterparty details
        $recipientName = $details->receiver_name ?? $details->sender_name ?? null;
        $recipientAccount = $details->receiver_account ?? $details->sender_account ?? null;
        $bankName = $details->bank_name ?? $details->receiver_bank ?? $details->sender_bank ?? 'EnzoBank';
        $swift = $details->swift_bic ?? 'ENZOUS33';
        $description = $details->description ?? null;
        $currency = $transaction->request_currency ?? 'USD';
        $symbol = get_default_currency_symbol();
    @endphp

    <!-- Header -->
    <div class="doc-header">
        <div class="brand">
            <div class="brand-logo">{{ substr($basic_settings->site_name, 0, 1) }}</div>
            <div>
                <div class="brand-name">{{ $basic_settings->site_name }}</div>
                <div class="brand-tag">{{ __('International Banking') }}</div>
                <div class="brand-reg">{{ __('Member FDIC | SWIFT: ENZOUS33') }}</div>
            </div>
        </div>
        <div class="doc-title">
            <h1>{{ __('Transaction Receipt') }}</h1>
            <div class="doc-sub">{{ __('Official Bank Document') }}</div>
            <span class="receipt-no">{{ __('Receipt') }}: {{ $stmtId }}</span>
        </div>
    </div>

    <!-- Status ribbon -->
    <div class="status-ribbon {{ $statusClass }}">
        <div class="ribbon-left">
            <span class="ribbon-dot"></span>
            {{ __($statusText) }}
        </div>
        <div class="ribbon-right">
            {{ $txLabel }} &bull; {{ $transaction->created_at ? $transaction->created_at->format('d M Y, h:i A') : '' }}
        </div>
    </div>

    <!-- Parties -->
    <div class="parties">
        <div class="party-block">
            <div class="party-label">
                <span class="icon in">&#10003;</span>
                {{ __('Transaction Details') }}
            </div>
            <div class="party-row"><span class="k">{{ __('Reference No') }}</span><span class="v mono">{{ $transaction->trx_id }}</span></div>
            <div class="party-row"><span class="k">{{ __('Account Holder') }}</span><span class="v">{{ $user->fullname }}</span></div>
            <div class="party-row"><span class="k">{{ __('Account Number') }}</span><span class="v mono">{{ $user->account_no }}</span></div>
            <div class="party-row"><span class="k">{{ __('Bank') }}</span><span class="v">{{ $bankName }}</span></div>
            @if($description)
            <div class="party-row"><span class="k">{{ __('Description') }}</span><span class="v">{{ $description }}</span></div>
            @endif
        </div>
        <div class="party-block">
            <div class="party-label">
                <span class="icon {{ $isCredit ? 'in' : 'out' }}">{{ $isCredit ? '↓' : '↑' }}</span>
                {{ $isCredit ? __('Sender / Source') : __('Recipient / Destination') }}
            </div>
            @if($recipientName)
            <div class="party-row"><span class="k">{{ __('Name') }}</span><span class="v">{{ $recipientName }}</span></div>
            @endif
            @if($recipientAccount)
            <div class="party-row"><span class="k">{{ __('Account') }}</span><span class="v mono">{{ $recipientAccount }}</span></div>
            @endif
            @if($bankName && $bankName !== 'EnzoBank')
            <div class="party-row"><span class="k">{{ __('Bank') }}</span><span class="v">{{ $bankName }}</span></div>
            @endif
            @if(isset($details->swift_bic))
            <div class="party-row"><span class="k">{{ __('SWIFT / BIC') }}</span><span class="v mono">{{ $details->swift_bic }}</span></div>
            @endif
            @if($isCredit && !$recipientName)
            <div class="party-row"><span class="k">{{ __('Type') }}</span><span class="v">{{ $txLabel }}</span></div>
            @endif
            @if(!$recipientName && !$recipientAccount && !$isCredit)
            <div class="party-row"><span class="k">{{ __('Destination') }}</span><span class="v">{{ $txLabel }}</span></div>
            @endif
        </div>
    </div>

    <!-- Amount summary -->
    <div class="amount-box">
        <div class="amount-box-head">
            <span>{{ __('Payment Summary') }}</span>
            <span>{{ $currency }}</span>
        </div>
        <div class="amount-box-body">
            <div class="amt-cell">
                <div class="amt-label">{{ __('Amount') }}</div>
                <div class="amt-value {{ $isCredit ? 'credit' : 'debit' }}">{{ $symbol }}{{ number_format($transaction->request_amount, 2) }}</div>
            </div>
            <div class="amt-cell">
                <div class="amt-label">{{ __('Fee') }}</div>
                <div class="amt-value debit">{{ $symbol }}{{ number_format($transaction->total_charge ?? 0, 2) }}</div>
            </div>
            <div class="amt-cell">
                <div class="amt-label">{{ __('Total') }}</div>
                <div class="amt-value balance">{{ $symbol }}{{ number_format($transaction->total_payable ?? $transaction->request_amount, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Details table -->
    <div class="details-title">{{ __('Detailed Breakdown') }}</div>
    <table class="details">
        <thead>
            <tr>
                <th>{{ __('Description') }}</th>
                <th class="num">{{ __('Debit') }}</th>
                <th class="num">{{ __('Credit') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="detail-key">{{ $txLabel }}</td>
                <td class="detail-value num">{{ $isCredit ? '—' : $symbol . number_format($transaction->total_payable ?? $transaction->request_amount, 2) }}</td>
                <td class="detail-value num credit">{{ $isCredit ? $symbol . number_format($transaction->request_amount, 2) : '—' }}</td>
            </tr>
            <tr>
                <td class="detail-key">{{ __('Processing Fee') }}</td>
                <td class="detail-value num">{{ $symbol }}{{ number_format($transaction->total_charge ?? 0, 2) }}</td>
                <td class="detail-value num">&mdash;</td>
            </tr>
            <tr>
                <td class="detail-key">{{ __('Balance After Transaction') }}</td>
                <td class="detail-value num"></td>
                <td class="detail-value num highlight">{{ $symbol }}{{ number_format($transaction->available_balance ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer: signatures + seal + verification -->
    <div class="doc-footer">
        <div class="sign-block">
            <div class="sign-label">{{ __('Authorized Signatory') }}</div>
            <div class="sign-line-wrap">
                <div class="sign-line"></div>
                <div class="sign-name">{{ $basic_settings->site_name }} Operations</div>
                <div class="sign-title">{{ __('Digital Banking Services') }}</div>
                <div class="sign-date">{{ __('Signed') }}: {{ dateFormat('d M Y', now()) }}</div>
            </div>
        </div>

        <div class="stamp-block">
            <div class="bank-seal">
                <div class="seal-inner">
                    <div class="seal-bank-name">{{ $basic_settings->site_name }}</div>
                    <div class="seal-divider"></div>
                    <div class="seal-text">{{ __('OFFICIAL') }}<br>{{ __('BANK SEAL') }}</div>
                </div>
            </div>
            <div class="stamp-date">{{ __('Issued') }}: {{ dateFormat('d M Y H:i', now()) }}</div>
        </div>

        <div class="verify-block">
            <div class="verify-label">{{ __('Document Verification') }}</div>
            <div class="verify-box">
                <div class="verify-qr">QR</div>
                <span>{{ __('Scan to verify') }}</span>
                <span class="ref">{{ $stmtId }}</span>
            </div>
        </div>
    </div>

    <!-- Legal -->
    <div class="legal-block">
        <p><strong>{{ __('Important Notice:') }}</strong> {{ __('This is a computer-generated official receipt issued by') }} {{ $basic_settings->site_name }} {{ __('and is valid without a physical signature.') }} {{ __('For verification, visit') }} <a href="{{ url('/') }}" style="color:#1D4ED8;">{{ $basic_settings->site_name }}</a> {{ __('or contact our support team with the receipt reference above.') }}</p>
        <p>{{ __('Any unauthorized use or alteration of this document is prohibited and subject to legal action.') }}</p>
    </div>

    <!-- Page footer -->
    <div class="page-footer">
        <span>{{ __('Confidential') }} &bull; {{ __('For intended recipient only') }}</span>
        <span class="page-info">{{ $basic_settings->site_name }} &bull; {{ __('Page 1 of 1') }} &bull; {{ date('Y') }}</span>
    </div>
    </div>
</div>
</body>
</html>