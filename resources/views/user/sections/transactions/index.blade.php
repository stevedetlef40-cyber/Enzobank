@extends("user.layouts.rise-master")

@push("css")
<style>
/* ── Type mapping ── */
.tx-type-ADD-MONEY, .tx-type-Salary-Disbursement, .tx-type-COMMISSION { --tx-color: #3B82F6; --tx-bg: rgba(59,130,246,0.12); }
.tx-type-BONUS { --tx-color: #A855F7; --tx-bg: rgba(168,85,247,0.12); }
.tx-type-MONEY-OUT, .tx-type-WITHDRAW { --tx-color: #EF4444; --tx-bg: rgba(239,68,68,0.12); }
.tx-type-OWN-BANK-TRANSFER { --tx-color: #3B82F6; --tx-bg: rgba(59,130,246,0.12); }
.tx-type-OTHER-BANK-TRANSFER { --tx-color: #F59E0B; --tx-bg: rgba(245,158,11,0.12); }
.tx-type-TRANSFER-MONEY { --tx-color: #2563EB; --tx-bg: rgba(37,99,235,0.12); }
.tx-type-VIRTUAL-CARD { --tx-color: #EC4899; --tx-bg: rgba(236,72,153,0.12); }
.tx-type-default { --tx-color: #94A3B8; --tx-bg: rgba(148,163,184,0.08); }

/* ── Animations ── */
@keyframes tlFadeUp {
    0% { opacity: 0; transform: translateY(24px) scale(0.97); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}

/* ── Header ── */
.tl-header { display: flex; flex-direction: column; gap: 12px; padding: 18px 16px 8px; }
.tl-header-row { display: flex; align-items: center; justify-content: space-between; }
.tl-header-title { font-size: 22px; font-weight: 700; color: #fff; letter-spacing: -0.3px; }
.tl-header-count { font-size: 12px; color: #64748B; background: #1E293B; padding: 3px 12px; border-radius: 100px; }

/* ── Search ── */
.tl-search-wrap { position: relative; width: 100%; }
.tl-search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #64748B; display: flex; }
.tl-search-wrap input {
    width: 100%; height: 42px; padding: 0 14px 0 40px; border: 1.5px solid #1E293B;
    border-radius: 12px; background: #111827; color: #F1F5F9; font-size: 14px;
    outline: none; transition: border-color 0.2s;
}
.tl-search-wrap input:focus { border-color: #3B82F6; }
.tl-search-wrap input::placeholder { color: #475569; }

/* ── Stats Row ── */
.tl-stats { display: flex; gap: 8px; padding: 12px 16px 4px; overflow-x: auto; }
.tl-stats::-webkit-scrollbar { display: none; }
.tl-stat-card {
    flex-shrink: 0; background: #111827; border: 1px solid #1E293B; border-radius: 12px;
    padding: 12px 16px; min-width: 110px; display: flex; flex-direction: column; gap: 2px;
}
.tl-stat-label { font-size: 11px; font-weight: 500; color: #64748B; text-transform: uppercase; letter-spacing: 0.8px; }
.tl-stat-value { font-size: 16px; font-weight: 700; color: #fff; }
.tl-stat-value.green { color: #3B82F6; }
.tl-stat-value.red { color: #EF4444; }
.tl-stat-value.purple { color: #A855F7; }

/* ── Filter Pills ── */
.tl-filter-scroll {
    display: flex; gap: 8px; padding: 12px 16px 8px; overflow-x: auto; scrollbar-width: none;
}
.tl-filter-scroll::-webkit-scrollbar { display: none; }
.tl-filter {
    flex-shrink: 0; padding: 7px 18px; border-radius: 100px; border: 1.5px solid #1E293B;
    background: transparent; color: #94A3B8; font-size: 13px; font-weight: 500;
    cursor: pointer; transition: all 0.2s; white-space: nowrap;
}
.tl-filter:hover { border-color: #334155; color: #E2E8F0; }
.tl-filter.active { background: #3B82F6; border-color: #3B82F6; color: #fff; }

/* ── Body ── */
.tl-body { padding: 0 16px 120px; }

/* Date Group */
.tl-date-group { padding: 16px 0 6px; }
.tl-date-label { font-size: 13px; font-weight: 600; color: #64748B; letter-spacing: 0.5px; }

/* ── Transaction Card ── */
.tl-item {
    background: #111827; border: 1.5px solid #1E293B; border-radius: 14px;
    padding: 14px; margin-bottom: 8px; cursor: pointer;
    transition: all 0.2s; opacity: 0;
    animation: tlFadeUp 0.45s ease-out forwards;
    position: relative; overflow: hidden;
}
.tl-item:active { transform: scale(0.985); }
.tl-item-main { display: flex; align-items: center; gap: 12px; }

/* Icon */
.tl-item-icon {
    width: 42px; height: 42px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    background: var(--tx-bg, rgba(148,163,184,0.08));
    color: var(--tx-color, #94A3B8);
}

/* Info */
.tl-item-info { flex: 1; min-width: 0; }
.tl-item-type-row { display: flex; align-items: center; gap: 8px; }
.tl-item-name { font-size: 14px; font-weight: 600; color: #F1F5F9; }
.tl-item-desc { font-size: 12px; color: #64748B; margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; }
.tl-item-date { font-size: 11px; color: #475569; margin-top: 2px; }

/* Status */
.tl-status {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 100px;
    text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;
}
.tl-status-dot { width: 5px; height: 5px; border-radius: 50%; }
.tl-status.success { background: rgba(59,130,246,0.12); color: #3B82F6; }
.tl-status.success .tl-status-dot { background: #3B82F6; }
.tl-status.pending { background: rgba(245,158,11,0.12); color: #F59E0B; }
.tl-status.pending .tl-status-dot { background: #F59E0B; }
.tl-status.rejected { background: rgba(239,68,68,0.12); color: #EF4444; }
.tl-status.rejected .tl-status-dot { background: #EF4444; }

/* Amount */
.tl-item-amount { font-size: 15px; font-weight: 700; text-align: right; flex-shrink: 0; }
.tl-item-amount.positive { color: #3B82F6; }
.tl-item-amount.negative { color: #EF4444; }
.tl-item-balance { font-size: 10px; color: #475569; margin-top: 2px; text-align: right; font-weight: 500; }

/* ── Expanded Detail ── */
.tl-detail {
    max-height: 0; overflow: hidden; transition: max-height 0.35s ease, opacity 0.25s ease, margin 0.3s ease;
    opacity: 0; margin-top: 0;
}
.tl-item.expanded .tl-detail {
    max-height: 400px; opacity: 1; margin-top: 12px;
}
.tl-detail-inner {
    border-top: 1px solid #1E293B; padding-top: 12px;
    display: grid; grid-template-columns: 1fr 1fr; gap: 8px 16px;
    font-size: 12px;
}
.tl-detail-item { display: flex; flex-direction: column; gap: 1px; }
.tl-detail-label { color: #64748B; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
.tl-detail-value { color: #E2E8F0; font-weight: 500; word-break: break-all; }
.tl-detail-value.highlight { color: #3B82F6; }

/* ── Pagination ── */
.tl-pagination { display: flex; justify-content: center; padding: 20px 0 100px; }
.tl-pagination .pagination { margin: 0; }
.tl-pagination .page-link {
    width: 36px; height: 36px; border-radius: 10px;
    background: #111827; border: 1px solid #1E293B;
    color: #94A3B8; font-size: 13px; font-weight: 500;
    transition: all 0.15s; cursor: pointer; text-decoration: none;
    display: flex; align-items: center; justify-content: center;
}
.tl-pagination .page-link:hover { border-color: #3B82F6; color: #fff; }
.tl-pagination .page-item.active .page-link { background: #3B82F6; border-color: #3B82F6; color: #fff; }
.tl-pagination .page-item.disabled .page-link { opacity: 0.3; cursor: default; pointer-events: none; }

/* ── Empty State ── */
.tl-empty { display: flex; flex-direction: column; align-items: center; padding: 60px 20px; text-align: center; gap: 8px; }
.tl-empty-icon { color: #1E293B; margin-bottom: 8px; }
.tl-empty-title { font-size: 16px; font-weight: 700; color: #fff; }
.tl-empty-sub { font-size: 13px; color: #94A3B8; }
.tl-empty-btn { margin-top: 12px; padding: 12px 28px; background: #3B82F6; color: #fff; border-radius: 100px; font-weight: 600; font-size: 14px; display: inline-block; text-decoration: none; transition: all 0.2s; }

/* ── Chevron ── */
.tl-item-chevron { color: #475569; transition: transform 0.25s ease; flex-shrink: 0; margin-left: 4px; }
.tl-item.expanded .tl-item-chevron { transform: rotate(180deg); }

/* ── Highlight search match ── */
.tl-highlight { background: rgba(59,130,246,0.25); color: #93C5FD; padding: 0 2px; border-radius: 2px; }

/* ── Light mode ── */
[data-theme="light"] {
    .tl-header-title { color: #0F172A; }
    .tl-header-count { color: #64748B; background: #F1F5F9; }
    .tl-search-wrap input { border-color: #E2E8F0; background: #F8FAFC; color: #0F172A; }
    .tl-search-wrap input::placeholder { color: #94A3B8; }
    .tl-stat-card { background: #fff; border-color: #E2E8F0; }
    .tl-stat-label { color: #64748B; }
    .tl-stat-value { color: #0F172A; }
    .tl-filter { border-color: #E2E8F0; color: #475569; }
    .tl-filter:hover { border-color: #CBD5E1; color: #0F172A; }
    .tl-filter.active { background: #3B82F6; border-color: #3B82F6; color: #fff; }
    .tl-item { background: #fff; border-color: #E2E8F0; }
    .tl-item-name { color: #0F172A; }
    .tl-item-desc { color: #64748B; }
    .tl-item-date { color: #94A3B8; }
    .tl-detail-inner { border-top-color: #E2E8F0; }
    .tl-detail-value { color: #475569; }
    .tl-detail-value.highlight { color: #1D4ED8; }
    .tl-pagination .page-link { background: #fff; border-color: #E2E8F0; color: #475569; }
    .tl-pagination .page-link:hover { border-color: #3B82F6; color: #0F172A; }
    .tl-pagination .page-item.active .page-link { background: #3B82F6; border-color: #3B82F6; color: #fff; }
    .tl-empty-icon { color: #E2E8F0; }
    .tl-empty-title { color: #0F172A; }
    .tl-empty-sub { color: #64748B; }
    .tl-item-chevron { color: #94A3B8; }
}

@media (max-width: 400px) {
    .tl-item { padding: 12px; }
    .tl-item-icon { width: 36px; height: 36px; }
    .tl-item-name { font-size: 13px; }
    .tl-item-amount { font-size: 14px; }
    .tl-stat-card { min-width: 90px; padding: 10px 14px; }
}
</style>
@endpush

@section("content")
@php
$transactions = $transactions ?? collect([]);
$totalCount = $transactions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $transactions->total() : $transactions->count();

$typeLabels = [
    "ADD-MONEY" => "Deposit", "MONEY-OUT" => "Withdrawal", "WITHDRAW" => "Withdrawal",
    "BONUS" => "Referral Bonus", "COMMISSION" => "Commission",
    "OWN-BANK-TRANSFER" => "Own Transfer", "OTHER-BANK-TRANSFER" => "Bank Transfer",
    "TRANSFER-MONEY" => "Transfer", "MONEY-EXCHANGE" => "Currency Exchange",
    "ADD-SUBTRACT-BALANCE" => "Adjustment", "MAKE-PAYMENT" => "Payment",
    "CAPITAL-RETURN" => "Capital Return", "VIRTUAL-CARD" => "Virtual Card",
    "MOBILE-WALLET-TRANSFER" => "Mobile Wallet", "Salary Disbursement" => "Salary",
];
if (!function_exists("txLabel")) {
    function txLabel($type) { global $typeLabels; return $typeLabels[$type] ?? ucwords(str_replace(["-","_"], " ", strtolower($type))); }
}
if (!function_exists("txIsCredit")) {
    function txIsCredit($tx) {
        if (($tx->attribute ?? "") === "RECEIVED") return true;
        return in_array($tx->type ?? "", ["ADD-MONEY","BONUS","COMMISSION","CAPITAL-RETURN","TRANSFER-MONEY","Salary Disbursement"])
            && (!in_array($tx->type ?? "", ["TRANSFER-MONEY","OWN-BANK-TRANSFER","OTHER-BANK-TRANSFER"]) || ($tx->receiver_id ?? null) == auth()->id());
    }
}
if (!function_exists("txStatusClass")) {
    function txStatusClass($status) {
        return match((int)$status) { 1 => "success", 2 => "pending", 3 => "pending", 4 => "rejected", 5 => "pending", default => "pending" };
    }
}
if (!function_exists("txStatusText")) {
    function txStatusText($status) {
        return match((int)$status) { 1 => "Completed", 2 => "Pending", 3 => "On Hold", 4 => "Rejected", 5 => "Waiting", default => "Unknown" };
    }
}

$statsCredit = 0; $statsDebit = 0; $statsBonus = 0;
foreach ($transactions as $tx) {
    if (txIsCredit($tx)) { $statsCredit += $tx->request_amount; }
    else { $statsDebit += $tx->request_amount; }
    if ($tx->type === "BONUS") $statsBonus += $tx->request_amount;
}
$currentUserId = auth()->id();
@endphp

<div class="tl-header">
    <div class="tl-header-row">
        <h1 class="tl-header-title">Transaction History</h1>
        <div style="display:flex;gap:8px;align-items:center">
            <a href="{{ route('user.statements.index') }}" class="rw-section-link-pill" style="display:flex;align-items:center;gap:6px;padding:5px 14px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Statement
            </a>
            <span class="tl-header-count">{{ $totalCount }} entries</span>
        </div>
    </div>
    <div class="tl-search-wrap">
        <span class="tl-search-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </span>
        <input type="text" placeholder="Search transactions..." id="txSearch" autocomplete="off">
    </div>
</div>

@if($totalCount > 0)
<div class="tl-stats">
    <div class="tl-stat-card">
        <span class="tl-stat-label">Deposits</span>
        <span class="tl-stat-value green">+${{ number_format($statsCredit, 0) }}</span>
    </div>
    <div class="tl-stat-card">
        <span class="tl-stat-label">Withdrawals</span>
        <span class="tl-stat-value red">-${{ number_format($statsDebit, 0) }}</span>
    </div>
    @if($statsBonus > 0)
    <div class="tl-stat-card">
        <span class="tl-stat-label">Bonuses</span>
        <span class="tl-stat-value purple">+${{ number_format($statsBonus, 0) }}</span>
    </div>
    @endif
</div>
@endif

<div class="tl-body">
    <div class="tl-filter-scroll">
        <button class="tl-filter active" data-filter="all">All</button>
        <button class="tl-filter" data-filter="deposit">Deposits</button>
        <button class="tl-filter" data-filter="withdrawal">Withdrawals</button>
        <button class="tl-filter" data-filter="transfer">Transfers</button>
        <button class="tl-filter" data-filter="bonus">Bonuses</button>
    </div>

    <div class="tl-list" id="txList">
        @php $prevGroup = ""; @endphp
        @forelse($transactions as $tx)
        @php
            $isCredit = txIsCredit($tx);
            $isReceived = (($tx->attribute ?? "") === "RECEIVED") || (($tx->receiver_id ?? null) == auth()->user()->wallet?->id) && in_array($tx->type ?? "", ['OWN-BANK-TRANSFER','OTHER-BANK-TRANSFER','MOBILE-WALLET-TRANSFER']);
            $txClass = "tx-type-" . str_replace([" ", "_", "/"], "-", $tx->type ?? "default");
            $details = is_string($tx->details) ? json_decode($tx->details) : ($tx->details ?? null);
            $desc = $details->description ?? "";
            $bank = $details->bank_name ?? $details->receiver_bank ?? "";
            $senderName = $details->sender_name ?? "";
            $senderBank = $details->sender_bank ?? "";
            $receiverName = $details->receiver_name ?? "";
            $receiverBank = $details->receiver_bank ?? "";
            if ($tx->type === "MOBILE-WALLET-TRANSFER" && $details) {
                if ($isReceived) {
                    $label = 'From: ' . ($senderName ?: 'Someone');
                } else {
                    $label = 'To: ' . ($receiverName ?: 'Someone');
                }
            } else {
                $label = txLabel($tx->type);
            }
            $dateGroup = $tx->created_at ? $tx->created_at->format("F Y") : "";
        @endphp

        @if($dateGroup && $dateGroup !== $prevGroup)
        @php $prevGroup = $dateGroup; @endphp
        <div class="tl-date-group"><span class="tl-date-label">{{ $dateGroup }}</span></div>
        @endif

        <div class="tl-item {{ $txClass }}" data-type="{{ ($isCredit || $isReceived) ? "credit" : "debit" }}" style="animation-delay: {{ min($loop->index * 0.035, 1.5) }}s">
            <div class="tl-item-main" onclick="this.closest('.tl-item').classList.toggle('expanded')">
                <div class="tl-item-icon" style="border-radius: 12px;">
                    @if(in_array($tx->type ?? "", ["ADD-MONEY","Salary Disbursement"]))
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    @elseif(in_array($tx->type ?? "", ["MONEY-OUT","WITHDRAW","MAKE-PAYMENT"]))
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><line x1="19" y1="12" x2="5" y2="12"/></svg>
                    @elseif($tx->type === "BONUS")
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @elseif(in_array($tx->type ?? "", ["OWN-BANK-TRANSFER","OTHER-BANK-TRANSFER","MOBILE-WALLET-TRANSFER"]))
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                    @elseif($tx->type === "VIRTUAL-CARD")
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    @elseif($tx->type === "COMMISSION")
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="20" y1="12" x2="4" y2="12"/><polyline points="10 18 4 12 10 6"/></svg>
                    @else
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                    @endif
                </div>

                <div class="tl-item-info">
                    <div class="tl-item-type-row">
                        <span class="tl-item-name">{{ $label }}</span>
                        <span class="tl-status {{ txStatusClass($tx->status) }}">
                            <span class="tl-status-dot"></span>
                            {{ txStatusText($tx->status) }}
                        </span>
                    </div>
                    @if($desc && $desc !== $label)
                    <div class="tl-item-desc">{{ $desc }}</div>
                    @endif
                    <div class="tl-item-date">{{ $tx->created_at ? $tx->created_at->format("M d, Y  h:i A") : "" }}</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:1px;">#{{ $tx->trx_id }}</div>
                </div>

                <div style="display:flex;flex-direction:column;align-items:flex-end;">
                    <span class="tl-item-amount {{ ($isCredit || $isReceived) ? "positive" : "negative" }}">
                        {{ ($isCredit || $isReceived) ? "+" : "-" }}${{ number_format($tx->request_amount, 2) }}
                    </span>
                    @if($tx->available_balance !== null)
                    <span class="tl-item-balance">${{ number_format($tx->available_balance, 2) }}</span>
                    @endif
                </div>

                <svg class="tl-item-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </div>

            <div class="tl-detail">
                <div class="tl-detail-inner">
                    <div class="tl-detail-item">
                        <span class="tl-detail-label">Transaction ID</span>
                        <span class="tl-detail-value highlight">{{ $tx->trx_id }}</span>
                    </div>
                    <div class="tl-detail-item">
                        <span class="tl-detail-label">Type</span>
                        <span class="tl-detail-value">{{ $label }}</span>
                    </div>
                    <div class="tl-detail-item">
                        <span class="tl-detail-label">Amount</span>
                        <span class="tl-detail-value {{ ($isCredit || $isReceived) ? 'text--success' : '' }}">${{ number_format($tx->request_amount, 2) }} {{ $tx->request_currency ?? '' }}</span>
                    </div>
                    @if($isReceived && $tx->user)
                    <div class="tl-detail-item">
                        <span class="tl-detail-label">Sender</span>
                        <span class="tl-detail-value text--success">{{ $senderName ?: ($tx->user->fullname ?? 'N/A') }}</span>
                    </div>
                    <div class="tl-detail-item">
                        <span class="tl-detail-label">Sender Account</span>
                        <span class="tl-detail-value text--success">{{ $tx->user->account_no ?? 'N/A' }}</span>
                    </div>
                    @if($senderBank)
                    <div class="tl-detail-item">
                        <span class="tl-detail-label">From Bank</span>
                        <span class="tl-detail-value text--success">{{ $senderBank }}</span>
                    </div>
                    @endif
                    @endif
                    @if($tx->total_charge > 0)
                    <div class="tl-detail-item">
                        <span class="tl-detail-label">Fee</span>
                        <span class="tl-detail-value">${{ number_format($tx->total_charge, 2) }}</span>
                    </div>
                    @endif
                    @if($bank)
                    <div class="tl-detail-item">
                        <span class="tl-detail-label">Bank</span>
                        <span class="tl-detail-value">{{ $bank }}</span>
                    </div>
                    @endif
                    @if($desc)
                    <div class="tl-detail-item" style="grid-column: 1 / -1;">
                        <span class="tl-detail-label">Description</span>
                        <span class="tl-detail-value">{{ $desc }}</span>
                    </div>
                    @endif
                    <div class="tl-detail-item">
                        <span class="tl-detail-label">Balance After</span>
                        <span class="tl-detail-value highlight">${{ number_format($tx->available_balance, 2) }}</span>
                    </div>
                    <div class="tl-detail-item">
                        <span class="tl-detail-label">Date</span>
                        <span class="tl-detail-value">{{ $tx->created_at ? $tx->created_at->format("M d, Y  h:i A") : "" }}</span>
                    </div>
                    <div class="tl-detail-item" style="grid-column: 1 / -1; text-align: right; margin-top: 8px;">
                        <a href="{{ route('user.fund-transfer.pdf.download', $tx->trx_id) }}" class="am-btn" style="width:auto;padding:10px 20px;border-radius:100px;font-size:13px;display:inline-flex;align-items:center;gap:6px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            {{ __('Download Receipt') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="tl-empty">
            <div class="tl-empty-icon">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="15" rx="2"/><path d="M3 10h18"/><path d="M7 15h2"/><path d="M11 15h6"/></svg>
            </div>
            <span class="tl-empty-title">No transactions yet</span>
            <span class="tl-empty-sub">Your transactions will appear here</span>
            <a href="{{ setRoute("user.add.money.index") }}" class="tl-empty-btn">Fund Account</a>
        </div>
        @endforelse
    </div>

    @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $transactions->hasPages())
    <div class="tl-pagination">
        {{ $transactions->onEachSide(1)->links("pagination::bootstrap-5") }}
    </div>
    @endif
</div>

@push("script")
<script>
(function(){
    var list = document.getElementById("txList");
    if (!list) return;

    // Filter
    var filters = document.querySelectorAll(".tl-filter");
    var items = list.querySelectorAll(".tl-item");
    filters.forEach(function(btn) {
        btn.addEventListener("click", function() {
            filters.forEach(function(b) { b.classList.remove("active"); });
            this.classList.add("active");
            var f = this.dataset.filter;
            items.forEach(function(item, i) {
                var show = true;
                if (f === "deposit") show = item.dataset.type === "credit";
                else if (f === "withdrawal") show = item.dataset.type === "debit";
                else if (f === "transfer") show = item.dataset.type === "transfer";
                else if (f === "bonus") show = item.dataset.type === "bonus";
                item.style.display = show ? "" : "none";
                if (show) {
                    item.style.animation = "none";
                    void item.offsetHeight;
                    item.style.animation = "";
                    item.style.animationDelay = Math.min(i * 0.035, 1.5) + "s";
                }
            });
        });
    });

    // Search
    var searchInput = document.getElementById("txSearch");
    if (searchInput) {
        var timer;
        searchInput.addEventListener("input", function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                var q = searchInput.value.toLowerCase().trim();
                items.forEach(function(item, i) {
                    var text = (item.textContent || "").toLowerCase();
                    var match = !q || text.indexOf(q) !== -1;
                    item.style.display = match ? "" : "none";
                    if (match) {
                        item.style.animation = "none";
                        void item.offsetHeight;
                        item.style.animation = "";
                        item.style.animationDelay = Math.min(i * 0.03, 1.2) + "s";
                    }
                });
            }, 200);
        });
    }
})();
</script>
@endpush
@endsection
