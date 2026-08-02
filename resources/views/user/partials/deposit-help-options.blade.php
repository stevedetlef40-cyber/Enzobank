@push('css')
<style>
/* Deposit help options — professional banking style, theme-aware */
.dh-section { margin-top: 28px; }
.dh-head { margin-bottom: 14px; }
.dh-head-title { font-size: 16px; font-weight: 800; color: var(--text-primary); letter-spacing: 0.2px; }
.dh-head-sub { font-size: 12.5px; color: var(--text-secondary); margin-top: 4px; line-height: 1.5; }
.dh-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
.dh-card {
    position: relative;
    display: flex; flex-direction: column; gap: 10px;
    padding: 18px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    box-shadow: var(--card-shadow);
    transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
}
.dh-card:hover { transform: translateY(-2px); border-color: var(--border-strong); }
.dh-card-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.dh-icon {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.dh-icon--bank   { background: rgba(59,130,246,0.12); color: #3B82F6; }
.dh-icon--crypto { background: rgba(245,158,11,0.12); color: #F59E0B; }
.dh-icon--wallet { background: rgba(59,130,246,0.12); color: #3B82F6; }
.dh-icon--chat   { background: rgba(139,92,246,0.12); color: #8B5CF6; }
.dh-badge {
    font-size: 10px; font-weight: 800; letter-spacing: 0.6px; text-transform: uppercase;
    padding: 4px 9px; border-radius: 999px;
    background: var(--success-bg); color: var(--success-text); border: 1px solid var(--success);
}
.dh-card-title { font-size: 15px; font-weight: 700; color: var(--text-primary); line-height: 1.35; }
.dh-card-desc { font-size: 12.5px; color: var(--text-secondary); line-height: 1.55; flex: 1; }
.dh-cta {
    display: inline-flex; align-items: center; gap: 8px;
    width: 100%; justify-content: center;
    padding: 12px 14px; border-radius: 999px;
    font-size: 13px; font-weight: 700; text-decoration: none;
    transition: all 0.15s ease; border: 1.5px solid transparent;
}
.dh-cta i { font-size: 17px; }
.dh-cta--wa { background: #3B82F6; color: #fff; }
.dh-cta--wa:hover { background: #1D4ED8; color: #fff; }
.dh-cta--plain { background: var(--bg-secondary); color: var(--text-primary); border-color: var(--border-strong); }
.dh-cta--plain:hover { border-color: var(--accent); color: var(--accent); }
.dh-cta-arrow { margin-left: auto; }

/* Light theme overrides */
[data-theme="light"] .dh-card:hover { border-color: #CBD5E1; box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
[data-theme="light"] .dh-badge { background: #DBEAFE; color: #1E40AF; border-color: #93C5FD; }
[data-theme="light"] .dh-cta--plain { background: #F1F5F9; color: #334155; border-color: #CBD5E1; }
[data-theme="light"] .dh-cta--plain:hover { border-color: #3B82F6; color: #2563EB; }

@media (max-width: 640px) {
    .dh-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@php
$depositHelpIbanMessage = 'Hello EnzoBank Support, I would like to deposit funds into my account using an international bank transfer (IBAN/SWIFT) from my bank. Please share the required wire details and instructions.';
$depositHelpWalletMessage = 'Hello EnzoBank Support, I don\'t have a crypto wallet and I\'m not sure how to send crypto. Could you please guide me step by step?';
$depositHelpOtherMessage = 'Hello EnzoBank Support, I have a question about deposits. Could you help me?';
$depositHelpCryptoAnchor = $depositHelpCryptoAnchor ?? '#cryptoDepositForm';
@endphp

<div class="dh-section" id="depositHelp">
    <div class="dh-head">
        <div class="dh-head-title">{{ __('Need help depositing?') }}</div>
        <div class="dh-head-sub">{{ __('Choose how you would like to fund your account. Our team is one message away.') }}</div>
    </div>

    <div class="dh-grid">
        {{-- International bank transfer (IBAN/SWIFT) --}}
        <div class="dh-card">
            <div class="dh-card-top">
                <div class="dh-icon dh-icon--bank">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="21" x2="21" y2="21"/><line x1="4" y1="10" x2="5" y2="14"/><line x1="10" y1="10" x2="11" y2="14"/><line x1="16" y1="10" x2="17" y2="14"/><line x1="22" y1="10" x2="21" y2="14"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M12 3 2 6h20L12 3z"/></svg>
                </div>
                <span class="dh-badge">{{ __('SWIFT / IBAN') }}</span>
            </div>
            <div class="dh-card-title">{{ __('Deposit via International Bank Transfer') }}</div>
            <div class="dh-card-desc">{{ __('You can fund your account by sending money from your international bank account using your account number (IBAN/SWIFT). Our support team will provide the exact wire details.') }}</div>
            <a href="{{ support_whatsapp_link($depositHelpIbanMessage) }}" target="_blank" rel="noopener noreferrer" class="dh-cta dh-cta--wa">
                <i class="lab la-whatsapp"></i>
                <span>{{ __('Chat for Wire Details') }}</span>
                <svg class="dh-cta-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        {{-- Cryptocurrency deposit --}}
        <div class="dh-card">
            <div class="dh-card-top">
                <div class="dh-icon dh-icon--crypto">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M8 8h6a2 2 0 0 1 0 4H8h6a2 2 0 0 1 0 4H8z"/></svg>
                </div>
                <span class="dh-badge">{{ __('INSTANT') }}</span>
            </div>
            <div class="dh-card-title">{{ __('Deposit with Cryptocurrency') }}</div>
            <div class="dh-card-desc">{{ __('Fund your account instantly with Bitcoin, Ethereum, USDT and other supported coins using the crypto deposit form above.') }}</div>
            <a href="{{ $depositHelpCryptoAnchor }}" class="dh-cta dh-cta--plain">
                <span>{{ __('Start Crypto Deposit') }}</span>
                <svg class="dh-cta-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        {{-- No crypto wallet --}}
        <div class="dh-card">
            <div class="dh-card-top">
                <div class="dh-icon dh-icon--wallet">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                </div>
                <span class="dh-badge">{{ __('GUIDED') }}</span>
            </div>
            <div class="dh-card-title">{{ __("I don't have a crypto wallet") }}</div>
            <div class="dh-card-desc">{{ __('No problem. Our team will walk you through setting up a wallet and sending your first deposit, step by step.') }}</div>
            <a href="{{ support_whatsapp_link($depositHelpWalletMessage) }}" target="_blank" rel="noopener noreferrer" class="dh-cta dh-cta--wa">
                <i class="lab la-whatsapp"></i>
                <span>{{ __('Get Step-by-Step Help') }}</span>
                <svg class="dh-cta-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        {{-- Other questions --}}
        <div class="dh-card">
            <div class="dh-card-top">
                <div class="dh-icon dh-icon--chat">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <span class="dh-badge">{{ __('SUPPORT') }}</span>
            </div>
            <div class="dh-card-title">{{ __('Another question about deposits') }}</div>
            <div class="dh-card-desc">{{ __('Ask us anything about deposit limits, fees, processing times or which methods are available to you.') }}</div>
            <a href="{{ support_whatsapp_link($depositHelpOtherMessage) }}" target="_blank" rel="noopener noreferrer" class="dh-cta dh-cta--wa">
                <i class="lab la-whatsapp"></i>
                <span>{{ __('Chat with Support') }}</span>
                <svg class="dh-cta-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</div>
