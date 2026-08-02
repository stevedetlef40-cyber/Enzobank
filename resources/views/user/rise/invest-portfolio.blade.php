@extends('user.layouts.rise-master')

@section('content')
@php
$investments = $investments ?? collect([]);
$totalInvested = $totalInvested ?? 0;
$activeCount = $activeCount ?? 0;
$totalEarnings = $totalEarnings ?? 0;
@endphp

<div class="am-header">
    <h1 class="am-header-title">My Investments</h1>
</div>

<div class="am-body invest-flow">
    <!-- Stats Row -->
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
        <div class="am-card" style="text-align:center;padding:16px 10px;">
            <div class="ip-stat-label">Total Invested</div>
            <div class="ip-stat-value">${{ number_format($totalInvested, 2) }}</div>
        </div>
        <div class="am-card" style="text-align:center;padding:16px 10px;">
            <div class="ip-stat-label">Active Plans</div>
            <div class="ip-stat-value ip-stat-blue">{{ $activeCount }}</div>
        </div>
        <div class="am-card" style="text-align:center;padding:16px 10px;">
            <div class="ip-stat-label">Total Earnings</div>
            <div class="ip-stat-value ip-stat-green">${{ number_format($totalEarnings, 2) }}</div>
        </div>
    </div>

    <!-- Investment List -->
    <div style="display:flex;flex-direction:column;gap:12px;">
        @forelse($investments as $inv)
        <div class="am-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                <span style="font-weight:700;font-size:16px;">{{ $inv->plan->name ?? 'Unknown Plan' }}</span>
                @php
                    $statusClass = match($inv->status) {
                        'active' => 'ip-status-active',
                        'pending' => 'ip-status-pending',
                        'completed' => 'ip-status-completed',
                        'cancelled' => 'ip-status-cancelled',
                        default => 'ip-status-default'
                    };
                @endphp
                <span class="ip-status {{ $statusClass }}">{{ ucfirst($inv->status) }}</span>
            </div>

            @if($inv->status === 'active' || $inv->status === 'completed')
            @php
                $start = $inv->created_at;
                $end = $inv->maturity_date;
                $total = $start && $end ? $start->diffInDays($end) : 1;
                $elapsed = $start ? $start->diffInDays(now()) : 0;
                $pct = min(100, ($elapsed / max($total, 1)) * 100);
            @endphp
            <div class="ip-track" style="margin-bottom:10px;">
                <div class="ip-track-fill" style="width:{{ $pct }}%;"></div>
            </div>
            @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:13px;">
                <div><span class="ip-text-muted">Invested:</span> <strong>${{ number_format($inv->amount, 2) }}</strong></div>
                <div><span class="ip-text-muted">Return:</span> <strong class="ip-text-green">${{ number_format($inv->expected_return ?? 0, 2) }}</strong></div>
                @if($inv->maturity_date)
                <div style="grid-column:1/-1;"><span class="ip-text-muted">Days left:</span> <strong>{{ max(0, now()->diffInDays($inv->maturity_date, false)) }}</strong></div>
                @endif
                <div style="grid-column:1/-1;font-size:11px;" class="ip-text-secondary">{{ $inv->payment_method ?? '' }}</div>
            </div>
        </div>
        @empty
        <div style="display:flex;flex-direction:column;align-items:center;padding:60px 20px;text-align:center;gap:16px;">
            <div class="ip-empty-icon">+</div>
            <div style="font-size:16px;font-weight:700;">No active investments</div>
            <div class="ip-empty-text">Start your investment journey today</div>
            <a href="{{ route('user.invest.new') }}" class="am-btn" style="border-radius:100px;max-width:200px;">Start Investing</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
