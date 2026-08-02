@extends('user.layouts.rise-master')

@push('css')
<style>
.vc-locked { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh; padding: 40px 20px; text-align: center; }
.vc-locked-icon { font-size: 72px; margin-bottom: 24px; opacity: 0.6; }
.vc-locked h2 { font-size: 24px; font-weight: 700; color: #fff; margin-bottom: 8px; }
.vc-locked-divider { width: 40px; height: 3px; background: linear-gradient(135deg, #3B82F6, #2563EB); border-radius: 2px; margin: 16px auto 20px; }
.vc-locked p { font-size: 14px; color: #94A3B8; line-height: 1.7; max-width: 400px; margin: 0 auto 8px; }
.vc-locked-warning { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #F59E0B; background: rgba(245,158,11,0.1); padding: 10px 16px; border-radius: 8px; margin: 16px 0 24px; }
.vc-locked-btn { display: inline-flex; align-items: center; gap: 8px; padding: 14px 32px; background: linear-gradient(135deg, #3B82F6, #2563EB); color: #fff; font-size: 15px; font-weight: 600; border-radius: 999px; text-decoration: none; transition: all 0.2s; }
.vc-locked-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(59,130,246,0.3); }
.vc-locked-sub { font-size: 12px; color: #64748B; margin-top: 20px; }
.vc-locked-card-bg { position: relative; width: 280px; height: 175px; margin: 0 auto 32px; }
.vc-locked-card-bg .fake-card { width: 100%; height: 100%; border-radius: 16px; background: linear-gradient(135deg, #0B0B0F, #161622); border: 1px solid rgba(255,255,255,0.06); filter: blur(3px) grayscale(0.5); opacity: 0.4; display: flex; align-items: center; justify-content: center; }
</style>
@endpush

@php
    $cryptoEnabled = optional(auth()->user())->crypto_status;
    $cardFee = get_virtual_card_fee();
@endphp
@section('content')
<div class="vc-locked">
    <div class="vc-locked-card-bg">
        <div class="fake-card">💳</div>
    </div>
    <div class="vc-locked-icon">🔒</div>
    <h2>Virtual Card Locked</h2>
    <div class="vc-locked-divider"></div>
    @if($cryptoEnabled)
        <p>To activate your virtual card, you must make a personal crypto deposit of at least ${{ $cardFee }}.</p>
        <div class="vc-locked-warning">
            ⚠️ Internal transfers do not qualify for card activation.
        </div>
        <a href="{{ route('user.crypto.deposit.index') }}" class="vc-locked-btn">Make a Deposit &rarr;</a>
        <p class="vc-locked-sub">Already deposited? Deposits are reviewed within 1-3 hours.</p>
    @else
        <p>Crypto deposits are currently disabled for your account, so card activation via deposit is not required.</p>
        <div class="vc-locked-warning">
            ⚠️ Your virtual card feature is managed by an administrator. Please contact support if you need access.
        </div>
        <a href="{{ route('user.rise.home') }}" class="vc-locked-btn">Back to Home</a>
    @endif
</div>
@endsection
