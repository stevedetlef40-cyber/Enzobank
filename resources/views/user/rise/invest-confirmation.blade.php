@extends('user.layouts.rise-master')

@section('content')
@php
$investment = $investment ?? null;
@endphp

<div class="am-body invest-flow" style="padding-top:60px;display:flex;flex-direction:column;align-items:center;gap:24px;">
    <!-- Success Animation -->
    <div style="width:80px;height:80px;border-radius:50%;background:var(--inv-success-bg, rgba(16,185,129,0.1));display:flex;align-items:center;justify-content:center;animation: scaleUp 0.5s ease-out;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--inv-success-text, #059669)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation: drawCheck 0.6s ease-out 0.2s forwards;stroke-dasharray:50;stroke-dashoffset:50;">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
    </div>
    <style>
        @keyframes scaleUp { 0%{transform:scale(0)} 100%{transform:scale(1)} }
        @keyframes drawCheck { to{stroke-dashoffset:0} }
    </style>

    <div style="text-align:center;">
        <div style="font-size:22px;font-weight:700;">Investment Submitted!</div>
        <div class="ip-text-muted" style="font-size:14px;margin-top:8px;">Your deposit is under review. You'll be notified once confirmed.</div>
    </div>

    <!-- Investment Summary -->
    <div class="am-card" style="width:100%;">
        <div class="am-card-title">Investment Summary</div>
        <div class="am-preview-row"><span class="am-preview-label">💼 Plan</span><span class="am-preview-value">{{ $investment->plan->name ?? '-' }}</span></div>
        <div class="am-preview-row"><span class="am-preview-label">💰 Amount</span><span class="am-preview-value">${{ number_format($investment->amount ?? 0, 2) }}</span></div>
        <div class="am-preview-row"><span class="am-preview-label">📈 Expected ROI</span><span class="am-preview-value">{{ $investment->plan->roi_percent ?? 0 }}%</span></div>
        <div class="am-preview-row"><span class="am-preview-label">📅 Duration</span><span class="am-preview-value">{{ $investment->plan->duration_days ?? 0 }} days</span></div>
        <div class="am-preview-row"><span class="am-preview-label">🔄 Payment via</span><span class="am-preview-value">{{ $investment->payment_method ?? '-' }}</span></div>
        <div class="am-preview-row"><span class="am-preview-label">✅ Expected Return</span><span class="am-preview-value ip-text-green">${{ number_format($investment->expected_return ?? 0, 2) }}</span></div>
        <div class="am-preview-row"><span class="am-preview-label">📊 Status</span><span class="am-preview-value"><span class="ip-status ip-status-pending">{{ ucfirst($investment->status ?? 'pending') }}</span></span></div>
    </div>

    <!-- Action Buttons -->
    <div style="width:100%;display:flex;flex-direction:column;gap:10px;">
        <a href="{{ route('user.invest.portfolio') }}" class="am-btn" style="border-radius:100px;">View My Investments</a>
        <a href="{{ route('user.invest.new') }}" class="vc-action-btn" style="display:flex;align-items:center;justify-content:center;padding:14px;border-radius:100px;">Make Another Investment</a>
    </div>
</div>
@endsection
