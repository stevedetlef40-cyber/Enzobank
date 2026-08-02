@extends('user.layouts.rise-master')

@push('css')
<style>
/* ── Send Money ── */
.send-tabs {
    display: flex;
    background: #1E293B;
    border-radius: 12px;
    padding: 4px;
    margin-bottom: 20px;
}
.send-tab {
    flex: 1;
    padding: 12px 8px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #94A3B8;
    text-align: center;
    cursor: pointer;
    transition: all 0.15s;
    border: none;
    background: none;
    -webkit-tap-highlight-color: transparent;
}
.send-tab.active {
    background: #3B82F6;
    color: #fff;
}
.send-tab-content { display: none; }
.send-tab-content.active { display: block; }

/* Form Fields */
.send-field-group { margin-bottom: 16px; }
.send-label {
    font-size: 13px;
    font-weight: 600;
    color: #94A3B8;
    margin-bottom: 6px;
    display: block;
}
.send-input-wrap {
    display: flex;
    align-items: center;
    border: 1.5px solid #334155;
    border-radius: 12px;
    overflow: hidden;
    transition: border-color 0.15s;
    background: #1E293B;
}
.send-input-wrap:focus-within { border-color: #3B82F6; }
.send-input {
    flex: 1;
    border: none;
    outline: none;
    padding: 14px 16px;
    font-size: 16px;
    font-weight: 500;
    color: #fff;
    background: transparent;
    min-width: 0;
}
.send-input::placeholder { color: #4B5563; }
.send-input-pill {
    padding: 0 14px;
    font-size: 13px;
    font-weight: 600;
    color: #94A3B8;
    background: rgba(255,255,255,0.04);
    align-self: stretch;
    display: flex;
    align-items: center;
    white-space: nowrap;
}

/* Recipient preview */
.send-recipient-preview {
    display: none;
    background: rgba(59,130,246,0.08);
    border: 1px solid rgba(59,130,246,0.2);
    border-radius: 12px;
    padding: 14px 16px;
    margin-top: 12px;
    align-items: center;
    gap: 12px;
}
.send-recipient-preview.show { display: flex; }
.send-recipient-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #3B82F6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 16px;
    flex-shrink: 0;
}
.send-recipient-info { flex: 1; }
.send-recipient-name { font-size: 15px; font-weight: 600; color: #fff; }
.send-recipient-detail { font-size: 12px; color: #94A3B8; }
.send-recipient-check { color: #3B82F6; flex-shrink: 0; }

/* Fee/Info cards */
.send-fee-card {
    background: rgba(59,130,246,0.06);
    border: 1px solid rgba(59,130,246,0.12);
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 16px;
}
.send-fee-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 0;
    font-size: 13px;
}
.send-fee-label { color: #94A3B8; }
.send-fee-value { color: #fff; font-weight: 600; }
.send-fee-divider { height: 1px; background: rgba(59,130,246,0.1); margin: 8px 0; }

/* Submit buttons */
.send-btn {
    width: 100%;
    padding: 16px;
    border-radius: 100px;
    font-size: 16px;
    font-weight: 700;
    border: none;
    background: linear-gradient(135deg, #3B82F6, #2563EB);
    color: #fff;
    cursor: pointer;
    transition: opacity 0.15s, transform 0.15s;
    -webkit-tap-highlight-color: transparent;
}
.send-btn:hover { opacity: 0.92; }
.send-btn:active { transform: scale(0.98); }
.send-btn:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

/* Light mode */
[data-theme="light"] .send-tabs { background: #E2E8F0; }
[data-theme="light"] .send-tab { color: #64748B; }
[data-theme="light"] .send-tab.active { background: #3B82F6; color: #fff; }
[data-theme="light"] .send-input-wrap { background: #fff; border-color: #D1D5DB; }
[data-theme="light"] .send-input { color: #1F2937; }
[data-theme="light"] .send-input::placeholder { color: #9CA3AF; }
[data-theme="light"] .send-input-pill { color: #64748B; background: rgba(0,0,0,0.03); }
[data-theme="light"] .send-fee-card { background: rgba(59,130,246,0.04); border-color: rgba(59,130,246,0.1); }
[data-theme="light"] .send-fee-label { color: #64748B; }
[data-theme="light"] .send-fee-value { color: #1F2937; }

/* Virtual card gate banner */
.send-card-gate {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 14px 16px;
    background: rgba(239,68,68,0.08);
    border: 1px solid rgba(239,68,68,0.2);
    border-radius: 12px;
    margin-bottom: 16px;
}
.send-card-gate svg { flex-shrink: 0; margin-top: 2px; }
.send-card-gate-body { flex: 1; font-size: 13px; line-height: 1.5; color: #FCA5A5; }
.send-card-gate-body strong { display: block; font-size: 14px; color: #F87171; margin-bottom: 2px; }
.send-card-gate-btn {
    display: inline-block;
    margin-top: 8px;
    padding: 8px 18px;
    border-radius: 100px;
    background: linear-gradient(135deg, #3B82F6, #2563EB);
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    border: none;
    cursor: pointer;
}
.send-card-gate-btn:hover { opacity: 0.92; color: #fff; }

/* Copy buttons for international details card */
.send-copy { width: 28px; height: 28px; border-radius: 7px; border: 1px solid rgba(148,163,184,0.35); background: rgba(148,163,184,0.08); color: var(--text-secondary,#94A3B8); cursor: pointer; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s; }
.send-copy:hover { border-color: var(--accent,#3B82F6); color: var(--accent,#3B82F6); background: rgba(59,130,246,0.1); }
.send-copy[disabled] { opacity: 0.35; cursor: not-allowed; }
.send-toast { position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%) translateY(10px); background: var(--accent,#3B82F6); color: #fff; padding: 12px 24px; border-radius: 999px; font-size: 14px; font-weight: 600; opacity: 0; transition: all 0.3s; pointer-events: none; z-index: 100; box-shadow: 0 8px 24px rgba(0,0,0,0.18); }
.send-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ __('Send Money') }}</h1>
</div>
<div class="am-body">

    {{-- Tab Toggle --}}
    <div class="send-tabs" role="tablist">
        <button class="send-tab active" data-tab="internal" role="tab">🏦 EnzoBank Account</button>
        <button class="send-tab" data-tab="other" role="tab">🌍 Other Bank</button>
    </div>

    {{-- ====== TAB 1: Internal EnzoBank Transfer ====== --}}
    <div class="send-tab-content active" id="tab-internal">
        <div class="am-card">
            {{-- Sender's auto-generated international details --}}
            @php
                $sendUser = auth()->user();
                $sendBank = $sendUser->network_bank_name ?? 'EnzoBank';
                $sendAcc  = $sendUser->network_account_number;
                $sendIban = $sendUser->network_iban;
                $sendSwift = $sendUser->network_swift ?? 'ENZOUS33';
                $sendShare = implode("\n", [
                    $sendBank . ' - International Details',
                    'Bank Name: ' . $sendBank,
                    'Account Number: ' . ($sendAcc ?: '-'),
                    'IBAN: ' . ($sendIban ?: '-'),
                    'SWIFT / BIC: ' . $sendSwift,
                ]);
            @endphp
            <div style="background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.15);border-radius:10px;padding:12px 16px;margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:16px;">🏦</span>
                        <strong style="font-size:14px;color:var(--text-primary,#fff);">{{ __('Your EnzoBank International Details') }}</strong>
                    </div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <button type="button" class="send-copy-all" data-copyall="{{ $sendShare }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;background:var(--accent,#3B82F6);color:var(--text-on-accent,#fff);border:none;cursor:pointer;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            {{ __('Copy All') }}
                        </button>
                        <a href="{{ route('user.bank.details.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;background:var(--bg-secondary,#1E293B);color:var(--text-primary,#fff);text-decoration:none;transition:all 0.15s;white-space:nowrap;" title="{{ __('Manage your external bank details') }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            {{ __('Manage Bank Details') }}
                        </a>
                    </div>
                </div>
                <div style="font-size:13px;color:var(--text-secondary,#94A3B8);line-height:1.8;margin-top:10px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;"><span><strong style="color:var(--text-primary,#fff);">Bank Name:</strong> {{ $sendBank }}</span><button type="button" class="send-copy" data-copy="{{ $sendBank }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;"><span><strong style="color:var(--text-primary,#fff);">Account Number:</strong> <span style="font-family:monospace;">{{ $sendAcc ?: '-' }}</span></span><button type="button" class="send-copy" data-copy="{{ $sendAcc ?: '' }}" {{ $sendAcc ? '' : 'disabled' }}><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;"><span><strong style="color:var(--text-primary,#fff);">IBAN:</strong> <span style="font-family:monospace;">{{ $sendIban ?: '-' }}</span></span><button type="button" class="send-copy" data-copy="{{ $sendIban ?: '' }}" {{ $sendIban ? '' : 'disabled' }}><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;"><span><strong style="color:var(--text-primary,#fff);">SWIFT/BIC:</strong> <span style="font-family:monospace;">{{ $sendSwift }}</span></span><button type="button" class="send-copy" data-copy="{{ $sendSwift }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div>
                </div>
                <p style="margin:8px 0 0;font-size:11px;color:var(--text-muted,#64748B);">Share these details with other EnzoBank users to receive transfers instantly. Tap a copy icon or "Copy All" to paste into chat or email.</p>
            </div>

            {{-- Bank Details Required Banner --}}
            @if(auth()->user()->bankDetails->where('status', 1)->count() === 0)
            <div style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);border-radius:10px;padding:14px 16px;margin-bottom:16px;display:flex;align-items:flex-start;gap:12px;">
                <div style="flex-shrink:0;width:36px;height:36px;border-radius:50%;background:rgba(245,158,11,0.2);display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <strong style="font-size:14px;color:var(--text-primary,#fff);display:block;margin-bottom:4px;">{{ __('Bank Details Required') }}</strong>
                    <p style="margin:0;font-size:13px;color:var(--text-secondary,#94A3B8);line-height:1.5;">{{ __('To send money to another EnzoBank account, you must first add at least one active external bank detail. This is a security requirement.') }}</p>
                    <a href="{{ route('user.bank.details.index') }}" style="display:inline-block;margin-top:10px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;background:#F59E0B;color:#fff;text-decoration:none;transition:background 0.15s;">{{ __('Add Bank Details Now') }}</a>
                </div>
            </div>
            @endif
            <form method="POST" action="{{ route('user.rise.send.submit') }}">
                @csrf
                <input type="hidden" name="type" value="internal">
                <div class="send-field-group">
                    <label class="send-label">{{ __('Send From Wallet') }}</label>
                    <div class="send-input-wrap">
                        <select name="wallet_id" id="internalWallet" class="send-input" style="appearance:auto;">
                            <option value="" disabled selected>{{ __("Select Wallet") }}</option>
                            @foreach(auth()->user()->wallets as $w)
                                <option value="{{ $w->id }}" data-currency="{{ $w->currency->code }}">{{ $w->currency->code }} — {{ $w->currency->name }} ({{ $w->currency->symbol }}{{ number_format($w->balance, 2) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('Recipient International Account / IBAN / Username') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="account" id="recipientLookup" placeholder="Enter recipient's international account number, IBAN, or username" autocomplete="off">
                    </div>
                    {{-- Recipient preview --}}
                    <div class="send-recipient-preview" id="recipientPreview">
                        <div class="send-recipient-avatar" id="recipientAvatar">J</div>
                        <div class="send-recipient-info">
                            <div class="send-recipient-name" id="recipientName">John Doe</div>
                            <div class="send-recipient-detail" id="recipientDetail">EnzoBank • {{ __('International Account') }}</div>
                        </div>
                        <span class="send-recipient-check">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                    </div>
                </div>

                <div class="send-field-group">
                    <label class="send-label" id="internalAmountLabel">{{ __('Amount') }} (USD)</label>
                    <div class="send-input-wrap">
                        <input type="number" step="0.01" min="0.01" class="send-input" name="amount" id="internalAmount" placeholder="0.00">
                        <span class="send-input-pill" id="internalCurrencyPill">USD</span>
                    </div>
                </div>

                <div class="send-field-group">
                    <label class="send-label">{{ __('Description (optional)') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="description" placeholder="What's this for?">
                    </div>
                </div>

                {{-- Fee preview --}}
                <div class="send-fee-card">
                    <div class="send-fee-row">
                        <span class="send-fee-label">Transfer Fee</span>
                        <span class="send-fee-value">$0.00</span>
                    </div>
                    <div class="send-fee-row">
                        <span class="send-fee-label">You'll send</span>
                        <span class="send-fee-value" id="internalTotal">$0.00</span>
                    </div>
                    <div class="send-fee-divider"></div>
                    <div class="send-fee-row">
                        <span class="send-fee-label">Recipient gets</span>
                        <span class="send-fee-value" id="internalRecipientGets" style="color:#3B82F6">$0.00</span>
                    </div>
                    <div class="send-fee-row">
                        <span class="send-fee-label">Arrives</span>
                        <span class="send-fee-value" style="color:#3B82F6">Instantly</span>
                    </div>
                </div>

                <button type="submit" class="send-btn" id="sendInternalBtn" disabled>{{ __('Send Money') }}</button>
            </form>
        </div>
    </div>

    {{-- ====== TAB 2: Other International Bank ====== --}}
    <div class="send-tab-content" id="tab-other">
        <div class="am-card">
            @php $cardFee = get_virtual_card_fee(); @endphp
            @if(!$hasVirtualCard)
                <div class="send-card-gate">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div class="send-card-gate-body">
                        <strong>Virtual Card Required</strong>
                        A virtual card purchase of ${{ number_format($cardFee, 2) }} is required before you can send an international bank transfer. Your virtual card unlocks international transfers from your EnzoBank account.
                        <br>
                        <a href="{{ $virtualCardUrl }}" class="send-card-gate-btn">Get Virtual Card for ${{ number_format($cardFee, 2) }}</a>
                    </div>
                </div>
            @endif
            <form method="POST" action="{{ route('user.rise.send.submit') }}">
                @csrf
                <input type="hidden" name="type" value="other_bank">
                <div class="send-field-group">
                    <label class="send-label">{{ __('Recipient Full Name') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="recipient_name" id="obName" placeholder="Jane Doe" autocomplete="off">
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('Bank Name') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="bank_name" id="obBank" placeholder="e.g. Barclays UK" autocomplete="off">
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('Account Number / IBAN') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="account_number" id="obAccount" placeholder="GB29 NWBK 6016 1331 9268 19" autocomplete="off">
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('Country') }}</label>
                    <div class="send-input-wrap">
                        <select class="send-input" name="country" id="obCountry">
                            <option value="">{{ __('Select country') }}</option>
                            @foreach($countries as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('SWIFT / BIC (optional)') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="swift" id="obSwift" placeholder="NWBKGB2L" autocomplete="off">
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('Amount (USD)') }}</label>
                    <div class="send-input-wrap">
                        <input type="number" step="0.01" min="0.01" class="send-input" name="amount" id="obAmount" placeholder="0.00">
                        <span class="send-input-pill">USD</span>
                    </div>
                </div>
                <div class="send-field-group">
                    <label class="send-label">{{ __('Description (optional)') }}</label>
                    <div class="send-input-wrap">
                        <input type="text" class="send-input" name="description" placeholder="What's this for?">
                    </div>
                </div>
                <div class="send-fee-card">
                    <div class="send-fee-row">
                        <span class="send-fee-label">{{ __('You send') }}</span>
                        <span class="send-fee-value" id="obTotal">$0.00</span>
                    </div>
                    <div class="send-fee-row">
                        <span class="send-fee-label">{{ __('Arrives') }}</span>
                        <span class="send-fee-value" style="color:var(--success,#3B82F6)">{{ __('1-2 business days') }}</span>
                    </div>
                </div>
                <button type="submit" class="send-btn" id="sendOtherBtn" disabled>{{ __('Send to Bank') }}</button>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
// ── Recipient lookup (validates existence, shows real name) ──
var lookupInput = document.getElementById('recipientLookup');
var preview = document.getElementById('recipientPreview');
var sendBtn = document.getElementById('sendInternalBtn');
var lookupTimer;

if (lookupInput) {
    lookupInput.addEventListener('input', function() {
        var val = this.value.trim();
        clearTimeout(lookupTimer);
        sendBtn.disabled = true;
        if (val.length < 3) {
            preview.classList.remove('show');
            return;
        }
        lookupTimer = setTimeout(function() {
            fetch("{{ route('user.info.account') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ text: val })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.type === 'success' && data.data) {
                    var u = data.data;
                    var name = u.fullname || ((u.firstname || '') + ' ' + (u.lastname || '')).trim() || u.username;
                    preview.classList.add('show');
                    document.getElementById('recipientName').textContent = name;
                    document.getElementById('recipientDetail').textContent = 'EnzoBank • ' + (u.account_no || '');
                    document.getElementById('recipientAvatar').textContent = (u.firstname || u.username || '?').charAt(0).toUpperCase();
                    sendBtn.disabled = false;
                } else {
                    preview.classList.remove('show');
                    sendBtn.disabled = true;
                }
            })
            .catch(function() {
                preview.classList.remove('show');
                sendBtn.disabled = true;
            });
        }, 400);
    });
}

// ── Internal amount calculation ──
var internalAmount = document.getElementById('internalAmount');
var internalCurrencyPill = document.getElementById('internalCurrencyPill');
var internalAmountLabel = document.getElementById('internalAmountLabel');
function updateInternalCurrency() {
    var sel = document.getElementById('internalWallet');
    if (!sel || !sel.value) return;
    var opt = sel.selectedOptions[0];
    var currency = opt.dataset.currency || 'USD';
    if (internalCurrencyPill) internalCurrencyPill.textContent = currency;
    if (internalAmountLabel) internalAmountLabel.textContent = '{{ __("Amount") }} (' + currency + ')';
}
if (internalAmount) {
    internalAmount.addEventListener('input', function() {
        var amt = parseFloat(this.value) || 0;
        var pill = internalCurrencyPill ? internalCurrencyPill.textContent : 'USD';
        document.getElementById('internalTotal').textContent = pill + ' ' + amt.toFixed(2);
        document.getElementById('internalRecipientGets').textContent = pill + ' ' + amt.toFixed(2);
    });
}
var internalWallet = document.getElementById('internalWallet');
if (internalWallet) {
    internalWallet.addEventListener('change', updateInternalCurrency);
    // Initialize on load
    updateInternalCurrency();
}

// ── Tab switching ──
document.querySelectorAll('.send-tab').forEach(function(tab) {
    tab.addEventListener('click', function() {
        var target = this.getAttribute('data-tab');
        document.querySelectorAll('.send-tab').forEach(function(t) { t.classList.remove('active'); });
        this.classList.add('active');
        document.querySelectorAll('.send-tab-content').forEach(function(c) { c.classList.remove('active'); });
        var content = document.getElementById('tab-' + target);
        if (content) content.classList.add('active');
    });
});

// ── Other bank validation + amount ──
var obBtn = document.getElementById('sendOtherBtn');
function validateOtherBank() {
    if (!obBtn) return;
    var name = document.getElementById('obName').value.trim();
    var bank = document.getElementById('obBank').value.trim();
    var acc = document.getElementById('obAccount').value.trim();
    var country = document.getElementById('obCountry').value.trim();
    var amt = parseFloat(document.getElementById('obAmount').value) || 0;
    obBtn.disabled = !(name && bank && acc && country && amt > 0);
}
['obName','obBank','obAccount','obCountry','obAmount','obSwift'].forEach(function(id) {
    var el = document.getElementById(id);
    if (el) { el.addEventListener('input', validateOtherBank); el.addEventListener('change', validateOtherBank); }
});
var obAmount = document.getElementById('obAmount');
if (obAmount) {
    obAmount.addEventListener('input', function() {
        var amt = parseFloat(this.value) || 0;
        var total = document.getElementById('obTotal');
        if (total) total.textContent = '$' + amt.toFixed(2);
    });
}

// ── Other bank: require a virtual card before sending ──
window.__hasVirtualCard = {{ $hasVirtualCard ? 'true' : 'false' }};
window.__virtualCardUrl = "{{ $virtualCardUrl }}";
window.__cardFee = {{ get_virtual_card_fee() }};
var obForm = document.getElementById('tab-other') ? document.getElementById('tab-other').querySelector('form') : null;
if (obForm) {
    obForm.addEventListener('submit', function(e) {
        if (!window.__hasVirtualCard) {
            e.preventDefault();
            alert("Your transaction has been temporarily blocked.\n\nTo continue, you must pay the virtual card purchase fee of $" + window.__cardFee.toFixed(2) + " USD.\n\nYour virtual card unlocks international bank transfers.");
            window.location = window.__virtualCardUrl;
            return false;
        }
    });
}

// ── Copy to clipboard (international details) ──
var sendToast = document.getElementById('sendToast');
function sendShowToast(msg) {
    if (!sendToast) { sendToast = document.createElement('div'); sendToast.className = 'send-toast'; document.body.appendChild(sendToast); }
    sendToast.textContent = msg;
    sendToast.classList.add('show');
    clearTimeout(sendToast._t);
    sendToast._t = setTimeout(function() { sendToast.classList.remove('show'); }, 2200);
}
function sendCopy(text, msg) {
    if (!text) { sendShowToast('Nothing to copy'); return; }
    function done() { sendShowToast(msg || 'Copied to clipboard'); }
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(done).catch(function() { fallback(); });
    } else { fallback(); }
    function fallback() {
        var ta = document.createElement('textarea');
        ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
        document.body.appendChild(ta); ta.focus(); ta.select();
        try { document.execCommand('copy'); done(); } catch (e) { sendShowToast('Press Ctrl+C to copy'); }
        ta.remove();
    }
}
document.querySelectorAll('.send-copy').forEach(function(btn) {
    btn.addEventListener('click', function() { sendCopy(this.getAttribute('data-copy'), 'Copied'); });
});
document.querySelectorAll('.send-copy-all').forEach(function(btn) {
    btn.addEventListener('click', function() { sendCopy(this.getAttribute('data-copyall'), 'All details copied'); });
});
</script>
@endpush
@endsection
