@extends('user.layouts.rise-master')

@push('css')
<style>
.dash-welcome {
    padding: 0 0 2px;
    animation: fadeSlideDown 0.6s ease-out;
}
.dash-page.dash-home {
    padding-top: 4px;
}
.dash-welcome-greeting {
    font-size: 14px;
    color: #64748B;
    font-weight: 500;
    letter-spacing: 0.3px;
    text-transform: uppercase;
    margin-bottom: 2px;
}
.dash-welcome-name {
    font-size: 26px;
    font-weight: 800;
    background: linear-gradient(135deg, #F1F5F9 0%, #3B82F6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.2;
    animation: shimmer 3s ease-in-out infinite;
    background-size: 200% 100%;
}
.dash-welcome-tagline {
    font-size: 13px;
    color: #64748B;
    margin-top: 2px;
    font-weight: 400;
}
@keyframes fadeSlideDown {
    0% { opacity: 0; transform: translateY(-12px); }
    100% { opacity: 1; transform: translateY(0); }
}
@keyframes shimmer {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}
@media (prefers-color-scheme: light) {
    .dash-welcome-greeting { color: #64748B; }
    .dash-welcome-name {
        background: linear-gradient(135deg, #0F172A 0%, #3B82F6 100%);
        -webkit-background-clip: text;
        background-clip: text;
    }
    .dash-welcome-tagline { color: #64748B; }
}
</style>
@endpush

@section('content')
@php
$user = auth()->user();
$wallet = $wallet ?? \App\Models\UserWallet::auth()->first();
$usdWallet = $usd_wallet ?? $wallet;
$gbpWallet = $gbp_wallet ?? null;
$eurWallet = $eur_wallet ?? null;
$usdBalance = $usdWallet ? $usdWallet->balance : 0;
$gbpBalance = $gbpWallet ? $gbpWallet->balance : 0;
$eurBalance = $eurWallet ? $eurWallet->balance : 0;
$balance = $usdBalance;
$transactions = $transactions ?? collect([]);
$accountNo = $user->account_no ?? '0000000000';
@endphp

<div class="dash-page dash-home">

    <!--===== WELCOME MESSAGE =====-->
    <div class="dash-welcome">
        <div class="dash-welcome-greeting">{{ __('Welcome back') }}</div>
        <div class="dash-welcome-name">{{ $user->fullname ?? $user->username }}</div>
        <div class="dash-welcome-tagline">{{ __('Your financial hub, at a glance') }}</div>
    </div>

    <!--===== BALANCE CARD =====-->
    <div class="dash-balance-card">
        <div class="dash-balance-top">
            <div class="dash-balance-currency-tabs">
                <button class="dash-currency-tab active" data-currency="usd">
                    <span class="dash-currency-flag">🇺🇸</span> USD
                </button>
                <button class="dash-currency-tab" data-currency="eur">
                    <span class="dash-currency-flag">🇪🇺</span> EUR
                </button>
                <button class="dash-currency-tab" data-currency="gbp">
                    <span class="dash-currency-flag">🇬🇧</span> GBP
                </button>
            </div>
            <div class="dash-balance-actions-top">
                <button class="dash-copy-btn" onclick="copyAccountNo('{{ $accountNo }}', this)" aria-label="Copy account number">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                </button>
                <button class="dash-eye-btn" id="dashBalanceToggle" aria-label="Toggle balance visibility">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
        </div>
        <div class="dash-balance-account-row">
            <span class="dash-balance-account-label">ACCOUNT</span>
            <span class="dash-balance-account-no">****{{ substr($accountNo, -4) }}</span>
        </div>
        <div class="dash-balance-amount-row">
            <span class="dash-balance-label">TOTAL BALANCE</span>
            <span class="dash-balance-amount" id="dashBalanceAmount" data-usd="{{ number_format($usdBalance, 2) }}" data-gbp="{{ number_format($gbpBalance, 2) }}" data-eur="{{ number_format($eurBalance, 2) }}">${{ number_format($balance, 2) }}</span>
        </div>
    </div>

    <!--===== VIRTUAL CARD PREVIEW =====-->
    <div class="dash-card-preview">
        <div class="dash-card-preview-inner">
            <div class="dash-card-preview-chip">
                <svg width="32" height="24" viewBox="0 0 40 30" fill="none"><rect x="0.5" y="0.5" width="39" height="29" rx="4.5" fill="#F59E0B" fill-opacity="0.35"/><rect x="3" y="3" width="12" height="9" rx="2" fill="#F59E0B" fill-opacity="0.7"/><rect x="3" y="17" width="12" height="9" rx="2" fill="#F59E0B" fill-opacity="0.7"/><rect x="18" y="3" width="18" height="23" rx="3" fill="#F59E0B" fill-opacity="0.45"/></svg>
            </div>
            <div class="dash-card-preview-number">**** **** **** 4242</div>
            <div class="dash-card-preview-bottom">
                <div class="dash-card-preview-holder">
                    <span class="dash-card-preview-label">CARD HOLDER</span>
                    <span class="dash-card-preview-name">{{ strtoupper($user->firstname ?? 'USER') }} {{ strtoupper($user->lastname ?? '') }}</span>
                </div>
                <div class="dash-card-preview-expiry">
                    <span class="dash-card-preview-label">EXPIRES</span>
                    <span class="dash-card-preview-date">12/28</span>
                </div>
            </div>
            <a href="#" class="dash-card-preview-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
    </div>

    <!--===== REFERRAL BANNER =====-->
    <div class="dash-referral-banner">
        <div class="dash-referral-banner-content">
            <div class="dash-referral-banner-text">
                <span class="dash-referral-banner-title">Refer & Earn</span>
                <span class="dash-referral-banner-sub">Get $50 for each friend you invite</span>
            </div>
            <a href="{{ route('user.rise.refer') }}" class="dash-referral-banner-btn">Invite</a>
        </div>
    </div>

    <!--===== ACTION PILLS (Main Services) =====-->
    <div class="dash-actions-row">
        <a href="{{ setRoute('user.add.money.index') }}" class="dash-action-pill">
            <div class="dash-action-icon dash-action-icon-blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
            <span class="dash-action-label">Add Money</span>
        </a>
        <a href="{{ route('user.rise.send') }}" class="dash-action-pill">
            <div class="dash-action-icon dash-action-icon-yellow">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/></svg>
            </div>
            <span class="dash-action-label">Transfer</span>
        </a>
        <a href="{{ setRoute('user.investments.offers') }}" class="dash-action-pill">
            <div class="dash-action-icon dash-action-icon-purple">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
            </div>
            <span class="dash-action-label">Invest</span>
        </a>
        <a href="{{ setRoute('user.strowallet.virtual.card.index') }}" class="dash-action-pill">
            <div class="dash-action-icon dash-action-icon-teal">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/><line x1="5" y1="15" x2="9" y2="15"/></svg>
            </div>
            <span class="dash-action-label">Cards</span>
        </a>
    </div>

    <!--===== ADDITIONAL SERVICES =====-->
    <div class="dash-services-row">
        <a href="{{ setRoute('user.money-out.index') }}" class="dash-service-pill">
            <div class="dash-service-icon dash-service-icon-red">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <span class="dash-service-label">Withdraw</span>
        </a>
        <a href="{{ setRoute('user.loans.index') }}" class="dash-service-pill">
            <div class="dash-service-icon dash-service-icon-amber">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <span class="dash-service-label">Loans</span>
        </a>
    </div>

    <!--===== STAT CARDS =====-->
    <div class="dash-stats-row">
        <div class="dash-stat-card dash-stat-in">
            <div class="dash-stat-icon-wrap dash-stat-icon-green">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
            <div class="dash-stat-info">
                <span class="dash-stat-label">Money In</span>
                <span class="dash-stat-value">${{ number_format($transactions->whereIn('type', ['ADD-MONEY', 'TRANSFER-MONEY'])->sum('request_amount'), 2) }}</span>
            </div>
        </div>
        <div class="dash-stat-card dash-stat-out">
            <div class="dash-stat-icon-wrap dash-stat-icon-red">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
            </div>
            <div class="dash-stat-info">
                <span class="dash-stat-label">Money Out</span>
                <span class="dash-stat-value">${{ number_format($transactions->whereIn('type', ['MONEY-OUT', 'TRANSFER-MONEY'])->sum('request_amount'), 2) }}</span>
            </div>
        </div>
    </div>

    <!--===== INVEST & GROW =====-->
    <div class="dash-section-header">
        <span class="dash-section-title">Invest & Grow</span>
        <a href="{{ setRoute('user.rise.invest') }}" class="dash-section-link">See all</a>
    </div>
    <div class="dash-invest-scroll">
        <div class="dash-invest-card">
            <div class="dash-invest-badge">★ USD</div>
            <div class="dash-invest-name">Growth Plan</div>
            <div class="dash-invest-rate">10% /yr</div>
            <div class="dash-invest-duration">3-12 months</div>
            <a href="{{ setRoute('user.rise.invest') }}" class="dash-invest-btn">Invest Now</a>
        </div>
        <div class="dash-invest-card">
            <div class="dash-invest-badge">★ USD</div>
            <div class="dash-invest-name">Premium Plus</div>
            <div class="dash-invest-rate">15% /yr</div>
            <div class="dash-invest-duration">6-24 months</div>
            <a href="{{ setRoute('user.rise.invest') }}" class="dash-invest-btn">Invest Now</a>
        </div>
        <div class="dash-invest-card">
            <div class="dash-invest-badge">★ GBP</div>
            <div class="dash-invest-name">Sterling Vault</div>
            <div class="dash-invest-rate">23% /yr</div>
            <div class="dash-invest-duration">12 months</div>
            <a href="{{ setRoute('user.rise.invest') }}" class="dash-invest-btn">Invest Now</a>
        </div>
    </div>

    <!--===== RECENT TRANSACTIONS =====-->
    <div class="dash-section-header">
        <span class="dash-section-title">Recent Transactions</span>
        <a href="{{ setRoute('user.transactions.index') }}" class="dash-section-link">View all</a>
    </div>
    <div class="dash-tx-list">
        @forelse($transactions->take(5) as $tx)
        @php
            $txDetails = is_string($tx->details) ? json_decode($tx->details) : ($tx->details ?? null);
            $isReceived = ($tx->attribute ?? '') === 'RECEIVED' || (in_array($tx->type ?? '', ['ADD-MONEY', 'TRANSFER-MONEY']) && ($tx->receiver_id ?? null) == auth()->id());
            $isCredit = $isReceived;
            if ($tx->type === 'MOBILE-WALLET-TRANSFER' && $txDetails) {
                if ($isReceived) {
                    $txLabel = 'From: ' . ($txDetails->sender_name ?? 'Someone');
                } else {
                    $txLabel = 'To: ' . ($txDetails->receiver_name ?? 'Someone');
                }
            } else {
                $txLabel = $tx->type ?? 'Transaction';
            }
        @endphp
        <div class="dash-tx-item">
            <div class="dash-tx-icon {{ $isCredit ? 'dash-tx-icon-green' : 'dash-tx-icon-red' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="{{ $isCredit ? '23 6 13.5 15.5 8.5 10.5 1 18' : '23 18 13.5 8.5 8.5 13.5 1 6' }}"/>
                </svg>
            </div>
            <div class="dash-tx-info">
                <span class="dash-tx-name">{{ $txLabel }}</span>
                <span class="dash-tx-date">{{ $tx->created_at ? $tx->created_at->diffForHumans() : '' }}</span>
            </div>
            <span class="dash-tx-amount {{ $isCredit ? 'dash-tx-positive' : 'dash-tx-negative' }}">{{ $isCredit ? '+' : '-' }}${{ number_format($tx->request_amount ?? 0, 2) }}</span>
        </div>
        @empty
        <div class="dash-tx-empty">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            <span>No transactions yet</span>
        </div>
        @endforelse
    </div>

    <!--===== CONTACT US =====-->
    <a href="{{ setRoute('frontend.contact') }}" class="dash-contact-row">
        <div class="dash-contact-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="dash-contact-text">
            <span class="dash-contact-title">Contact Us</span>
            <span class="dash-contact-sub">We're here to help 24/7</span>
        </div>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </a>

</div>
@endsection

@push("script")
<script>
(function(){
    // --- Persisted state keys ---
    var STORAGE_KEY_CURRENCY = 'dash_currency';
    var STORAGE_KEY_HIDDEN = 'dash_hidden';

    var currencySymbols = { usd: '$', eur: '€', gbp: '£' };
    var toggleBtn = document.getElementById('dashBalanceToggle');
    var balanceEl = document.getElementById('dashBalanceAmount');
    var openEye = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
    var closedEye = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';

    // --- Balance visibility toggle (persisted) ---
    if (toggleBtn && balanceEl) {
        var savedHidden = localStorage.getItem(STORAGE_KEY_HIDDEN);
        if (savedHidden === 'true') {
            balanceEl.classList.add('dash-balance-hidden');
            toggleBtn.querySelector('svg')?.outerHTML !== undefined && (toggleBtn.innerHTML = closedEye);
        }
        toggleBtn.addEventListener('click', function() {
            var isHidden = balanceEl.classList.toggle('dash-balance-hidden');
            localStorage.setItem(STORAGE_KEY_HIDDEN, isHidden ? 'true' : 'false');
            this.innerHTML = isHidden ? closedEye : openEye;
        });
    }

    // --- Currency tab switching (persisted) ---
    var tabs = document.querySelectorAll('.dash-currency-tab');
    if (tabs.length && balanceEl) {
        // Restore saved currency on load
        var savedCurrency = localStorage.getItem(STORAGE_KEY_CURRENCY);
        if (savedCurrency) {
            var savedTab = document.querySelector('.dash-currency-tab[data-currency="' + savedCurrency + '"]');
            if (savedTab) {
                tabs.forEach(function(t) { t.classList.remove('active'); });
                savedTab.classList.add('active');
                var symbol = currencySymbols[savedCurrency] || '$';
                var stored = balanceEl.getAttribute('data-' + savedCurrency);
                if (stored) balanceEl.textContent = symbol + stored;
            }
        }

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var currency = this.getAttribute('data-currency');
                if (!currency) return;
                tabs.forEach(function(t) { t.classList.remove('active'); });
                this.classList.add('active');
                localStorage.setItem(STORAGE_KEY_CURRENCY, currency);
                var symbol = currencySymbols[currency] || '$';
                var stored = balanceEl.getAttribute('data-' + currency);
                if (stored) {
                    balanceEl.classList.add('currency-switching');
                    setTimeout(function() {
                        balanceEl.textContent = symbol + stored;
                        balanceEl.classList.remove('currency-switching');
                    }, 120);
                }
            });
        });
    }

    // Copy account number helper
    window.copyAccountNo = function(accountNo, btn) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(accountNo).then(function() {
                var orig = btn.innerHTML;
                btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
                setTimeout(function() { btn.innerHTML = orig; }, 1500);
            });
        }
    };

    // Entrance animation
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('dash-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.dash-balance-card, .dash-card-preview, .dash-referral-banner, .dash-action-pill, .dash-service-pill, .dash-stat-card, .dash-invest-card, .dash-tx-item, .dash-contact-row, .dash-section-header').forEach(function(el) {
        el.classList.add('dash-fade-in');
        observer.observe(el);
    });
})();
</script>
@endpush
