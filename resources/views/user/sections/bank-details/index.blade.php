@extends('user.layouts.rise-master')

@push('css')
<style>
/* ── Bank Details Hub — professional, theme-aware ── */
.bd-page { background: var(--bg-primary); min-height: calc(100vh - 72px); padding-bottom: 32px; }
.bd-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 16px 12px; position: sticky; top: 0; background: var(--bg-primary); z-index: 10; }
.bd-header-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
.bd-back { width: 36px; height: 36px; border-radius: 50%; background: var(--bg-card); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); box-shadow: var(--card-shadow); text-decoration: none; flex-shrink: 0; }
.bd-back:hover { color: var(--accent); }
.bd-title-wrap { min-width: 0; }
.bd-title { font-size: 18px; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
.bd-subtitle { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
.bd-body { padding: 0 16px; display: flex; flex-direction: column; gap: 20px; }

/* ── Steps guide ── */
.bd-steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.bd-step { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; padding: 14px 12px; display: flex; align-items: flex-start; gap: 10px; box-shadow: var(--card-shadow); }
.bd-step-num { width: 26px; height: 26px; border-radius: 50%; background: var(--accent); color: var(--text-on-accent); font-size: 12px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.bd-step-title { font-size: 12px; font-weight: 700; color: var(--text-primary); line-height: 1.3; }
.bd-step-sub { font-size: 11px; color: var(--text-muted); margin-top: 2px; line-height: 1.4; }

/* ── Section card ── */
.bd-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; box-shadow: var(--card-shadow); }
.bd-section-title { font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 14px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.bd-section-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
.bd-count-badge { margin-left: auto; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 999px; background: var(--bg-secondary); color: var(--text-secondary); border: 1px solid var(--border-color); }

/* ── Own international details (receive) ── */
.bd-receive { background: linear-gradient(135deg, var(--accent), var(--blue)); border: none; border-radius: 18px; padding: 22px; color: var(--text-on-accent); box-shadow: 0 12px 28px rgba(29,78,216,0.25); position: relative; overflow: hidden; }
.bd-receive::after { content: ""; position: absolute; right: -50px; top: -50px; width: 180px; height: 180px; border: 22px solid rgba(255,255,255,0.07); border-radius: 50%; }
.bd-receive::before { content: ""; position: absolute; right: 40px; bottom: -70px; width: 140px; height: 140px; border: 18px solid rgba(255,255,255,0.05); border-radius: 50%; }
.bd-receive-title { font-size: 15px; font-weight: 800; display: flex; align-items: center; gap: 8px; position: relative; z-index: 1; }
.bd-receive-sub { font-size: 12px; opacity: 0.85; margin: 4px 0 16px; position: relative; z-index: 1; line-height: 1.5; }
.bd-receive-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; position: relative; z-index: 1; }
.bd-receive-row { background: rgba(255,255,255,0.10); border: 1px solid rgba(255,255,255,0.18); border-radius: 10px; padding: 10px 12px; display: flex; align-items: center; justify-content: space-between; gap: 8px; }
.bd-receive-row--full { grid-column: 1 / -1; }
.bd-receive-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; opacity: 0.75; }
.bd-receive-value { font-size: 13px; font-weight: 700; font-family: "SF Mono", "Monaco", "Cascadia Code", "Roboto Mono", monospace; word-break: break-all; margin-top: 2px; }

/* ── Copy chips / buttons ── */
.bd-copy-btn { background: rgba(255,255,255,0.16); border: 1px solid rgba(255,255,255,0.3); color: var(--text-on-accent); border-radius: 7px; padding: 5px 8px; font-size: 11px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; flex-shrink: 0; transition: all 0.15s; }
.bd-copy-btn:hover { background: rgba(255,255,255,0.28); }
.bd-copy-btn--ghost { background: var(--bg-secondary); border: 1px solid var(--border-color); color: var(--text-secondary); }
.bd-copy-btn--ghost:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }
.bd-receive-actions { margin-top: 14px; display: flex; gap: 8px; flex-wrap: wrap; position: relative; z-index: 1; }

/* ── Empty state ── */
.bd-empty { text-align: center; padding: 28px 16px; color: var(--text-muted); }
.bd-empty-icon { width: 60px; height: 60px; border-radius: 50%; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; font-size: 22px; }
.bd-empty-title { font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px; }
.bd-empty-sub { font-size: 13px; color: var(--text-secondary); max-width: 320px; margin: 0 auto; line-height: 1.5; }

/* ── Bank detail card ── */
.bd-list { display: flex; flex-direction: column; gap: 12px; }
.bd-card { background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: 14px; padding: 16px; transition: all 0.15s; }
.bd-card:hover { border-color: var(--border-strong); }
.bd-card-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
.bd-card-avatar { width: 42px; height: 42px; border-radius: 12px; background: var(--accent-soft); color: var(--accent); font-weight: 800; font-size: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.bd-card-info { flex: 1; min-width: 0; }
.bd-card-name { font-size: 15px; font-weight: 700; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bd-card-bank { font-size: 12px; color: var(--text-secondary); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bd-card-meta { font-size: 11px; color: var(--text-muted); margin-top: 3px; }
.bd-badge { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 999px; flex-shrink: 0; white-space: nowrap; }
.bd-badge--active { background: var(--success-bg); color: var(--success-text); border: 1px solid var(--success); }
.bd-badge--inactive { background: var(--danger-bg); color: var(--danger-text); border: 1px solid var(--danger); }

.bd-card-details { margin-top: 14px; display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 10px; }
.bd-detail-row { display: flex; flex-direction: column; gap: 4px; }
.bd-detail-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-muted); display: flex; align-items: center; gap: 6px; }
.bd-detail-value { font-size: 13px; font-weight: 600; color: var(--text-primary); font-family: "SF Mono", "Monaco", "Cascadia Code", "Roboto Mono", monospace; word-break: break-all; display: inline-flex; align-items: center; gap: 6px; }

.bd-card-actions { margin-top: 14px; display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
.bd-btn { padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 600; border: 1px solid var(--border-color); background: var(--bg-secondary); color: var(--text-primary); cursor: pointer; transition: all 0.15s; display: inline-flex; align-items: center; gap: 6px; }
.bd-btn:hover { border-color: var(--accent); color: var(--accent); }
.bd-btn--danger { border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.1); color: #EF4444; }
.bd-btn--danger:hover { background: #EF4444; color: #fff; }

/* ── Add/Edit form ── */
.bd-form-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; box-shadow: var(--card-shadow); }
.bd-form-title { font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; display: flex; align-items: center; gap: 10px; }
.bd-form-intro { font-size: 12px; color: var(--text-secondary); margin: 0 0 16px 44px; line-height: 1.5; max-width: 460px; }
.bd-form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
.bd-field { display: flex; flex-direction: column; gap: 6px; }
.bd-field--full { grid-column: 1 / -1; }
.bd-label { font-size: 13px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 6px; }
.bd-req { color: var(--danger); }
.bd-opt { font-size: 11px; color: var(--text-muted); font-weight: 500; }
.bd-input, .bd-select {
    width: 100%; padding: 12px 14px; border-radius: 10px;
    border: 1.5px solid var(--border-color); background: var(--input-bg);
    font-size: 14px; color: var(--text-primary); outline: none; transition: border-color 0.15s;
}
.bd-input:focus, .bd-select:focus { border-color: var(--accent); }
.bd-input::placeholder { color: var(--placeholder); }
.bd-input--mono { font-family: "SF Mono", "Monaco", "Cascadia Code", "Roboto Mono", monospace; letter-spacing: 0.4px; }
.bd-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 40px; }
.bd-hint { font-size: 11px; color: var(--text-muted); line-height: 1.5; display: flex; gap: 5px; align-items: flex-start; }
.bd-hint svg { flex-shrink: 0; margin-top: 1px; }
.bd-error { font-size: 12px; color: var(--danger); }

.bd-form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-color); }
.bd-btn-primary { padding: 12px 24px; border-radius: 999px; font-size: 14px; font-weight: 700; background: var(--accent); color: var(--text-on-accent); border: none; cursor: pointer; transition: all 0.15s; }
.bd-btn-primary:hover { background: var(--blue); }
.bd-btn-secondary { padding: 12px 24px; border-radius: 999px; font-size: 14px; font-weight: 600; background: var(--bg-secondary); color: var(--text-primary); border: 1.5px solid var(--border-color); cursor: pointer; transition: all 0.15s; }
.bd-btn-secondary:hover { border-color: var(--accent); color: var(--accent); }

/* ── Where to find these info box ── */
.bd-where { margin-top: 16px; background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: 12px; padding: 14px 16px; display: flex; gap: 12px; align-items: flex-start; }
.bd-where-icon { width: 32px; height: 32px; border-radius: 8px; background: rgba(245,158,11,0.12); color: #F59E0B; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.bd-where-title { font-size: 12px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
.bd-where-text { font-size: 12px; color: var(--text-secondary); line-height: 1.6; }
.bd-where-text ul { margin: 6px 0 0; padding-left: 18px; display: flex; flex-direction: column; gap: 3px; }

/* ── Security tip ── */
.bd-tip { display: flex; gap: 10px; align-items: flex-start; background: var(--success-bg); border: 1px solid rgba(37,99,235,0.25); border-radius: 12px; padding: 12px 14px; font-size: 12px; color: var(--success-text); line-height: 1.5; }
.bd-tip svg { flex-shrink: 0; margin-top: 1px; }

/* ── Toast ── */
.bd-toast { position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%) translateY(10px); background: var(--accent); color: var(--text-on-accent); padding: 12px 24px; border-radius: 999px; font-size: 14px; font-weight: 600; opacity: 0; transition: all 0.3s; pointer-events: none; z-index: 100; box-shadow: 0 8px 24px rgba(0,0,0,0.18); }
.bd-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

/* Light theme overrides */
[data-theme="light"] .bd-card { background: var(--bg-primary); }
[data-theme="light"] .bd-card:hover { border-color: #CBD5E1; box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
[data-theme="light"] .bd-input, [data-theme="light"] .bd-select { background: #fff; border-color: #E2E8F0; }
[data-theme="light"] .bd-input:focus, [data-theme="light"] .bd-select:focus { border-color: #3B82F6; }
[data-theme="light"] .bd-btn { background: #F1F5F9; border-color: #E2E8F0; color: #334155; }
[data-theme="light"] .bd-btn:hover { border-color: #3B82F6; color: #2563EB; }
[data-theme="light"] .bd-btn-secondary { background: #F1F5F9; border-color: #E2E8F0; color: #334155; }
[data-theme="light"] .bd-btn-secondary:hover { border-color: #3B82F6; color: #2563EB; }

@media (max-width: 480px) {
    .bd-steps { grid-template-columns: 1fr; }
    .bd-receive-grid { grid-template-columns: 1fr; }
}
@media (prefers-reduced-motion: reduce) {
    .bd-card, .bd-input, .bd-select, .bd-btn, .bd-btn-primary, .bd-btn-secondary, .bd-toast, .bd-copy-btn { transition: none; }
}
</style>
@endpush

@section('content')
<div class="bd-page">
    <div class="bd-header">
        <div class="bd-header-left">
            <a href="{{ route('user.rise.home') }}" class="bd-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            </a>
            <div class="bd-title-wrap">
                <div class="bd-title">{{ __('Bank Details') }}</div>
                <div class="bd-subtitle">{{ __('Add and manage the accounts you use to send money') }}</div>
            </div>
        </div>
    </div>

    <div class="bd-body">
        {{-- How it works --}}
        <div class="bd-steps">
            <div class="bd-step">
                <span class="bd-step-num">1</span>
                <div>
                    <div class="bd-step-title">{{ __('Add recipient details') }}</div>
                    <div class="bd-step-sub">{{ __('Store the bank account of anyone you want to pay.') }}</div>
                </div>
            </div>
            <div class="bd-step">
                <span class="bd-step-num">2</span>
                <div>
                    <div class="bd-step-title">{{ __('Send in seconds') }}</div>
                    <div class="bd-step-sub">{{ __('Pick the saved account and transfer instantly.') }}</div>
                </div>
            </div>
            <div class="bd-step">
                <span class="bd-step-num">3</span>
                <div>
                    <div class="bd-step-title">{{ __('Track everything') }}</div>
                    <div class="bd-step-sub">{{ __('Every transfer appears in your transaction history.') }}</div>
                </div>
            </div>
        </div>

        {{-- Your own EnzoBank international details (to receive money) --}}
        @php
            $myBankName = $user->network_bank_name ?? 'EnzoBank';
            $myAccount  = $user->network_account_number;
            $myIban     = $user->network_iban;
            $mySwift    = $user->network_swift ?? 'ENZOUS33';
            $myShareText = implode("\n", [
                $myBankName . ' - ' . __('International Details'),
                __('Bank Name') . ': ' . $myBankName,
                __('Account Number') . ': ' . ($myAccount ?: '-'),
                __('IBAN') . ': ' . ($myIban ?: '-'),
                __('SWIFT / BIC') . ': ' . $mySwift,
            ]);
        @endphp
        <div class="bd-section bd-receive">
            <div class="bd-receive-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8l4 4-4 4M3 12h14M7 8l-4 4 4 4"/></svg>
                {{ __('Your EnzoBank International Details') }}
            </div>
            <div class="bd-receive-sub">
                {{ __('Share these details with any EnzoBank user to receive money instantly. Tap any row or use "Copy All" to paste into chat, email or a form.') }}
            </div>
            <div class="bd-receive-grid">
                <div class="bd-receive-row bd-receive-row--full">
                    <div>
                        <div class="bd-receive-label">{{ __('Bank Name') }}</div>
                        <div class="bd-receive-value">{{ $myBankName }}</div>
                    </div>
                    <button type="button" class="bd-copy-btn" data-copy="{{ $myBankName }}">{{ __('Copy') }}</button>
                </div>
                <div class="bd-receive-row bd-receive-row--full">
                    <div>
                        <div class="bd-receive-label">{{ __('Account Number') }}</div>
                        <div class="bd-receive-value">{{ $myAccount ?: '-' }}</div>
                    </div>
                    <button type="button" class="bd-copy-btn" data-copy="{{ $myAccount ?: '' }}" {{ $myAccount ? '' : 'disabled' }}>{{ __('Copy') }}</button>
                </div>
                <div class="bd-receive-row bd-receive-row--full">
                    <div>
                        <div class="bd-receive-label">{{ __('IBAN') }}</div>
                        <div class="bd-receive-value">{{ $myIban ?: '-' }}</div>
                    </div>
                    <button type="button" class="bd-copy-btn" data-copy="{{ $myIban ?: '' }}" {{ $myIban ? '' : 'disabled' }}>{{ __('Copy') }}</button>
                </div>
                <div class="bd-receive-row bd-receive-row--full">
                    <div>
                        <div class="bd-receive-label">{{ __('SWIFT / BIC') }}</div>
                        <div class="bd-receive-value">{{ $mySwift }}</div>
                    </div>
                    <button type="button" class="bd-copy-btn" data-copy="{{ $mySwift }}">{{ __('Copy') }}</button>
                </div>
            </div>
            <div class="bd-receive-actions">
                <button type="button" class="bd-copy-btn" data-copyall="{{ $myShareText }}" style="background:var(--text-on-accent);color:var(--accent);border:none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    {{ __('Copy All Details') }}
                </button>
            </div>
        </div>

        {{-- External Bank Details (user-added) --}}
        <div class="bd-section">
            <h3 class="bd-section-title">
                <span class="bd-section-icon" style="background:rgba(59,130,246,0.12);color:#3B82F6;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </span>
                {{ __('External Bank Accounts') }}
                <span class="bd-count-badge">{{ $user->bankDetails->count() }} {{ __('saved') }}</span>
            </h3>

            <div class="bd-list">
                @forelse($user->bankDetails as $detail)
                @php
                    $initials = strtoupper(substr($detail->recipient_name, 0, 1)) . (strpos($detail->recipient_name, ' ') !== false ? strtoupper(substr(strrchr($detail->recipient_name, ' '), 1, 1)) : '');
                    $copyAllText = implode("\n", [
                        $detail->recipient_name . ' - ' . $detail->bank_name,
                        __('Recipient Name') . ': ' . $detail->recipient_name,
                        __('Bank Name') . ': ' . $detail->bank_name,
                        __('Account / IBAN') . ': ' . $detail->account_number_iban,
                        __('Country') . ': ' . $detail->country,
                        ($detail->swift_bic ? __('SWIFT / BIC') . ': ' . $detail->swift_bic : ''),
                    ]);
                @endphp
                <div class="bd-card">
                    <div class="bd-card-head">
                        <div class="bd-card-avatar">{{ $initials }}</div>
                        <div class="bd-card-info">
                            <div class="bd-card-name">{{ $detail->recipient_name }}</div>
                            <div class="bd-card-bank">{{ $detail->bank_name }}</div>
                            <div class="bd-card-meta">{{ __('Added') }} {{ $detail->created_at ? $detail->created_at->format('d M Y') : '' }}</div>
                        </div>
                        <span class="bd-badge {{ $detail->status ? 'bd-badge--active' : 'bd-badge--inactive' }}">
                            {{ $detail->status ? __('Active') : __('Inactive') }}
                        </span>
                    </div>

                    <div class="bd-card-details">
                        <div class="bd-detail-row">
                            <span class="bd-detail-label">{{ __('Account / IBAN') }}</span>
                            <span class="bd-detail-value">{{ $detail->account_number_iban }}
                                <button type="button" class="bd-copy-btn bd-copy-btn--ghost" data-copy="{{ $detail->account_number_iban }}" title="{{ __('Copy') }}">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    {{ __('Copy') }}
                                </button>
                            </span>
                        </div>
                        @if($detail->swift_bic)
                        <div class="bd-detail-row">
                            <span class="bd-detail-label">{{ __('SWIFT / BIC') }}</span>
                            <span class="bd-detail-value">{{ $detail->swift_bic }}
                                <button type="button" class="bd-copy-btn bd-copy-btn--ghost" data-copy="{{ $detail->swift_bic }}" title="{{ __('Copy') }}">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    {{ __('Copy') }}
                                </button>
                            </span>
                        </div>
                        @endif
                        <div class="bd-detail-row">
                            <span class="bd-detail-label">{{ __('Country') }}</span>
                            <span class="bd-detail-value">{{ $detail->country }}</span>
                        </div>
                    </div>

                    <div class="bd-card-actions">
                        <button type="button" class="bd-btn" data-copyall="{{ $copyAllText }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            {{ __('Copy All Details') }}
                        </button>
                        <form method="POST" action="{{ route('user.bank.details.toggle', $detail->id) }}">
                            @csrf @method('PUT')
                            <button type="submit" class="bd-btn">{{ $detail->status ? __('Deactivate') : __('Activate') }}</button>
                        </form>
                        <form method="POST" action="{{ route('user.bank.details.destroy', $detail->id) }}" onsubmit="return confirm('{{ __('Remove this bank detail?') }}');">
                            @csrf @method('DELETE')
                            <button type="submit" class="bd-btn bd-btn--danger">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                {{ __('Remove') }}
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="bd-empty">
                    <div class="bd-empty-icon" style="background:rgba(59,130,246,0.12);color:#3B82F6;">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    </div>
                    <div class="bd-empty-title">{{ __('No saved bank accounts yet') }}</div>
                    <div class="bd-empty-sub">{{ __('Add the bank account you want to send money to below. Only active details can be used for transfers.') }}</div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Add / Edit Bank Detail Form --}}
        <div class="bd-form-section">
            <h3 class="bd-form-title">
                <span class="bd-section-icon" style="background:rgba(59,130,246,0.12);color:#3B82F6;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </span>
                {{ __('Add New Bank Detail') }}
            </h3>
            <p class="bd-form-intro">{{ __('Fill in the recipient account exactly as it appears on their bank statement. Double-check each field — a single wrong character can delay a transfer.') }}</p>

            <form method="POST" action="{{ route('user.bank.details.store') }}" id="bdForm" autocomplete="off">
                @csrf
                <div class="bd-form-grid">
                    <div class="bd-field">
                        <label class="bd-label">{{ __('Recipient Full Name') }} <span class="bd-req">*</span></label>
                        <input type="text" name="recipient_name" class="bd-input" value="{{ old('recipient_name') }}" placeholder="{{ __('e.g. Jane Doe') }}" maxlength="255" required>
                        <span class="bd-hint">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            {{ __('The exact name on the recipient bank account, as shown on their statement.') }}
                        </span>
                        @error('recipient_name')<span class="bd-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="bd-field">
                        <label class="bd-label">{{ __('Bank Name') }} <span class="bd-req">*</span></label>
                        <input type="text" name="bank_name" class="bd-input" value="{{ old('bank_name') }}" placeholder="{{ __('e.g. Barclays UK') }}" maxlength="255" required>
                        <span class="bd-hint">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            {{ __('The bank that holds the recipient account, e.g. Barclays, Chase, UBA.') }}
                        </span>
                        @error('bank_name')<span class="bd-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="bd-field">
                        <label class="bd-label">{{ __('Account Number / IBAN') }} <span class="bd-req">*</span></label>
                        <input type="text" name="account_number_iban" id="bdAccountInput" class="bd-input bd-input--mono" value="{{ old('account_number_iban') }}" placeholder="{{ __('e.g. GB29 NWBK 6016 1331 9268 19') }}" maxlength="34" required>
                        <span class="bd-hint">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            {{ __('A plain account number (8-12 digits) or full IBAN (up to 34 characters) for international accounts.') }}
                        </span>
                        @error('account_number_iban')<span class="bd-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="bd-field">
                        <label class="bd-label">{{ __('Country') }} <span class="bd-req">*</span></label>
                        <select name="country" class="bd-select" required>
                            <option value="" disabled selected>{{ __('Select country') }}</option>
                            @foreach($countries as $countryName)
                                <option value="{{ $countryName }}" {{ old('country') == $countryName ? 'selected' : '' }}>{{ $countryName }}</option>
                            @endforeach
                        </select>
                        <span class="bd-hint">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            {{ __('Where the recipient bank is registered.') }}
                        </span>
                        @error('country')<span class="bd-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="bd-field">
                        <label class="bd-label">{{ __('SWIFT / BIC') }} <span class="bd-opt">({{ __('optional') }})</span></label>
                        <input type="text" name="swift_bic" id="bdSwiftInput" class="bd-input bd-input--mono" value="{{ old('swift_bic') }}" placeholder="{{ __('e.g. NWBKGB2L') }}" maxlength="11">
                        <span class="bd-hint">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            {{ __('8 or 11 letter bank identifier. Found on bank statements or via a SWIFT finder. Helps route international transfers.') }}
                        </span>
                        @error('swift_bic')<span class="bd-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="bd-where">
                    <span class="bd-where-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </span>
                    <div>
                        <div class="bd-where-title">{{ __('Where to find these details') }}</div>
                        <div class="bd-where-text">
                            {{ __('If you are not sure, ask the recipient to check:') }}
                            <ul>
                                <li>{{ __('Their bank statement or mobile banking app — shows account number, IBAN and SWIFT/BIC.') }}</li>
                                <li>{{ __('A recent invoice or payment confirmation sent to their account.') }}</li>
                                <li>{{ __('A bank check (cheque) — the account number is printed at the bottom.') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bd-form-actions">
                    <button type="button" class="bd-btn-secondary" onclick="window.location.href='{{ route('user.rise.home') }}'">{{ __('Cancel') }}</button>
                    <button type="submit" class="bd-btn-primary">{{ __('Add Bank Detail') }}</button>
                </div>
            </form>
        </div>

        {{-- Security tip --}}
        <div class="bd-tip">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M12 11V7"/><circle cx="12" cy="14" r="0.5"/></svg>
            <span>{{ __('Security: your saved bank details are encrypted in transit and only used to process the transfers you confirm. We never show your details to other users.') }}</span>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="bd-toast" id="bdToast">{{ session('success')[0] }}</div>
@elseif(session('error'))
    <div class="bd-toast" id="bdToast" style="background:var(--danger);">{{ session('error')[0] }}</div>
@endif

@push('script')
<script>
(function() {
    // Live formatting: uppercase IBAN/SWIFT as you type
    var accountInput = document.getElementById('bdAccountInput');
    var swiftInput = document.getElementById('bdSwiftInput');
    var monoInputs = document.querySelectorAll('.bd-input--mono');
    monoInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            var start = this.selectionStart;
            this.value = this.value.toUpperCase().replace(/\s+/g, ' ').trim();
            this.setSelectionRange(start, start);
        });
    });

    function showToast(message, isError) {
        var toast = document.getElementById('bdToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'bdToast';
            toast.className = 'bd-toast';
            document.body.appendChild(toast);
        }
        toast.textContent = message;
        toast.style.background = isError ? 'var(--danger)' : 'var(--accent)';
        toast.classList.add('show');
        clearTimeout(toast._timer);
        toast._timer = setTimeout(function() { toast.classList.remove('show'); }, 2500);
    }

    function copyText(text, successMessage) {
        if (!text) { showToast('Nothing to copy', true); return; }
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                showToast(successMessage || 'Copied to clipboard');
            }).catch(function() { legacyCopy(text, successMessage); });
        } else {
            legacyCopy(text, successMessage);
        }
    }

    function legacyCopy(text, successMessage) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        try {
            document.execCommand('copy');
            showToast(successMessage || 'Copied to clipboard');
        } catch (e) {
            showToast('Press Ctrl+C to copy', true);
        }
        ta.remove();
    }

    // Per-field copy buttons
    document.querySelectorAll('[data-copy]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var label = this.closest('.bd-receive-row, .bd-detail-row, .bd-card-actions');
            var name = label && label.querySelector('.bd-receive-label, .bd-detail-label');
            copyText(this.getAttribute('data-copy'), (name ? name.textContent.trim() + ' copied' : 'Copied to clipboard'));
        });
    });

    // Copy-all buttons
    document.querySelectorAll('[data-copyall]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            copyText(this.getAttribute('data-copyall'), 'All details copied');
        });
    });

    // Auto-show session toast on load
    var sessionToast = document.getElementById('bdToast');
    if (sessionToast && sessionToast.textContent.trim()) {
        sessionToast.classList.add('show');
        setTimeout(function() { sessionToast.classList.remove('show'); }, 3000);
    }
})();
</script>
@endpush
@endsection
