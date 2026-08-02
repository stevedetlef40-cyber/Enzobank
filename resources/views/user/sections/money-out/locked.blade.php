@extends('user.layouts.rise-master')

@push('css')
<style>
.mo-locked { max-width: 520px; margin: 0 auto; padding: 32px 16px 40px; text-align: center; }
.mo-locked-icon {
    width: 84px; height: 84px; border-radius: 50%;
    background: var(--accent-soft); display: flex; align-items: center; justify-content: center;
    font-size: 40px; margin: 0 auto 20px;
}
.mo-locked h2 { font-size: 22px; font-weight: 700; color: var(--text-primary); margin-bottom: 10px; }
.mo-locked h2 span { color: var(--accent); }
.mo-locked-sub { font-size: 14px; color: var(--text-secondary); line-height: 1.65; margin-bottom: 22px; }

.mo-locked-progress { margin-bottom: 22px; text-align: left; }
.mo-locked-progress-head { display: flex; justify-content: space-between; font-size: 13px; color: var(--text-secondary); margin-bottom: 8px; }
.mo-locked-progress-head strong { color: var(--text-primary); }
.mo-locked-bar { height: 10px; border-radius: 999px; background: var(--bg-elevated); overflow: hidden; border: 1px solid var(--border-color); }
.mo-locked-bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(135deg, var(--accent), #2563EB); width: 0; transition: width 0.8s ease-out; }

.mo-locked-reasons { list-style: none; padding: 0; margin: 0 0 24px; text-align: left; display: inline-block; }
.mo-locked-reasons li { display: flex; align-items: center; gap: 10px; font-size: 14px; color: var(--text-secondary); padding: 7px 0; }
.mo-locked-reasons .check { color: var(--success); font-weight: 700; }
.mo-locked-reasons .cross { color: var(--danger); font-weight: 700; }

.mo-locked-warning {
    display: flex; align-items: flex-start; gap: 10px; text-align: left;
    background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);
    border-radius: 12px; padding: 14px 16px; margin-bottom: 24px; font-size: 13px; color: #FCA5A5; line-height: 1.55;
}
.mo-locked-warning svg { flex-shrink: 0; margin-top: 2px; }

.mo-locked-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.mo-locked-btn {
    display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px;
    background: var(--accent); color: var(--text-on-accent); font-size: 15px; font-weight: 600;
    border-radius: 999px; text-decoration: none; transition: opacity 0.2s, transform 0.2s;
}
.mo-locked-btn:hover { opacity: 0.92; transform: translateY(-1px); }
.mo-locked-btn-ghost { background: transparent; border: 1px solid var(--border-strong); color: var(--text-secondary); }
.mo-locked-btn-ghost:hover { border-color: var(--accent); color: var(--accent); background: transparent; }
</style>
@endpush

@php
    $isReferred   = !is_null($user->referral_id);
    $qualifying   = (bool) $user->has_qualifying_deposit;
    $referralGoal = 600;
    $deposited    = (float) ($total_deposits ?? 0);
    $pct          = $isReferred ? min(100, round($deposited / $referralGoal * 100)) : ($qualifying ? 100 : 0);
@endphp

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ __('Withdraw') }}</h1>
</div>
<div class="am-body">
    <div class="mo-locked">
        <div class="mo-locked-icon">🔒</div>
        <h2>Withdrawal <span>Locked</span></h2>
        <p class="mo-locked-sub">
            @if($user->crypto_status)
                For your security and to meet anti-money-laundering compliance, withdrawals are
                enabled only after you fund your account with a personal crypto deposit.
            @else
                Crypto deposits are currently disabled for your account, so the crypto-deposit
                requirement does not apply. Withdrawals are available once any other account
                checks are complete.
            @endif
        </p>

        @if($isReferred)
            <div class="mo-locked-progress">
                <div class="mo-locked-progress-head">
                    <span>Referral funding progress</span>
                    <strong>${{ number_format($deposited, 2) }} / ${{ number_format($referralGoal, 2) }}</strong>
                </div>
                <div class="mo-locked-bar">
                    <div class="mo-locked-bar-fill" style="width:{{ $pct }}%"></div>
                </div>
            </div>
            <div class="mo-locked-warning">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>You joined using a referral link, so withdrawals unlock once you have deposited a total of <strong>${{ number_format($referralGoal, 2) }}</strong> in crypto. Make a deposit below to continue.</span>
            </div>
        @endif

        <ul class="mo-locked-reasons">
            <li><span class="check">✓</span> Account verification</li>
            <li><span class="check">✓</span> Anti-money-laundering compliance</li>
            <li><span class="check">✓</span> Platform security</li>
            <li><span class="cross">✗</span> Funds received from transfers do not qualify.</li>
        </ul>

        <div class="mo-locked-actions">
            @if($user->crypto_status)
                <a href="{{ route('user.crypto.deposit.index') }}" class="mo-locked-btn">Make a Deposit &rarr;</a>
            @endif
            <a href="{{ route('user.rise.home') }}" class="mo-locked-btn mo-locked-btn-ghost">Back to Home</a>
        </div>
    </div>
</div>
@endsection
