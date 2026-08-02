<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('Statement') }} | {{ $basic_settings->site_name }}</title>
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
    .page { padding: 42px 46px 36px; }

    /* ── Header ── */
    .doc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 3px solid #0B2A5B;
        padding-bottom: 16px;
    }
    .brand { display: flex; align-items: center; gap: 12px; }
    .brand img { height: 42px; }
    .brand-name { font-size: 20px; font-weight: 800; color: #0B2A5B; letter-spacing: 0.3px; }
    .brand-tag { font-size: 10px; color: #6B7280; letter-spacing: 1.5px; text-transform: uppercase; margin-top: 2px; }
    .doc-title { text-align: right; }
    .doc-title h1 { margin: 0; font-size: 22px; font-weight: 800; color: #0B2A5B; letter-spacing: 1px; text-transform: uppercase; }
    .doc-title .doc-sub { font-size: 11px; color: #6B7280; margin-top: 3px; }

    /* ── Account meta ── */
    .meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0;
        margin-top: 22px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        overflow: hidden;
    }
    .meta-cell {
        flex: 1 1 25%;
        min-width: 160px;
        padding: 14px 18px;
        border-right: 1px solid #E5E7EB;
    }
    .meta-cell:last-child { border-right: none; }
    .meta-label { font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #9CA3AF; }
    .meta-value { font-size: 13px; font-weight: 700; color: #111827; margin-top: 4px; }

    /* ── Summary band ── */
    .summary {
        display: flex;
        flex-wrap: wrap;
        margin-top: 16px;
        border-radius: 8px;
        overflow: hidden;
        background: #F4F6FB;
        border: 1px solid #E5E7EB;
    }
    .sum-cell { flex: 1 1 25%; min-width: 150px; padding: 14px 18px; }
    .sum-cell + .sum-cell { border-left: 1px solid #E5E7EB; }
    .sum-label { font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #6B7280; }
    .sum-value { font-size: 15px; font-weight: 800; margin-top: 4px; }
    .sum-value.credit { color: #1D4ED8; }
    .sum-value.debit { color: #B91C1C; }
    .sum-value.balance { color: #0B2A5B; }

    /* ── Ledger table ── */
    .ledger-title { font-size: 11px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: #0B2A5B; margin: 26px 0 10px; }
    table.ledger { width: 100%; border-collapse: collapse; font-size: 11px; }
    table.ledger thead th {
        background: #0B2A5B;
        color: #FFFFFF;
        text-align: left;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 9px 12px;
    }
    table.ledger thead th.num { text-align: right; }
    table.ledger tbody td { padding: 9px 12px; border-bottom: 1px solid #EEF1F5; vertical-align: top; }
    table.ledger tbody tr:nth-child(even) { background: #FAFBFD; }
    .tx-desc { font-weight: 700; color: #111827; }
    .tx-id { color: #9CA3AF; font-size: 9px; margin-top: 2px; }
    .num { text-align: right; white-space: nowrap; font-variant-numeric: tabular-nums; }
    .credit { color: #1D4ED8; font-weight: 700; }
    .debit { color: #B91C1C; font-weight: 700; }
    .muted { color: #C7CDD6; }
    .balance { font-weight: 700; color: #111827; }
    .status {
        display: inline-block;
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 2px 7px;
        border-radius: 100px;
    }
    .status.success { background: #DBEAFE; color: #1D4ED8; }
    .status.pending { background: #FEF3C7; color: #92400E; }
    .status.hold { background: #DBEAFE; color: #1D4ED8; }
    .status.rejected { background: #FEE2E2; color: #B91C1C; }

    /* ── Footer ── */
    .doc-footer {
        margin-top: 60px;
        padding-top: 28px;
        border-top: 3px solid #0B2A5B;
        position: relative;
    }

    /* Verification bar */
    .verify-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        background: #F0F4FA;
        border: 1px solid #D1D9E8;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 10px;
    }
    .verify-bar .verify-item { display: flex; align-items: center; gap: 6px; }
    .verify-bar .verify-label { color: #6B7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .verify-bar .verify-value { color: #111827; font-weight: 700; font-family: monospace; }
    .verify-bar .verify-qr { width: 56px; height: 56px; background: #fff; border: 1px solid #E5E7EB; border-radius: 6px; display: flex; align-items: center; justify-content: center; }

    /* Signatory section */
    .signatory-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 28px;
    }
    .signatory-block { padding: 20px; background: #FAFBFD; border: 1px solid #E5E7EB; border-radius: 10px; }
    .signatory-header { font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #6B7280; margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
    .signatory-header::before { content: ""; width: 8px; height: 8px; background: #0B2A5B; border-radius: 50%; }
    .sign-line-wrap { position: relative; min-height: 60px; }
    .sign-line { width: 100%; max-width: 320px; border-top: 1.5px solid #1F2937; margin-bottom: 10px; }
    .sign-name { font-size: 14px; font-weight: 700; color: #111827; }
    .sign-title { font-size: 11px; color: #6B7280; margin-top: 2px; }
    .sign-dept { font-size: 10px; color: #9CA3AF; margin-top: 2px; }
    .sign-date { font-size: 10px; color: #9CA3AF; margin-top: 10px; font-family: monospace; }

    /* Bank stamp/seal */
    .bank-seal-block { text-align: right; }
    .bank-seal {
        width: 120px;
        height: 120px;
        margin: 0 auto 10px;
        border: 3px solid #0B2A5B;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #FAFBFD 0%, #F0F4FA 100%);
        position: relative;
        box-shadow: 0 4px 12px rgba(11,42,91,0.1);
    }
    .bank-seal::before {
        content: "";
        position: absolute;
        inset: 6px;
        border: 1px dashed #0B2A5B;
        border-radius: 50%;
    }
    .seal-inner {
        position: relative;
        z-index: 1;
        text-align: center;
    }
    .seal-bank-name { font-size: 11px; font-weight: 800; color: #0B2A5B; letter-spacing: 0.3px; line-height: 1.2; }
    .seal-divider { width: 40px; height: 1px; background: #0B2A5B; margin: 6px auto; }
    .seal-text { font-size: 8px; font-weight: 700; color: #0B2A5B; text-transform: uppercase; letter-spacing: 1px; line-height: 1.3; }
    .seal-reg { font-size: 7px; color: #6B7280; margin-top: 4px; line-height: 1.2; }
    .seal-date { font-size: 9px; color: #6B7280; margin-top: 8px; font-family: monospace; }

    /* Legal / disclaimer */
    .legal-block {
        margin-top: 24px;
        padding: 16px;
        background: #F9FAFC;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 8.5px;
        color: #6B7280;
        line-height: 1.7;
    }
    .legal-block p { margin: 0 0 8px; }
    .legal-block p:last-child { margin-bottom: 0; }
    .legal-block strong { color: #374151; }
    .legal-block a { color: #1D4ED8; text-decoration: none; }

    /* Page footer with page numbers */
    .page-footer {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 12px;
        border-top: 1px solid #E5E7EB;
        font-size: 8px;
        color: #9CA3AF;
    }
    .page-footer .page-info { font-family: monospace; }

    .empty-note { text-align: center; color: #9CA3AF; padding: 30px 0; font-style: italic; }

    @media print {
        .verify-bar { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .signatory-block { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .bank-seal { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
</head>
<body>
<div class="page">
    @php
        $user = Auth::user();
        $periodFrom = $date['from_date'] ?? null;
        $periodTo = $date['to_date'] ?? null;

        $creditTypes = [
            'ADD-MONEY', 'BONUS', 'COMMISSION', 'CAPITAL-RETURN',
            'TRANSFER-MONEY', 'Salary Disbursement', 'Salary-Disbursement',
        ];
        $transferTypes = [
            \App\Constants\PaymentGatewayConst::TYPE_OTHER_BANK_TRANSFER,
            \App\Constants\PaymentGatewayConst::TYPE_OWN_BANK_TRANSFER,
        ];
        function pdfStmtIsCredit($tx, $creditTypes, $transferTypes) {
            $type = $tx->type ?? '';
            if (in_array($type, $transferTypes)) {
                return ($tx->receiver_id ?? null) == Auth::id();
            }
            return in_array($type, $creditTypes);
        }
        function pdfStmtTypeLabel($type) {
            $map = [
                'ADD-MONEY' => 'Deposit', 'MONEY-OUT' => 'Withdrawal', 'WITHDRAW' => 'Withdrawal',
                'BONUS' => 'Referral Bonus', 'COMMISSION' => 'Commission',
                'OWN-BANK-TRANSFER' => 'Own Account Transfer', 'OTHER-BANK-TRANSFER' => 'Bank Transfer',
                'TRANSFER-MONEY' => 'Transfer', 'MONEY-EXCHANGE' => 'Currency Exchange',
                'ADD-SUBTRACT-BALANCE' => 'Balance Adjustment', 'MAKE-PAYMENT' => 'Payment',
                'CAPITAL-RETURN' => 'Capital Return', 'VIRTUAL-CARD' => 'Virtual Card',
                'MOBILE-WALLET-TRANSFER' => 'Mobile Wallet', 'Salary Disbursement' => 'Salary',
            ];
            return $map[$type] ?? ucwords(str_replace(['-', '_'], ' ', strtolower($type)));
        }
        function pdfStmtStatusClass($status) {
            return match((int) $status) {
                1 => 'success', 3 => 'hold', 4 => 'rejected',
                default => 'pending',
            };
        }

        $txs = $transactions->sortBy('created_at')->values();
        $totalCredit = 0; $totalDebit = 0;
        foreach ($txs as $t) {
            if (pdfStmtIsCredit($t, $creditTypes, $transferTypes)) {
                $totalCredit += (float) $t->request_amount;
            } else {
                $totalDebit += (float) $t->request_amount;
            }
        }
        $closing = $txs->count() ? (float) $txs->last()->available_balance : 0;
        $opening = $closing - ($totalCredit - $totalDebit);
        $default_symbol = get_default_currency_symbol();
        $stmtId = 'ST-' . strtoupper(substr(md5($user->id . now()), 0, 10));
    @endphp

    <!-- Header -->
    <div class="doc-header">
        <div class="brand">
            <div>
                <div class="brand-name">{{ $basic_settings->site_name }}</div>
                <div class="brand-tag">{{ __('International Banking') }}</div>
            </div>
        </div>
        <div class="doc-title">
            <h1>{{ __('Account Statement') }}</h1>
            <div class="doc-sub">{{ __('Statement Ref') }}: {{ $stmtId }}</div>
        </div>
    </div>

    <!-- Account meta -->
    <div class="meta">
        <div class="meta-cell">
            <div class="meta-label">{{ __('Account Holder') }}</div>
            <div class="meta-value">{{ $user->fullname }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">{{ __('Account Number') }}</div>
            <div class="meta-value">{{ $user->account_no }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">{{ __('Statement Period') }}</div>
            <div class="meta-value">{{ $periodFrom && $periodTo ? $periodFrom . ' → ' . $periodTo : __('All Time') }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">{{ __('Date Generated') }}</div>
            <div class="meta-value">{{ dateFormat('d M Y', now()) }}</div>
        </div>
    </div>

    <!-- Summary band -->
    <div class="summary">
        <div class="sum-cell">
            <div class="sum-label">{{ __('Opening Balance') }}</div>
            <div class="sum-value balance">{{ $default_symbol }}{{ get_amount($opening) }}</div>
        </div>
        <div class="sum-cell">
            <div class="sum-label">{{ __('Total Credits') }}</div>
            <div class="sum-value credit">+{{ $default_symbol }}{{ get_amount($totalCredit) }}</div>
        </div>
        <div class="sum-cell">
            <div class="sum-label">{{ __('Total Debits') }}</div>
            <div class="sum-value debit">-{{ $default_symbol }}{{ get_amount($totalDebit) }}</div>
        </div>
        <div class="sum-cell">
            <div class="sum-label">{{ __('Closing Balance') }}</div>
            <div class="sum-value balance">{{ $default_symbol }}{{ get_amount($closing) }}</div>
        </div>
    </div>

    <!-- Ledger -->
    <div class="ledger-title">{{ __('Transaction History') }}</div>
    <table class="ledger">
        <thead>
            <tr>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Transaction') }}</th>
                <th>{{ __('Status') }}</th>
                <th class="num">{{ __('Debit') }}</th>
                <th class="num">{{ __('Credit') }}</th>
                <th class="num">{{ __('Balance') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($txs as $item)
            @php
                $isCredit = pdfStmtIsCredit($item, $creditTypes, $transferTypes);
                $isSend = $item->userTrxType == \App\Constants\PaymentGatewayConst::SEND;
            @endphp
            <tr>
                <td>{{ $item->created_at->format('d M Y') }}<br>{{ $item->created_at->format('h:i A') }}</td>
                <td>
                    <div class="tx-desc">{{ pdfStmtTypeLabel($item->type) }}</div>
                    <div class="tx-id">{{ $item->trx_id }}</div>
                </td>
                <td><span class="status {{ pdfStmtStatusClass($item->status) }}">{{ __($item->string_status->value) }}</span></td>
                <td class="num">
                    @if(!$isCredit)
                        <span class="debit">{{ get_amount($isSend ? $item->total_payable : $item->request_amount, $item->request_currency) }}</span>
                    @else
                        <span class="muted">—</span>
                    @endif
                </td>
                <td class="num">
                    @if($isCredit)
                        <span class="credit">{{ get_amount($item->request_amount, $item->request_currency) }}</span>
                    @else
                        <span class="muted">—</span>
                    @endif
                </td>
                <td class="num balance">{{ get_amount($item->available_balance, $item->request_currency) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="empty-note">{{ __('No transactions recorded for this period.') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="doc-footer">
        <!-- Verification bar -->
        <div class="verify-bar">
            <div class="verify-item">
                <span class="verify-label">{{ __('Statement Ref') }}:</span>
                <span class="verify-value">{{ $stmtId }}</span>
            </div>
            <div class="verify-item">
                <span class="verify-label">{{ __('Generated') }}:</span>
                <span class="verify-value">{{ dateFormat('d M Y H:i', now()) }}</span>
            </div>
            <div class="verify-item">
                <span class="verify-label">{{ __('Page') }}:</span>
                <span class="verify-value">1 / 1</span>
            </div>
            <div class="verify-qr" title="{{ __('Scan to verify') }}">{{ __('QR') }}</div>
        </div>

        <!-- Signatory section -->
        <div class="signatory-section">
            <!-- Account Holder Signature -->
            <div class="signatory-block">
                <div class="signatory-header">{{ __('Account Holder Signature') }}</div>
                <div class="sign-line-wrap">
                    <div class="sign-line"></div>
                    <div class="sign-name">{{ $user->fullname }}</div>
                    <div class="sign-title">{{ __('Primary Account Holder') }}</div>
                    <div class="sign-dept">{{ __('Retail Banking Division') }}</div>
                    <div class="sign-date">{{ __('Date') }}: {{ dateFormat('d M Y', now()) }}</div>
                </div>
            </div>

            <!-- Authorized Bank Officer -->
            <div class="signatory-block">
                <div class="signatory-header">{{ __('Authorized Bank Officer') }}</div>
                <div class="sign-line-wrap">
                    <div class="sign-line"></div>
                    <div class="sign-name">{{ $basic_settings->site_name }} Operations</div>
                    <div class="sign-title">{{ __('Digital Banking Services') }}</div>
                    <div class="sign-dept">{{ __('Compliance & Operations') }}</div>
                    <div class="sign-date">{{ __('Date') }}: {{ dateFormat('d M Y', now()) }}</div>
                </div>
            </div>
        </div>

        <!-- Bank Seal -->
        <div class="bank-seal-block">
            <div class="bank-seal">
                <div class="seal-inner">
                    <div class="seal-bank-name">{{ $basic_settings->site_name }}</div>
                    <div class="seal-divider"></div>
                    <div class="seal-text">{{ __('OFFICIAL<br>BANK SEAL') }}</div>
                </div>
            </div>
            <div class="seal-reg">
                {{ __('Regulated by') }} {{ $basic_settings->site_name }}<br>
                {{ __('Member FDIC / FCA Authorized') }}
            </div>
            <div class="seal-date">{{ __('Sealed') }}: {{ dateFormat('d M Y H:i', now()) }}</div>
        </div>

        <!-- Legal disclaimer -->
        <div class="legal-block">
            <p><strong>{{ __('Important Notice:') }}</strong> {{ __('This statement is a computer-generated document and is valid without a physical signature. It reflects transactions processed up to the generation date and time.') }}</p>
            <p>{{ __('For verification of authenticity, scan the QR code above or visit') }} <a href="{{ url('/') }}">{{ $basic_settings->site_name }}</a> {{ __('and use the statement reference') }} <strong>{{ $stmtId }}</strong>.</p>
            <p>{{ __('Any discrepancies must be reported within 30 days of the statement date in accordance with applicable banking regulations.') }}</p>
        </div>

        <!-- Page footer -->
        <div class="page-footer">
            <span>{{ __('Confidential - For intended recipient only') }}</span>
            <span class="page-info">{{ __('Page 1 of 1') }}</span>
            <span>{{ $basic_settings->site_name }} &copy; {{ date('Y') }}</span>
        </div>
    </div>
</div>
</body>
</html>
