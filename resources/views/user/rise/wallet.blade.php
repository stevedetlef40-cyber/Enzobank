@extends('user.layouts.rise-master')

@push('css')
<style>
/* ── Subtitle + statement button ── */
.wl-subtitle { font-size: 13px; color: var(--text-muted); margin: 4px 0 0; }
.wl-stmt-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 16px;
    border-radius: 100px;
    border: 1.5px solid var(--accent);
    color: var(--accent);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s;
    white-space: nowrap;
}
.wl-stmt-btn:hover { background: var(--accent); color: #fff; }

/* ── Hero balance card ── */
.wl-hero {
    border-radius: 20px;
    padding: 22px 22px 24px;
    background: linear-gradient(135deg, #0B1F4D, #1E4FCC);
    color: #fff;
    box-shadow: 0 14px 34px rgba(11,31,77,0.38);
    position: relative;
    overflow: hidden;
}
.wl-hero::after {
    content: "";
    position: absolute;
    right: -40px; top: -40px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
}
.wl-hero-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    z-index: 1;
}
.wl-curr-switch {
    display: inline-flex;
    gap: 4px;
    background: rgba(255,255,255,0.16);
    border-radius: 100px;
    padding: 4px;
}
.wl-curr {
    border: none;
    background: transparent;
    color: rgba(255,255,255,0.8);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 6px 14px;
    border-radius: 100px;
    cursor: pointer;
    transition: all 0.15s;
}
.wl-curr.active { background: #fff; color: #1D4ED8; }
.wl-eye {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.18);
    display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer; color: #fff; user-select: none;
    transition: background 0.15s ease, transform 0.1s ease;
    flex-shrink: 0;
}
.wl-eye:hover { background: rgba(255,255,255,0.28); }
.wl-eye:active { transform: scale(0.92); }
.wl-eye svg { display: block; }
.wl-balance {
    position: relative; z-index: 1;
    margin-top: 22px;
    display: flex; align-items: baseline; gap: 2px;
    font-weight: 800;
    line-height: 1;
    transition: filter 0.2s ease;
}
.wl-balance-cur { font-size: 22px; font-weight: 700; opacity: 0.9; }
.wl-balance-int { font-size: 38px; letter-spacing: -1px; }
.wl-balance-dec { font-size: 22px; font-weight: 600; opacity: 0.85; }
.wl-balance.digits-hidden { filter: blur(8px); }
.wl-balance-label {
    position: relative; z-index: 1;
    margin-top: 10px;
    font-size: 12px;
    color: rgba(255,255,255,0.85);
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

/* ── Quick actions ── */
.wl-actions {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.wl-action {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 16px 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    transition: transform 0.15s, border-color 0.15s;
}
.wl-action:active { transform: scale(0.97); }
.wl-action-icon {
    width: 46px; height: 46px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
}
.wl-action-icon.add { background: #3B82F6; }
.wl-action-icon.send { background: #3B82F6; }
.wl-action-icon.out { background: #DC2626; }
.wl-action-icon.stmt { background: #7C3AED; }
.wl-action span { font-size: 12px; font-weight: 600; color: var(--text-secondary); }

/* ── Section ── */
.wl-section { }
.wl-section-head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 12px;
}
.wl-section-title { font-size: 16px; font-weight: 700; color: var(--text-primary); }
.wl-section-link {
    font-size: 13px; font-weight: 600; color: var(--accent);
    text-decoration: none;
}

/* ── Wallets overview ── */
.wl-wallet-list {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
}
.wl-wallet-row {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-color);
}
.wl-wallet-row:last-child { border-bottom: none; }
.wl-wallet-flag {
    width: 42px; height: 42px;
    border-radius: 12px;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.wl-wallet-info { flex: 1; min-width: 0; }
.wl-wallet-name { font-size: 14px; font-weight: 600; color: var(--text-primary); }
.wl-wallet-code { font-size: 12px; color: var(--text-muted); margin-top: 1px; }
.wl-wallet-bal { font-size: 15px; font-weight: 700; color: var(--text-primary); font-variant-numeric: tabular-nums; }

/* ── Transactions ── */
.wl-tx-list {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
}
.wl-tx-item {
    display: flex; align-items: center; gap: 12px;
    padding: 13px 16px;
    border-bottom: 1px solid var(--border-color);
}
.wl-tx-item:last-child { border-bottom: none; }
.wl-tx-icon {
    width: 40px; height: 40px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: #fff;
}
.wl-tx-icon.green { background: var(--success-bg); color: var(--success-text); }
.wl-tx-icon.red { background: var(--danger-bg); color: var(--danger-text); }
.wl-tx-icon.blue { background: var(--info-bg); color: var(--info); }
.wl-tx-info { flex: 1; min-width: 0; }
.wl-tx-name { font-size: 14px; font-weight: 600; color: var(--text-primary); }
.wl-tx-date { font-size: 12px; color: var(--text-muted); margin-top: 1px; }
.wl-tx-amount { font-size: 14px; font-weight: 700; font-variant-numeric: tabular-nums; }
.wl-tx-amount.positive { color: var(--success-text); }
.wl-tx-amount.negative { color: var(--danger-text); }

/* ── Empty ── */
.wl-empty {
    display: flex; flex-direction: column; align-items: center;
    padding: 44px 20px; text-align: center; gap: 8px;
}
.wl-empty-icon { color: var(--border-strong); margin-bottom: 6px; }
.wl-empty-title { font-size: 16px; font-weight: 700; color: var(--text-primary); }
.wl-empty-sub { font-size: 13px; color: var(--text-muted); }

@media (max-width: 430px) {
    .wl-actions { grid-template-columns: repeat(2, 1fr); }
    .wl-balance-int { font-size: 32px; }
}
</style>
@endpush

@section('content')

@php
    $usdWallet = $usd_wallet ?? \App\Models\UserWallet::auth()->whereHas('currency', fn($q) => $q->where('code', 'USD'))->first();
    $gbpWallet = $gbp_wallet ?? null;
    $eurWallet = $eur_wallet ?? null;

    function wlParts($balance) {
        $p = explode('.', number_format((float) $balance, 2));
        return ['int' => $p[0], 'dec' => $p[1] ?? '00'];
    }

    $wallets = [
        ['code' => 'USD', 'symbol' => '$', 'flag' => '🇺🇸', 'name' => 'US Dollar',     'balance' => $usdWallet ? $usdWallet->balance : 0],
        ['code' => 'GBP', 'symbol' => '£', 'flag' => '🇬🇧', 'name' => 'British Pound',  'balance' => $gbpWallet ? $gbpWallet->balance : 0],
        ['code' => 'EUR', 'symbol' => '€', 'flag' => '🇪🇺', 'name' => 'Euro',          'balance' => $eurWallet ? $eurWallet->balance : 0],
    ];
    foreach ($wallets as &$w) { $w = array_merge($w, wlParts($w['balance'])); }
    unset($w);

    $default_symbol = get_default_currency_symbol();

    global $creditTypes;
    $creditTypes = ['ADD-MONEY', 'TRANSFER-MONEY', 'BONUS', 'COMMISSION', 'Salary Disbursement', 'MOBILE-WALLET-TRANSFER'];
    function wlIsCredit($tx) {
        global $creditTypes;
        // Check attribute field first (SEND/RECEIVED)
        if (($tx->attribute ?? '') === 'RECEIVED') {
            return true;
        }
        // For wallet transfers, check if current user is the recipient (receiver_id stores wallet_id)
        if (in_array($tx->type ?? '', ['MOBILE-WALLET-TRANSFER', 'OWN-BANK-TRANSFER', 'OTHER-BANK-TRANSFER'])) {
            return ($tx->receiver_id ?? null) == auth()->user()->wallet?->id;
        }
        return in_array($tx->type ?? '', $creditTypes);
    }
    function wlTypeLabel($type) {
        $map = [
            'ADD-MONEY' => 'Deposit', 'MONEY-OUT' => 'Withdrawal', 'WITHDRAW' => 'Withdrawal',
            'BONUS' => 'Referral Bonus', 'COMMISSION' => 'Commission',
            'OWN-BANK-TRANSFER' => 'Own Transfer', 'OTHER-BANK-TRANSFER' => 'Bank Transfer',
            'TRANSFER-MONEY' => 'Transfer', 'MONEY-EXCHANGE' => 'Currency Exchange',
            'MAKE-PAYMENT' => 'Payment', 'VIRTUAL-CARD' => 'Virtual Card',
            'MOBILE-WALLET-TRANSFER' => 'Mobile Wallet', 'Salary Disbursement' => 'Salary',
        ];
        return $map[$type] ?? ucwords(str_replace(['-', '_'], ' ', strtolower($type)));
    }
@endphp

<div class="am-header">
    <div>
        <h1 class="am-header-title">{{ __('My Wallet') }}</h1>
        <p class="wl-subtitle">{{ __('Manage your balances across currencies') }}</p>
    </div>
    <a href="{{ route('user.statements.index') }}" class="wl-stmt-btn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        {{ __('Statement') }}
    </a>
</div>

<div class="am-body">

    <!-- Hero balance -->
    <div class="wl-hero" id="wlHero" data-wallets='@json($wallets)'>
        <div class="wl-hero-top">
            <div class="wl-curr-switch">
                @foreach($wallets as $w)
                <button class="wl-curr {{ $loop->first ? 'active' : '' }}" data-curr="{{ $w['code'] }}">{{ $w['code'] }}</button>
                @endforeach
            </div>
            <span class="wl-eye" id="wlEye" title="Show / hide balance" role="button" tabindex="0" aria-label="Show or hide balance">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            </span>
        </div>
        <div class="wl-balance" id="wlBalance">
            <span class="wl-balance-cur" id="wlCur">{{ $wallets[0]['symbol'] }}</span><span class="wl-balance-int" id="wlInt">{{ $wallets[0]['int'] }}</span><span class="wl-balance-dec" id="wlDec">.{{ $wallets[0]['dec'] }}</span>
        </div>
        <div class="wl-balance-label">{{ __('Available Balance') }}</div>
    </div>

    <!-- Quick actions -->
    <div class="wl-actions">
        <a href="{{ setRoute('user.add.money.index') }}" class="wl-action">
            <div class="wl-action-icon add">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
            <span>{{ __('Add Money') }}</span>
        </a>
        <a href="{{ route('user.rise.send') }}" class="wl-action">
            <div class="wl-action-icon send">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polyline points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </div>
            <span>{{ __('Send') }}</span>
        </a>
        <a href="{{ setRoute('user.money-out.index') }}" class="wl-action">
            <div class="wl-action-icon out">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
            </div>
            <span>{{ __('Withdraw') }}</span>
        </a>
        <a href="{{ route('user.statements.index') }}" class="wl-action">
            <div class="wl-action-icon stmt">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <span>{{ __('Statement') }}</span>
        </a>
    </div>

    <!-- Wallets overview -->
    <div class="wl-section">
        <div class="wl-section-head">
            <span class="wl-section-title">{{ __('Your Wallets') }}</span>
        </div>
        <div class="wl-wallet-list">
            @foreach($wallets as $w)
            <div class="wl-wallet-row">
                <div class="wl-wallet-flag">{{ $w['flag'] }}</div>
                <div class="wl-wallet-info">
                    <div class="wl-wallet-name">{{ $w['name'] }}</div>
                    <div class="wl-wallet-code">{{ $w['code'] }} • {{ __('Wallet') }}</div>
                </div>
                <div class="wl-wallet-bal">{{ $w['symbol'] }}{{ number_format($w['balance'], 2) }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent transactions -->
    <div class="wl-section">
        <div class="wl-section-head">
            <span class="wl-section-title">{{ __('Recent Transactions') }}</span>
            <a href="{{ setRoute('user.transactions.index') }}" class="wl-section-link">{{ __('See all') }}</a>
        </div>

        @if($transactions->count() > 0)
        <div class="wl-tx-list">
            @foreach($transactions->take(8) as $tx)
            @php
                $isCredit = wlIsCredit($tx);
                $iconClass = $tx->type === 'ADD-MONEY' ? 'green' : ($tx->type === 'MONEY-OUT' || $tx->type === 'WITHDRAW' ? 'red' : 'blue');
            @endphp
            <div class="wl-tx-item">
                <div class="wl-tx-icon {{ $iconClass }}">
                    @if($isCredit)
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    @else
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    @endif
                </div>
                <div class="wl-tx-info">
                    <div class="wl-tx-name">{{ wlTypeLabel($tx->type) }}</div>
                    <div class="wl-tx-date">{{ $tx->created_at->diffForHumans() }}</div>
                </div>
                <span class="wl-tx-amount {{ $isCredit ? 'positive' : 'negative' }}">
                    {{ $isCredit ? '+' : '-' }}{{ $default_symbol }}{{ number_format($tx->request_amount, 2) }}
                </span>
            </div>
            @endforeach
        </div>
        @else
        <div class="wl-empty">
            <div class="wl-empty-icon">
                <svg width="46" height="46" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="15" rx="2"/><path d="M3 10h18"/><path d="M7 15h2"/><path d="M11 15h6"/></svg>
            </div>
            <span class="wl-empty-title">{{ __('No transactions yet') }}</span>
            <span class="wl-empty-sub">{{ __('Your activity will appear here') }}</span>
        </div>
        @endif
    </div>

</div>
@endsection

@push('script')
<script>
(function(){
    var hero = document.getElementById('wlHero');
    if (!hero) return;
    var data = JSON.parse(hero.dataset.wallets || '[]');
    var map = {};
    data.forEach(function(w){ map[w.code] = w; });

    var curEl = document.getElementById('wlCur');
    var intEl = document.getElementById('wlInt');
    var decEl = document.getElementById('wlDec');
    var balanceEl = document.getElementById('wlBalance');
    var eye = document.getElementById('wlEye');

    var eyeOpen = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
    var eyeOff = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/></svg>';

    var STORE_CURR = 'enzobank_wallet_currency';
    var STORE_HIDDEN = 'enzobank_wallet_hidden';
    function lsGet(k){ try { return localStorage.getItem(k); } catch(e){ return null; } }
    function lsSet(k,v){ try { localStorage.setItem(k,v); } catch(e){} }

    function setCurrency(code, persist) {
        var w = map[code];
        if (!w) return;
        curEl.textContent = w.symbol;
        intEl.textContent = w.int;
        decEl.textContent = '.' + w.dec;
        document.querySelectorAll('.wl-curr').forEach(function(b){
            b.classList.toggle('active', b.dataset.curr === code);
        });
        if (persist) { lsSet(STORE_CURR, code); }
    }

    function setHidden(hidden, persist) {
        balanceEl.classList.toggle('digits-hidden', hidden);
        eye.innerHTML = hidden ? eyeOff : eyeOpen;
        if (persist) { lsSet(STORE_HIDDEN, hidden ? '1' : '0'); }
    }

    // Restore persisted state
    var savedCurr = lsGet(STORE_CURR);
    if (savedCurr && map[savedCurr]) {
        setCurrency(savedCurr, false);
    } else if (data.length) {
        setCurrency(data[0].code, false);
    }

    var savedHidden = lsGet(STORE_HIDDEN);
    setHidden(savedHidden === '1', false);

    document.querySelectorAll('.wl-curr').forEach(function(btn){
        btn.addEventListener('click', function(){
            setCurrency(this.dataset.curr, true);
        });
    });

    eye.addEventListener('click', function(){
        setHidden(!balanceEl.classList.contains('digits-hidden'), true);
    });
})();
</script>
@endpush
