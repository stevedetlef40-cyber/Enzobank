@extends('user.layouts.rise-master')

@push('css')
<style>
/* ============================================================
   VIRTUAL CARD — PROFESSIONAL / ANIMATED / THEME-AWARE
   Self-contained tokens so the page renders correctly in both
   light and dark mode regardless of the global theme tokens.
   ============================================================ */
:root {
    /* Map to the app's global design tokens so this screen matches the
       rest of the app in BOTH light and dark mode. */
    --vc-bg:        var(--bg-primary, #F4F6F9);
    --vc-surface:   var(--bg-card, #FFFFFF);
    --vc-surface-2: var(--bg-elevated, #F8FAFC);
    --vc-border:    var(--border-color, #E2E8F0);
    --vc-border-2:  var(--border-strong, #CBD5E1);
    --vc-text:      var(--text-primary, #0F172A);
    --vc-text-dim:  var(--text-secondary, #475569);
    --vc-text-mute: var(--text-muted, #64748B);
    --vc-accent:    var(--accent, #1D4ED8);
    --vc-accent-2:  var(--cyan, #2563EB);
    --vc-success:   var(--success, #3B82F6);
    --vc-danger:    var(--danger, #DC2626);
    --vc-warning:   var(--warning, #B45309);
    --vc-shadow:    var(--shadow-strong, 0 20px 60px rgba(0,0,0,0.45));
    /* Premium deep-blue → indigo → purple card gradient with depth */
    --vc-card-grad:  linear-gradient(135deg, #0B0B0F 0%, #161622 50%, #1A1A2E 100%);
    --vc-card-grad2: linear-gradient(135deg, #050508 0%, #0D0D14 100%);
}

.vc-page {
    background: var(--vc-bg);
    min-height: 100%;
    padding-bottom: 24px;
    animation: vcFadeIn 0.5s ease both;
}
@keyframes vcFadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }

.vc-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 16px 8px;
    animation: vcFadeIn 0.5s 0.05s ease both;
}
.vc-header-title {
    font-size: 22px; font-weight: 800; color: var(--vc-text);
    letter-spacing: -0.3px; margin: 0;
}
.vc-new-card {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 16px; border-radius: 999px;
    background: var(--vc-accent); color: #fff;
    font-size: 13px; font-weight: 700; text-decoration: none;
    box-shadow: 0 8px 22px rgba(59,130,246,0.35);
    transition: transform 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
}
.vc-new-card:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(59,130,246,0.45); }
.vc-new-card:active { transform: scale(0.97); }

/* ---------- CARD SCENE ---------- */
.vc-card-scene {
    perspective: 1400px;
    padding: 16px;
    animation: vcFadeIn 0.5s 0.1s ease both;
}
.vc-card-tilt {
    transition: transform 0.25s ease;
    transform-style: preserve-3d;
    will-change: transform;
}
.vc-card-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 1.586 / 1;
    cursor: pointer;
    transform-style: preserve-3d;
    transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}
.vc-card-wrapper.flipped { transform: rotateY(180deg); }
.vc-card-wrapper.frozen .vc-card-front,
.vc-card-wrapper.frozen .vc-card-back { filter: grayscale(0.9) brightness(0.7); }
.vc-card-wrapper.canceled .vc-card-front,
.vc-card-wrapper.canceled .vc-card-back { filter: grayscale(1) brightness(0.55); }
.vc-card-stamp {
    position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%) rotate(-12deg);
    font-size: 22px; font-weight: 900; letter-spacing: 3px; color: #fff;
    border: 3px solid #fff; border-radius: 8px; padding: 4px 14px;
    text-shadow: 0 2px 6px rgba(0,0,0,0.5); z-index: 5; pointer-events: none;
}

.vc-card-front, .vc-card-back {
    position: absolute; inset: 0;
    border-radius: 20px;
    backface-visibility: hidden; -webkit-backface-visibility: hidden;
    padding: 22px;
    display: flex; flex-direction: column; justify-content: space-between;
    overflow: hidden;
    color: #fff;
    box-shadow: var(--vc-shadow);
    border: 1px solid rgba(255,255,255,0.06);
}
.vc-card-front {
    background:
        radial-gradient(130% 100% at 0% 0%, rgba(255,255,255,0.08), rgba(255,255,255,0) 52%),
        var(--vc-card-grad);
    padding: 22px 24px;
}
.vc-card-back  { background: var(--vc-card-grad2); transform: rotateY(180deg); }

/* Animated sheen sweeping across the card */
.vc-card-shimmer {
    position: absolute; inset: 0; pointer-events: none;
    background: linear-gradient(115deg, transparent 30%, rgba(255,255,255,0.10) 50%, transparent 66%);
    background-size: 250% 250%;
    animation: vcSheen 4.5s ease-in-out infinite;
    mix-blend-mode: screen;
}
@keyframes vcSheen { 0% { background-position: 130% 0; } 100% { background-position: -30% 0; } }

/* Decorative glowing orbs */
.vc-card-front::after, .vc-card-back::after {
    content: ""; position: absolute; width: 220px; height: 220px; border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.08), transparent 70%);
    top: -90px; right: -70px; pointer-events: none;
}

.vc-card-top { display: flex; align-items: center; justify-content: space-between; }
.vc-card-logo { font-weight: 800; font-size: 16px; letter-spacing: 0.3px; color: #fff; }
.vc-card-logo span { color: var(--vc-accent-2); }
.vc-card-brand {
    font-style: italic; font-weight: 800; font-size: 20px; letter-spacing: 1px; color: #fff;
    text-shadow: 0 1px 2px rgba(0,0,0,0.25);
}
.vc-card-contactless { color: rgba(255,255,255,0.85); }

.vc-card-chip {
    width: 48px; height: 36px; margin-bottom: 14px;
    filter: drop-shadow(0 1px 1px rgba(0,0,0,0.35));
}
.vc-card-chip svg { width: 100%; height: 100%; display: block; }
.vc-card-mid { display: flex; }
.vc-card-chip-row { display: flex; align-items: center; gap: 14px; }
.vc-card-number {
    font-size: clamp(15px, 4.4vw, 21px); font-weight: 700; letter-spacing: 2px; white-space: nowrap; font-family: 'Courier New', monospace;
    color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.3); font-variant-numeric: tabular-nums;
}
.vc-card-bottom { display: flex; align-items: flex-end; justify-content: space-between; gap: 12px; }
.vc-card-holder, .vc-card-expiry { display: flex; flex-direction: column; gap: 3px; min-width: 0; }
    .vc-card-holder { max-width: 62%; }
.vc-card-holder-label, .vc-card-expiry-label {
    font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.7);
}
.vc-card-holder-name, .vc-card-expiry-date {
    font-size: 13px; font-weight: 600; color: #fff; text-transform: uppercase; letter-spacing: 0.4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* Back face */
.vc-card-magstripe { height: 42px; background: #000000; border-radius: 4px; margin: 0 -22px; }
.vc-card-signature {
    margin-top: 16px; display: flex; align-items: center; justify-content: space-between;
    background: rgba(255,255,255,0.96); border-radius: 6px; padding: 8px 12px;
}
.vc-card-signature-panel {
    flex: 1; height: 30px; margin-right: 12px;
    background: repeating-linear-gradient(45deg, rgba(15,23,42,0.06) 0 8px, rgba(15,23,42,0.02) 8px 16px);
    border-radius: 3px; border-bottom: 1px solid rgba(15,23,42,0.22);
}
.vc-card-signature-cvv { display: flex; flex-direction: column; align-items: flex-end; gap: 1px; }
.vc-card-cvv {
    font-size: 15px; font-weight: 800; color: #0F172A; letter-spacing: 1.5px;
    font-variant-numeric: tabular-nums; white-space: nowrap;
}
.vc-card-cvv-label { font-size: 8px; color: #475569; text-transform: uppercase; letter-spacing: 1px; display: block; text-align: right; }
.vc-card-back-inner { display: flex; flex-direction: column; justify-content: flex-end; height: 100%; gap: 10px; }
.vc-card-footer-text {
    font-size: 9px; line-height: 1.5; color: rgba(255,255,255,0.78);
}

/* ---------- STATUS ---------- */
.vc-status-row { display: flex; justify-content: center; padding: 4px 16px 0; }
.vc-status-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 16px; border-radius: 999px; font-size: 13px; font-weight: 700;
    border: 1px solid var(--vc-border);
}
.vc-status-badge.active   { color: var(--vc-success); background: rgba(59,130,246,0.12); border-color: rgba(59,130,246,0.3); }
.vc-status-badge.inactive { color: var(--vc-warning); background: rgba(245,158,11,0.12); border-color: rgba(245,158,11,0.3); }
.vc-status-badge.frozen   { color: var(--vc-accent); background: rgba(59,130,246,0.12); border-color: rgba(59,130,246,0.3); }
.vc-status-badge.canceled { color: var(--vc-danger); background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); }
.vc-status-dot { width: 8px; height: 8px; border-radius: 50%; }
.vc-status-dot.active   { background: var(--vc-success); box-shadow: 0 0 0 0 rgba(59,130,246,0.6); animation: vcPulse 1.8s infinite; }
.vc-status-dot.inactive { background: var(--vc-warning); }
.vc-status-dot.frozen   { background: var(--vc-accent); }
.vc-status-dot.canceled { background: var(--vc-danger); }
@keyframes vcPulse { 0% { box-shadow: 0 0 0 0 rgba(59,130,246,0.55); } 70% { box-shadow: 0 0 0 8px rgba(59,130,246,0); } 100% { box-shadow: 0 0 0 0 rgba(59,130,246,0); } }

/* ---------- ACTIONS ---------- */
.vc-actions { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; padding: 16px; }
.vc-action-btn {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    padding: 14px 8px; border-radius: 14px;
    background: var(--vc-surface); border: 1px solid var(--vc-border);
    color: var(--vc-text-dim); font-size: 11px; font-weight: 600;
    transition: transform 0.15s ease, border-color 0.15s ease, color 0.15s ease, background 0.15s ease;
}
.vc-action-btn svg { color: var(--vc-accent); }
.vc-action-btn:hover { transform: translateY(-2px); border-color: var(--vc-accent); color: var(--vc-text); }
.vc-action-btn:active { transform: scale(0.96); }
.vc-action-btn.is-active { color: var(--vc-accent); border-color: var(--vc-accent); background: rgba(59,130,246,0.10); }

/* ---------- DETAILS CARD ---------- */
.vc-details-card, .vc-tx-card {
    margin: 0 16px 16px; padding: 18px;
    background: var(--vc-surface); border: 1px solid var(--vc-border);
    border-radius: 18px; box-shadow: var(--vc-shadow);
    animation: vcFadeIn 0.5s 0.15s ease both;
}
.vc-details-title, .vc-tx-section-title {
    font-size: 14px; font-weight: 700; color: var(--vc-text); margin-bottom: 14px;
}
.vc-detail-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 11px 0; border-bottom: 1px solid var(--vc-border);
}
.vc-detail-row:last-child { border-bottom: none; }
.vc-detail-label { font-size: 13px; color: var(--vc-text-dim); }
.vc-detail-value { font-size: 13px; font-weight: 700; color: var(--vc-text); }
.vc-copy-btn { cursor: pointer; user-select: none; transition: transform 0.15s ease; }
.vc-copy-btn:active { transform: scale(0.85); }

/* ---------- TRANSACTIONS ---------- */
.vc-tx-card { padding: 18px; }
.rw-tx-list { display: flex; flex-direction: column; gap: 4px; }
.rw-tx-item {
    display: flex; align-items: center; gap: 12px; padding: 12px 4px;
    border-bottom: 1px solid var(--vc-border);
}
.rw-tx-item:last-child { border-bottom: none; }
.rw-tx-icon {
    width: 38px; height: 38px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.rw-tx-icon.green { background: rgba(59,130,246,0.14); color: var(--vc-success); }
.rw-tx-icon.red   { background: rgba(239,68,68,0.14);  color: var(--vc-danger); }
.rw-tx-info { flex: 1; min-width: 0; display: flex; flex-direction: column; }
.rw-tx-name { font-size: 13px; font-weight: 600; color: var(--vc-text); }
.rw-tx-date { font-size: 11px; color: var(--vc-text-mute); }
.rw-tx-amount { font-size: 14px; font-weight: 800; }
.rw-tx-amount.positive { color: var(--vc-success); }
.rw-tx-amount.negative { color: var(--vc-danger); }
.rw-empty { text-align: center; padding: 26px 10px; }
.rw-empty svg { color: var(--vc-text-mute); }
.rw-empty-title { display: block; font-size: 14px; font-weight: 700; color: var(--vc-text); margin-top: 10px; }
.rw-empty-sub { display: block; font-size: 12px; color: var(--vc-text-mute); margin-top: 4px; }

/* ---------- YOUR CARDS STRIP ---------- */
.vc-cards-strip { margin: 0 16px 20px; }
.vc-cards-strip .vc-details-title { padding: 0 2px; }
.vc-cards-scroll { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 4px; }
.vc-mini-card {
    min-width: 130px; padding: 14px; flex-shrink: 0;
    border-radius: 14px; color: #fff; position: relative; overflow: hidden;
    background: var(--vc-card-grad);
    box-shadow: 0 10px 24px rgba(0,0,0,0.45);
    border: 1px solid rgba(255,255,255,0.06);
}
.vc-mini-card .vc-mini-num { font-size: 13px; font-weight: 700; letter-spacing: 1px; }
.vc-mini-card .vc-mini-status { display: block; font-size: 11px; margin-top: 6px; opacity: 0.85; }
.vc-mini-card .vc-mini-status.off { opacity: 0.6; }

/* Toast */
.vc-toast {
    position: fixed; left: 50%; bottom: 96px; transform: translateX(-50%) translateY(20px);
    background: var(--vc-surface); color: var(--vc-text); border: 1px solid var(--vc-border);
    padding: 12px 18px; border-radius: 12px; font-size: 13px; font-weight: 600;
    box-shadow: var(--vc-shadow); opacity: 0; pointer-events: none; transition: all 0.3s ease; z-index: 300;
}
.vc-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
</style>
@endpush

@section('content')
@php
$cardCharge = $cardCharge ?? null;
$cardReloadCharge = $cardReloadCharge ?? null;
$transactions = $transactions ?? collect([]);
$myCards = $myCards ?? collect([]);
$firstCard = $myCards->first();
$cardNumber = $firstCard->card_number ?? '4242424242424242';
$cardName = strtoupper(auth()->user()->fullname ?? auth()->user()->username ?? 'CARD HOLDER');
$expMonth = $firstCard->expiry_month ?? '12';
$expYear = $firstCard->expiry_year ?? '28';
$cvv = null; // never expose the stored (encrypted) CVV in the page payload
$cvvMasked = $cvv ? '•••' : '—';
$cardStatus = $firstCard->is_active ?? true;
$initialStatus = ($firstCard->card_status ?? '') === 'canceled' ? 'canceled' : ($cardStatus ? 'active' : 'frozen');
$cardId = $firstCard->id ?? '';
$cardType = $firstCard->card_type ?? 'Virtual Debit';
$spendingLimit = $firstCard->card_amount ?? '$5,000.00';
@endphp

<div class="vc-page">
    <div class="vc-header">
        <h1 class="vc-header-title">My Card</h1>
        @if(($customer_card ?? 0) < $card_limit)
        <a href="{{ setRoute('user.strowallet.virtual.card.create') }}" class="vc-new-card">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            New Card
        </a>
        @endif
    </div>

    @if($showCardGate)

    <div class="vc-card-scene">
        <div class="vc-card-tilt" id="cardTilt">
            <div class="vc-card-wrapper {{ $initialStatus === 'canceled' ? 'canceled' : '' }} {{ $initialStatus === 'frozen' ? 'frozen' : '' }}" id="cardWrapper" data-card-id="{{ $cardId }}" data-cvv="{{ $cvv }}" onclick="this.classList.toggle('flipped')">
                <!-- FRONT -->
                <div class="vc-card-front">
                    <div class="vc-card-shimmer"></div>
                    <div class="vc-card-top">
                        <span class="vc-card-logo">Enzo<span>Bank</span></span>
                        <span class="vc-card-brand">VISA</span>
                    </div>
                    <div class="vc-card-mid">
                        <div class="vc-card-chip-row">
                            <span class="vc-card-chip">
                                <svg viewBox="0 0 48 36" role="img" aria-label="EMV chip">
                                    <defs>
                                        <linearGradient id="vcChipGrad" x1="0" y1="0" x2="1" y2="1">
                                            <stop offset="0" stop-color="#F0F0F0"/>
                                            <stop offset="0.45" stop-color="#C0C0C0"/>
                                            <stop offset="0.55" stop-color="#D0D0D0"/>
                                            <stop offset="1" stop-color="#888888"/>
                                        </linearGradient>
                                    </defs>
                                    <rect x="2" y="3" width="44" height="30" rx="6" fill="url(#vcChipGrad)" stroke="#777777" stroke-width="0.8"/>
                                    <line x1="6" y1="18" x2="42" y2="18" stroke="#777777" stroke-width="1"/>
                                    <line x1="16" y1="6" x2="16" y2="30" stroke="#777777" stroke-width="1"/>
                                    <line x1="32" y1="6" x2="32" y2="30" stroke="#777777" stroke-width="1"/>
                                    <rect x="6" y="6" width="10" height="9" rx="2" fill="none" stroke="#777777" stroke-width="1"/>
                                    <rect x="32" y="6" width="10" height="9" rx="2" fill="none" stroke="#777777" stroke-width="1"/>
                                    <rect x="6" y="21" width="10" height="9" rx="2" fill="none" stroke="#777777" stroke-width="1"/>
                                    <rect x="32" y="21" width="10" height="9" rx="2" fill="none" stroke="#777777" stroke-width="1"/>
                                    <rect x="18" y="13" width="12" height="10" rx="2" fill="none" stroke="#777777" stroke-width="1"/>
                                </svg>
                            </span>
                            <span class="vc-card-contactless">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <path d="M8.5 8.5a5 5 0 0 1 0 7"/><path d="M11.5 5.5a9 9 0 0 1 0 13"/><path d="M14.5 2.5a13 13 0 0 1 0 19"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="vc-card-number">•••• •••• •••• {{ substr($cardNumber, -4) }}</div>
                    <div class="vc-card-bottom">
                        <div class="vc-card-holder">
                            <span class="vc-card-holder-label">Card Holder</span>
                            <span class="vc-card-holder-name">{{ $cardName }}</span>
                        </div>
                        <div class="vc-card-expiry">
                            <span class="vc-card-expiry-label">Valid Thru</span>
                            <span class="vc-card-expiry-date">{{ $expMonth }}/{{ $expYear }}</span>
                        </div>
                    </div>
                    <div class="vc-card-stamp" id="vcCanceledStamp" @if($initialStatus !== 'canceled') style="display:none" @endif>CANCELED</div>
                </div>
                <!-- BACK -->
                <div class="vc-card-back">
                    <div class="vc-card-shimmer"></div>
                    <div class="vc-card-magstripe"></div>
                    <div class="vc-card-signature">
                        <div class="vc-card-signature-panel"></div>
                        <div class="vc-card-signature-cvv">
                            <span class="vc-card-cvv-label">CVV</span>
                            <span class="vc-card-cvv" data-vc-cvv>{{ $cvvMasked }}</span>
                        </div>
                    </div>
                    <div class="vc-card-back-inner">
                        <div></div>
                        <div class="vc-card-footer-text">This card is issued by EnzoBank pursuant to a license from VISA. For customer service contact support@enzobank.org</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="vc-status-row">
        <span class="vc-status-badge {{ $initialStatus }}" id="vcStatusBadge">
            <span class="vc-status-dot {{ $initialStatus }}" id="vcStatusDot"></span>
            <span id="vcStatusText">{{ ucfirst($initialStatus) }}</span>
        </span>
    </div>

    <!-- Actions -->
    <div class="vc-actions">
        <button class="vc-action-btn {{ $initialStatus === 'frozen' ? 'is-active' : '' }}" id="freezeBtn" type="button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <span class="vc-action-label">{{ $initialStatus === 'frozen' ? 'Frozen' : 'Freeze' }}</span>
        </button>
        <button class="vc-action-btn {{ $cvvRevealed ?? false ? 'is-active' : '' }}" id="viewCvvBtn" type="button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" id="viewCvvIcon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            <span class="vc-action-label">View CVV</span>
        </button>
        <button class="vc-action-btn" id="cancelBtn" type="button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            <span class="vc-action-label">Cancel</span>
        </button>
    </div>

    <!-- Card Details -->
    <div class="vc-details-card">
        <div class="vc-details-title">Card Details</div>
        <div class="vc-detail-row">
            <span class="vc-detail-label">Card Number</span>
            <span class="vc-detail-value">•••• •••• •••• {{ substr($cardNumber, -4) }} <span class="vc-copy-btn" onclick="navigator.clipboard.writeText('{{ $cardNumber }}').then(function(){showVcToast('Card number copied')})"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></span></span>
        </div>
        <div class="vc-detail-row">
            <span class="vc-detail-label">Expiry Date</span>
            <span class="vc-detail-value">{{ $expMonth }}/{{ $expYear }}</span>
        </div>
        <div class="vc-detail-row">
            <span class="vc-detail-label">Card Type</span>
            <span class="vc-detail-value">{{ $cardType }}</span>
        </div>
        <div class="vc-detail-row">
            <span class="vc-detail-label">CVV</span>
            <span class="vc-detail-value"><span data-vc-cvv>{{ $cvvMasked }}</span> <span class="vc-copy-btn" id="vcvRevealBtn" title="Show / hide CVV"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" id="vcvRevealIcon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span></span>
        </div>
        <div class="vc-detail-row">
            <span class="vc-detail-label">Spending Limit</span>
            <span class="vc-detail-value">{{ $spendingLimit }}</span>
        </div>
    </div>

    <!-- Card Transactions -->
    <div class="vc-tx-card">
        <div class="vc-tx-section-title">Card Transactions</div>
        <div class="rw-tx-list">
            @forelse($transactions as $tx)
            <div class="rw-tx-item">
                <div class="rw-tx-icon {{ $tx->type === 'ADD-MONEY' ? 'green' : 'red' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="{{ $tx->type === 'ADD-MONEY' ? '23 6 13.5 15.5 8.5 10.5 1 18' : '23 18 13.5 8.5 8.5 13.5 1 6' }}"/>
                    </svg>
                </div>
                <div class="rw-tx-info">
                    <span class="rw-tx-name">{{ $tx->type }}</span>
                    <span class="rw-tx-date">{{ $tx->created_at ? $tx->created_at->diffForHumans() : '' }}</span>
                </div>
                <span class="rw-tx-amount {{ $tx->type === 'ADD-MONEY' ? 'positive' : 'negative' }}">
                    {{ $tx->type === 'ADD-MONEY' ? '+' : '-' }}${{ number_format($tx->request_amount ?? 0, 2) }}
                </span>
            </div>
            @empty
            <div class="rw-empty">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="6" width="18" height="15" rx="2"/><path d="M3 10h18"/><path d="M7 15h2"/><path d="M11 15h6"/></svg>
                <span class="rw-empty-title">No transactions</span>
                <span class="rw-empty-sub">Card transactions will appear here</span>
            </div>
            @endforelse
        </div>
    </div>

    @if($myCards->count() > 1)
    <div class="vc-cards-strip">
        <div class="vc-details-title">Your Cards</div>
        <div class="vc-cards-scroll">
            @foreach($myCards as $card)
            <div class="vc-mini-card">
                <span class="vc-mini-num">•••• {{ substr($card->card_number ?? '', -4) }}</span>
                @php $miniStatus = ($card->card_status ?? '') === 'canceled' ? 'canceled' : ($card->is_active ? 'active' : 'frozen'); @endphp
                <span class="vc-mini-status {{ $miniStatus === 'active' ? '' : 'off' }}">{{ ucfirst($miniStatus) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif
</div>

<div class="vc-toast" id="vcToast"></div>
@endsection

@push('script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    var wrapper   = document.getElementById('cardWrapper');
    var freezeBtn = document.getElementById('freezeBtn');
    var viewCvv   = document.getElementById('viewCvvBtn');
    var cancelBtn = document.getElementById('cancelBtn');
    var ccvReveal = document.getElementById('vcvRevealBtn');
    if (!wrapper) return;

    var cardId      = wrapper.dataset.cardId || '';
    var realCvv     = '';
    var cvvUrl      = '{{ route("user.strowallet.virtual.card.cvv") }}';
    var csrf        = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    var cvvRevealed = false;
    var cardState   = wrapper.classList.contains('canceled') ? 'canceled'
                    : wrapper.classList.contains('frozen')   ? 'frozen'   : 'active';

    // ---- CVV masking / reveal ----
    function renderCvv() {
        var text = cvvRevealed ? (realCvv || '—') : (realCvv ? '•••' : '—');
        document.querySelectorAll('[data-vc-cvv]').forEach(function (el) { el.textContent = text; });
    }
    function loadAndRevealCvv() {
        if (cvvRevealed) { setRevealed(false); return; }
        if (realCvv) { setRevealed(true); showVcToast('CVV revealed'); return; }
        vcRequest(cvvUrl, { data_target: cardId }, 'POST')
            .then(function (res) {
                if (ok(res) && res.data && res.data.cvv) {
                    realCvv = res.data.cvv;
                    setRevealed(true);
                    showVcToast('CVV revealed');
                } else {
                    showVcToast(msgOf(res, 'Could not load CVV'));
                }
            })
            .catch(function () { showVcToast('Network error - try again'); });
    }
    function setRevealed(state) {
        cvvRevealed = state;
        if (viewCvv) {
            viewCvv.classList.toggle('is-active', state);
            var eyePath    = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            var eyeOffPath = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            var icon = document.getElementById('viewCvvIcon');
            if (icon) icon.innerHTML = state ? eyeOffPath : eyePath;
            var revealIcon = document.getElementById('vcvRevealIcon');
            if (revealIcon) revealIcon.innerHTML = state ? eyeOffPath : eyePath;
        }
        renderCvv();
    }

    // ---- Status pill ----
    function setCardStatus(state) {
        cardState = state;
        var badge = document.getElementById('vcStatusBadge');
        var dot   = document.getElementById('vcStatusDot');
        var text  = document.getElementById('vcStatusText');
        var states = ['active','frozen','canceled','inactive'];
        if (badge) { states.forEach(function (s) { badge.classList.remove(s); }); badge.classList.add(state); }
        if (dot)   { states.forEach(function (s) { dot.classList.remove(s); }); dot.classList.add(state); }
        if (text)  { text.textContent = state.charAt(0).toUpperCase() + state.slice(1); }
        wrapper.classList.toggle('frozen', state === 'frozen');
        wrapper.classList.toggle('canceled', state === 'canceled');
        var stamp = document.getElementById('vcCanceledStamp');
        if (stamp) stamp.style.display = state === 'canceled' ? 'block' : 'none';
    }

    // ---- Generic JSON request ----
    function vcRequest(url, payload, method) {
        return fetch(url, {
            method: method || 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        }).then(function (r) { return r.json(); });
    }
    function ok(res) {
        return !!res && (res.type === 'success' || (res.message && res.message.success));
    }
    function msgOf(res, fallback) {
        if (!res) return fallback;
        if (res.message && res.message.success) return res.message.success[0];
        if (res.message && res.message.error)   return res.message.error[0];
        return fallback;
    }

    // ---- Freeze / Unfreeze ----
    if (freezeBtn) {
        freezeBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (cardState === 'canceled') { showVcToast('Canceled cards cannot be changed'); return; }
            if (!cardId) { showVcToast('Card not available'); return; }
            var freezing = cardState !== 'frozen';
            freezeBtn.disabled = true;
            vcRequest('{{ route("user.strowallet.virtual.card.change.status") }}', { status: freezing ? 1 : 0, data_target: cardId }, 'PUT')
                .then(function (res) {
                    freezeBtn.disabled = false;
                    if (ok(res)) {
                        setCardStatus(freezing ? 'frozen' : 'active');
                        var lbl = freezeBtn.querySelector('.vc-action-label');
                        if (lbl) lbl.textContent = freezing ? 'Frozen' : 'Freeze';
                        showVcToast(freezing ? 'Card frozen' : 'Card unfrozen');
                    } else {
                        showVcToast(msgOf(res, 'Action failed'));
                    }
                })
                .catch(function () { freezeBtn.disabled = false; showVcToast('Network error — try again'); });
        });
    }

    // ---- View CVV (flips to back + reveals) ----
    if (viewCvv) {
        viewCvv.addEventListener('click', function (e) {
            e.stopPropagation();
            wrapper.classList.add('flipped');
            loadAndRevealCvv();
        });
    }

    // ---- Detail row CVV reveal ----
    if (ccvReveal) {
        ccvReveal.addEventListener('click', function (e) {
            e.stopPropagation();
            loadAndRevealCvv();
        });
    }

    // ---- Cancel (destructive, confirmed) ----
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (cardState === 'canceled') { showVcToast('Card already canceled'); return; }
            if (!cardId) { showVcToast('Card not available'); return; }
            if (!confirm('Cancel this virtual card? This cannot be undone.')) return;
            cancelBtn.disabled = true;
            vcRequest('{{ route("user.strowallet.virtual.card.cancel") }}', { data_target: cardId }, 'POST')
                .then(function (res) {
                    cancelBtn.disabled = false;
                    if (ok(res)) {
                        setCardStatus('canceled');
                        if (freezeBtn) { freezeBtn.disabled = true; var lbl = freezeBtn.querySelector('.vc-action-label'); if (lbl) lbl.textContent = 'Frozen'; }
                        cancelBtn.disabled = true;
                        showVcToast('Card canceled');
                    } else {
                        showVcToast(msgOf(res, 'Cancellation failed'));
                    }
                })
                .catch(function () { cancelBtn.disabled = false; showVcToast('Network error — try again'); });
        });
    }

    // ---- Subtle 3D tilt (desktop only) ----
    var scene = document.querySelector('.vc-card-scene');
    var tilt  = document.getElementById('cardTilt');
    if (scene && tilt && window.matchMedia('(pointer: fine)').matches) {
        scene.addEventListener('mousemove', function (e) {
            if (wrapper.classList.contains('flipped')) return;
            var r = scene.getBoundingClientRect();
            var px = (e.clientX - r.left) / r.width - 0.5;
            var py = (e.clientY - r.top) / r.height - 0.5;
            tilt.style.transform = 'rotateY(' + (px * 12) + 'deg) rotateX(' + (-py * 12) + 'deg)';
        });
        scene.addEventListener('mouseleave', function () { tilt.style.transform = ''; });
    }

    renderCvv();
});

function showVcToast(msg) {
    var t = document.getElementById('vcToast');
    if (!t) return;
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(window.__vcToastTimer);
    window.__vcToastTimer = setTimeout(function () { t.classList.remove('show'); }, 1800);
}
</script>
@endpush
