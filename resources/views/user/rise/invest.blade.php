@extends('user.layouts.rise-master')

@push('css')
<style>
/* ===== INVEST PAGE — ANIMATED UPGRADE ===== */
.inv-hidden { display: none !important; }

/* Staggered fade-slide-in */
.inv-card {
    opacity: 0;
    transform: translateY(20px);
    animation: invFadeUp 0.5s ease-out forwards;
}
@keyframes invFadeUp {
    to { opacity: 1; transform: translateY(0); }
}

/* Circular progress ring */
.inv-ring {
    width: 72px; height: 72px;
    position: relative;
}
.inv-ring svg {
    transform: rotate(-90deg);
    width: 72px; height: 72px;
}
.inv-ring-bg { fill: none; stroke: var(--border-color); stroke-width: 5; }
.inv-ring-fg {
    fill: none; stroke: var(--accent); stroke-width: 5;
    stroke-linecap: round;
    stroke-dasharray: 188.5;
    stroke-dashoffset: 188.5;
    transition: stroke-dashoffset 1s ease-out;
}
.inv-ring-fg.green { stroke: var(--success); }
.inv-ring-fg.orange { stroke: #F59E0B; }
.inv-ring-fg.purple { stroke: #8B5CF6; }
.inv-ring-center {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    color: #fff;
    line-height: 1.1;
}
.inv-ring-label { font-size: 8px; font-weight: 500; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

/* Horizontal progress bar */
.inv-progress {
    height: 6px;
    background: var(--border-color);
    border-radius: 3px;
    overflow: hidden;
    position: relative;
}
.inv-progress-fill {
    height: 100%;
    border-radius: 3px;
    width: 0;
    transition: width 1s ease-out;
}
.inv-progress-fill.blue { background: var(--accent); }
.inv-progress-fill.green { background: var(--success); }
.inv-progress-fill.orange { background: #F59E0B; }
.inv-progress-fill.purple { background: #8B5CF6; }
.inv-progress-fill.red { background: var(--danger); }

/* Status badges */
.inv-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}
.inv-badge.active { background: rgba(59,130,246,0.12); color: var(--success); }
.inv-badge.closed { background: rgba(239,68,68,0.12); color: var(--danger); }
.inv-badge.pending { background: rgba(245,158,11,0.12); color: #F59E0B; }
.inv-badge.passed { background: rgba(59,130,246,0.12); color: var(--accent); }
.inv-badge-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
}
.inv-badge.active .inv-badge-dot { background: var(--success); }
.inv-badge.closed .inv-badge-dot { background: var(--danger); }
.inv-badge.pending .inv-badge-dot { background: #F59E0B; }
.inv-badge.passed .inv-badge-dot { background: var(--accent); }

/* Stats cards row */
.inv-stats {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 10px;
    margin-bottom: 16px;
}
.inv-stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 16px 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 6px;
}
.inv-stat-label {
    font-size: 10px;
    font-weight: 500;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Filter row */
.inv-filters {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.inv-filters::-webkit-scrollbar { display: none; }
.inv-filter-select {
    flex: 1;
    min-width: 0;
    padding: 10px 12px;
    border: 1px solid var(--border-strong);
    border-radius: 10px;
    font-size: 13px;
    background: var(--border-color);
    color: var(--text-primary);
    outline: none;
    -webkit-appearance: none;
    appearance: none;
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2394A3B8' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
}
.inv-filter-select:focus { border-color: var(--accent); }
.inv-filter-select option { background: var(--bg-card); color: var(--text-primary); }

/* Plans grid */
.inv-plans-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 12px;
}

/* Plan card */
.inv-plan-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 16px 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    transition: border-color 0.15s, transform 0.15s;
}
.inv-plan-card:active { border-color: var(--accent); transform: scale(0.98); }
.inv-plan-rate {
    font-size: 20px;
    font-weight: 800;
    color: var(--accent);
    line-height: 1.1;
}
.inv-plan-name { font-size: 13px; font-weight: 600; color: #fff; }
.inv-plan-meta { font-size: 11px; color: var(--text-muted); display: flex; gap: 6px; flex-wrap: wrap; }
.inv-plan-btn {
    margin-top: 6px;
    padding: 9px 12px;
    border-radius: 100px;
    background: var(--accent);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    text-align: center;
    display: block;
    transition: background 0.15s;
    text-decoration: none;
}
.inv-plan-btn:active { background: #2563EB; }

/* Donut chart */
.inv-donut {
    position: relative;
    width: 120px;
    height: 120px;
}
.inv-donut svg { width: 120px; height: 120px; transform: rotate(-90deg); }
.inv-donut-circle {
    fill: none;
    stroke-width: 12;
    stroke-dasharray: 282.7;
    stroke-dashoffset: 282.7;
    transition: stroke-dashoffset 1.2s ease-out;
}
.inv-donut-center {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.inv-donut-value { font-size: 18px; font-weight: 800; color: #fff; }
.inv-donut-label { font-size: 9px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

/* Donut legend */
.inv-legend { display: flex; flex-direction: column; gap: 8px; flex: 1; }
.inv-legend-item { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-secondary); }
.inv-legend-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}
.inv-legend-bar {
    flex: 1;
    height: 4px;
    background: var(--border-color);
    border-radius: 2px;
    overflow: hidden;
}
.inv-legend-fill {
    height: 100%;
    border-radius: 2px;
    width: 0;
    transition: width 1s ease-out;
}

/* Section headers */
.inv-section-title {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.inv-section-title .inv-badge { font-size: 11px; }

/* CTA button */
.inv-cta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    position: fixed;
    bottom: 80px;
    left: 50%;
    transform: translateX(-50%);
    max-width: 448px;
    width: calc(100% - 32px);
    padding: 14px 24px;
    background: var(--accent);
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    border-radius: 12px;
    z-index: 40;
    text-decoration: none;
    transition: background 0.15s;
}
.inv-cta:active { background: #2563EB; }

/* Earn banner */
.inv-earn-banner {
    background: linear-gradient(135deg, #1E3A5F, #1E40AF);
    border-radius: 14px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}
.inv-earn-left { display: flex; align-items: center; gap: 12px; }
.inv-earn-icon { font-size: 28px; }
.inv-earn-text { display: flex; flex-direction: column; }
.inv-earn-title { font-size: 15px; font-weight: 700; color: #fff; }
.inv-earn-sub { font-size: 11px; color: rgba(255,255,255,0.7); }
.inv-earn-btn {
    padding: 8px 18px;
    background: rgba(255,255,255,0.18);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    text-decoration: none;
    transition: background 0.15s;
}
.inv-earn-btn:active { background: rgba(255,255,255,0.3); }

/* Empty state */
.inv-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 20px;
    text-align: center;
    gap: 6px;
    color: var(--text-muted);
}
.inv-empty-icon { font-size: 36px; }
.inv-empty-title { font-size: 15px; font-weight: 700; color: #fff; }
.inv-empty-sub { font-size: 12px; }

/* Learn More modal */
.inv-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.65);
    z-index: 999;
    align-items: flex-end;
    justify-content: center;
}
.inv-modal-overlay.open { display: flex; }
.inv-modal-sheet {
    background: #0F172A;
    border-radius: 20px 20px 0 0;
    width: 100%;
    max-width: 480px;
    max-height: 80vh;
    overflow-y: auto;
    padding: 24px 20px 32px;
    animation: invSheetUp 0.35s ease-out;
}
@keyframes invSheetUp {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}
.inv-modal-handle {
    width: 36px;
    height: 4px;
    background: var(--border-strong);
    border-radius: 2px;
    margin: 0 auto 18px;
}
.inv-modal-title {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 4px;
}
.inv-modal-sub {
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 20px;
}
.inv-modal-step {
    display: flex;
    gap: 14px;
    margin-bottom: 18px;
}
.inv-modal-step-num {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--border-color);
    border: 1px solid var(--border-strong);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    color: var(--accent);
    flex-shrink: 0;
}
.inv-modal-step-body h4 {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    margin: 0 0 3px;
}
.inv-modal-step-body p {
    font-size: 12px;
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.5;
}
.inv-modal-close {
    display: block;
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    background: var(--accent);
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    text-align: center;
    border: none;
    cursor: pointer;
    margin-top: 8px;
}
.inv-modal-close:active { background: #2563EB; }

/* Light mode */
[data-theme="light"] {
    .inv-stat-card { background: #fff; border-color: #E2E8F0; }
    .inv-stat-label { color: var(--text-muted); }
    .inv-filter-select { background: #F8FAFC; border-color: #CBD5E1; color: #0F172A; }
    .inv-filter-select option { background: #fff; color: #0F172A; }
    .inv-plan-card { background: #fff; border-color: #E2E8F0; }
    .inv-plan-name { color: #0F172A; }
    .inv-plan-meta { color: var(--text-muted); }
    .inv-section-title { color: #0F172A; }
    .inv-ring-bg { stroke: #E2E8F0; }
    .inv-ring-center { color: #0F172A; }
    .inv-progress { background: #E2E8F0; }
    .inv-donut-value { color: #0F172A; }
    .inv-legend-item { color: var(--text-muted); }
    .inv-legend-bar { background: #E2E8F0; }
    .inv-empty-title { color: #0F172A; }
    .inv-modal-sheet { background: #fff; }
    .inv-modal-title { color: #0F172A; }
    .inv-modal-step-num { background: var(--text-primary); border-color: #CBD5E1; }
    .inv-modal-step-body h4 { color: #0F172A; }
    .io-title { color: #0F172A; }
    .ri-find-btn { background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; }
}
</style>
@endpush

@section('content')
@php
$user = auth()->user();
$assets = $assets ?? collect([]);
$portfolio = $portfolio ?? null;
$holdings = $holdings ?? collect([]);

// Compute stats from assets
$totalPlans = $assets->count();
$activePlans = $assets->where('status', true)->count();
$avgYield = $assets->avg('base_yield') ?? 0;
$totalValue = $holdings->sum(function ($h) {
    return (float)($h->quantity ?? 0) * (float)($h->asset->current_price ?? 0);
});
@endphp

<div class="io-header" style="border:none;padding-bottom:0;">
    <h1 class="io-title" style="font-size:22px;">Invest</h1>
    <a href="{{ route('user.rise.invest') }}" class="ri-find-btn" style="font-size:12px;padding:6px 14px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        Find Plans
    </a>
</div>

<div class="io-body" style="padding-bottom:100px;">

    {{-- ===== STATS ROW (animated rings + progress bars) ===== --}}
    <div class="inv-stats" id="invStats">
        <div class="inv-stat-card inv-card" style="animation-delay:0.05s;">
            <div class="inv-ring" id="ringActive">
                <svg viewBox="0 0 72 72"><circle class="inv-ring-bg" cx="36" cy="36" r="30"/><circle class="inv-ring-fg green" cx="36" cy="36" r="30" data-pct="{{ $totalPlans > 0 ? round(($activePlans/$totalPlans)*100) : 0 }}"/></svg>
                <div class="inv-ring-center"><span class="inv-ring-center-value" data-target="{{ $activePlans }}">{{ $activePlans }}</span><span class="inv-ring-label">Active</span></div>
            </div>
            <span class="inv-stat-label">Win Rate</span>
        </div>
        <div class="inv-stat-card inv-card" style="animation-delay:0.10s;">
            <div class="inv-ring" id="ringYield">
                <svg viewBox="0 0 72 72"><circle class="inv-ring-bg" cx="36" cy="36" r="30"/><circle class="inv-ring-fg" cx="36" cy="36" r="30" data-pct="{{ min(round($avgYield * 5), 100) }}"/></svg>
                <div class="inv-ring-center"><span class="inv-ring-center-value" data-target="{{ number_format($avgYield, 1) }}">{{ number_format($avgYield, 1) }}</span><span class="inv-ring-label">Yield %</span></div>
            </div>
            <span class="inv-stat-label">Avg Yield</span>
        </div>
        <div class="inv-stat-card inv-card" style="animation-delay:0.15s;">
            <div class="inv-ring" id="ringPlans">
                <svg viewBox="0 0 72 72"><circle class="inv-ring-bg" cx="36" cy="36" r="30"/><circle class="inv-ring-fg orange" cx="36" cy="36" r="30" data-pct="{{ min($totalPlans * 10, 100) }}"/></svg>
                <div class="inv-ring-center"><span class="inv-ring-center-value" data-target="{{ $totalPlans }}">{{ $totalPlans }}</span><span class="inv-ring-label">Plans</span></div>
            </div>
            <span class="inv-stat-label">Available</span>
        </div>
        <div class="inv-stat-card inv-card" style="animation-delay:0.20s;">
            <div class="inv-ring" id="ringValue">
                <svg viewBox="0 0 72 72"><circle class="inv-ring-bg" cx="36" cy="36" r="30"/><circle class="inv-ring-fg purple" cx="36" cy="36" r="30" data-pct="{{ $totalValue > 0 ? min(round(($totalValue/10000)*100), 100) : 0 }}"/></svg>
                <div class="inv-ring-center"><span class="inv-ring-center-value" data-target="{{ $totalValue > 0 ? 'K' : 0 }}">{{ $totalValue > 0 ? number_format($totalValue/1000,1).'K' : '0' }}</span><span class="inv-ring-label">Value</span></div>
            </div>
            <span class="inv-stat-label">Portfolio</span>
        </div>
    </div>

    {{-- ===== EARN BANNER ===== --}}
    <div class="inv-earn-banner inv-card" style="animation-delay:0.25s;">
        <div class="inv-earn-left">
            <span class="inv-earn-icon">💰</span>
            <div class="inv-earn-text">
                <span class="inv-earn-title">Earn up to 23% annually</span>
                <span class="inv-earn-sub">Start investing with as little as $10</span>
            </div>
        </div>
        <a href="javascript:void(0)" onclick="document.getElementById('invModal').classList.add('open')" class="inv-earn-btn">Learn More</a>
    </div>

    {{-- ===== PROGRESS BARS (comparative stats) ===== --}}
    <div class="inv-card" style="animation-delay:0.30s;margin-bottom:16px;">
        <div class="inv-section-title">
            Fund Performance
            <span class="inv-badge passed"><span class="inv-badge-dot"></span> On Track</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <div>
                <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--text-secondary);margin-bottom:4px;">
                    <span>Fixed Income</span>
                    <span>85%</span>
                </div>
                <div class="inv-progress"><div class="inv-progress-fill blue" data-target="85"></div></div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--text-secondary);margin-bottom:4px;">
                    <span>Growth Fund</span>
                    <span>62%</span>
                </div>
                <div class="inv-progress"><div class="inv-progress-fill green" data-target="62"></div></div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--text-secondary);margin-bottom:4px;">
                    <span>Premium Plus</span>
                    <span>41%</span>
                </div>
                <div class="inv-progress"><div class="inv-progress-fill orange" data-target="41"></div></div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--text-secondary);margin-bottom:4px;">
                    <span>Sterling Vault</span>
                    <span>28%</span>
                </div>
                <div class="inv-progress"><div class="inv-progress-fill purple" data-target="28"></div></div>
            </div>
        </div>
    </div>

    {{-- ===== FILTERS ===== --}}
    <div class="inv-filters inv-card" style="animation-delay:0.35s;padding:0;border:none;background:none;">
        <select class="inv-filter-select" id="filterType">
            <option value="all">All Types</option>
            <option value="Stocks">Stocks</option>
            <option value="Bonds">Bonds</option>
            <option value="Real Estate">Real Estate</option>
            <option value="Crypto">Crypto</option>
        </select>
        <select class="inv-filter-select" id="filterStatus">
            <option value="all">All Status</option>
            <option value="active">Active</option>
            <option value="closed">Closed</option>
        </select>
        <select class="inv-filter-select" id="filterRisk">
            <option value="all">All Risk</option>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>
    </div>

    {{-- ===== PLANS HEADER ===== --}}
    <div class="inv-section-title inv-card" style="animation-delay:0.40s;border:none;padding:0;">
        Investment Plans
        <span class="inv-badge active"><span class="inv-badge-dot"></span> {{ $totalPlans }} available</span>
    </div>

    {{-- ===== PLANS GRID ===== --}}
    <div class="inv-plans-grid" id="invPlansGrid">
        @forelse($assets->take(8) as $i => $asset)
        <div class="inv-plan-card inv-card plan-card" style="animation-delay:{{ 0.40 + ($i+1)*0.06 }}s;" data-type="{{ $asset->asset_type ?? 'Stocks' }}" data-status="{{ $asset->status ? 'active' : 'closed' }}" data-risk="{{ $asset->risk_level ?? 'medium' }}">
            <span class="inv-badge {{ $asset->status ? 'active' : 'closed' }}" style="align-self:flex-start;">
                <span class="inv-badge-dot"></span> {{ $asset->status ? 'Active' : 'Closed' }}
            </span>
            <div class="inv-plan-rate">{{ $asset->base_yield ?? rand(5,23) }}%</div>
            <div class="inv-plan-name">{{ $asset->name ?? 'Plan' }}</div>
            <div class="inv-plan-meta">
                <span>{{ $asset->symbol ?? '' }}</span>
                <span>·</span>
                <span>{{ $asset->risk_level ?? 'Medium' }} risk</span>
            </div>
            <a href="{{ route('user.invest.new') }}" class="inv-plan-btn">Invest Now</a>
        </div>
        @empty
        <div style="grid-column:1/-1;">
            <div class="inv-empty">
                <span class="inv-empty-icon">📊</span>
                <span class="inv-empty-title">No investment plans yet</span>
                <span class="inv-empty-sub">Create your first plan to start earning</span>
            </div>
        </div>
        @endforelse
    </div>

    {{-- ===== PORTFOLIO COMPOSITION (donut chart) ===== --}}
    @if($holdings->count() > 0)
    <div class="inv-card" style="animation-delay:0.80s;">
        <div class="inv-section-title">
            Portfolio Composition
            <span class="inv-badge passed"><span class="inv-badge-dot"></span> Diversified</span>
        </div>
        <div style="display:flex;align-items:center;gap:20px;">
            <div class="inv-donut" id="donutChart">
                <svg viewBox="0 0 100 100">
                    <circle class="inv-donut-circle" cx="50" cy="50" r="45" style="stroke:var(--accent);" data-pct="45"/>
                    <circle class="inv-donut-circle" cx="50" cy="50" r="45" style="stroke:var(--success);transform:rotate(162deg);transform-origin:50% 50%;" data-pct="30"/>
                    <circle class="inv-donut-circle" cx="50" cy="50" r="45" style="stroke:#F59E0B;transform:rotate(270deg);transform-origin:50% 50%;" data-pct="25"/>
                </svg>
                <div class="inv-donut-center">
                    <span class="inv-donut-value">${{ number_format($totalValue, 0) }}</span>
                    <span class="inv-donut-label">Total</span>
                </div>
            </div>
            <div class="inv-legend">
                @foreach($holdings->take(4) as $h)
                <div class="inv-legend-item">
                    <span class="inv-legend-dot" style="background:{{ $loop->index == 0 ? 'var(--accent)' : ($loop->index == 1 ? 'var(--success)' : ($loop->index == 2 ? '#F59E0B' : '#8B5CF6')) }};"></span>
                    <span style="flex:1;">{{ $h->asset->name ?? $h->asset_type ?? 'Asset' }}</span>
                    <span style="font-weight:600;color:var(--text-primary);">{{ $h->allocation_percent ?? 0 }}%</span>
                    <div class="inv-legend-bar"><div class="inv-legend-fill" style="background:{{ $loop->index == 0 ? 'var(--accent)' : ($loop->index == 1 ? 'var(--success)' : ($loop->index == 2 ? '#F59E0B' : '#8B5CF6')) }};" data-target="{{ $h->allocation_percent ?? 0 }}"></div></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ===== LEARN MORE MODAL ===== --}}
<div class="inv-modal-overlay" id="invModal" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="inv-modal-sheet">
        <div class="inv-modal-handle"></div>
        <div class="inv-modal-title">How Investing Works</div>
        <div class="inv-modal-sub">Everything you need to know to get started with EnzoBank Rise investing</div>

        <div class="inv-modal-step">
            <div class="inv-modal-step-num">1</div>
            <div class="inv-modal-step-body">
                <h4>Choose an Investment Plan</h4>
                <p>Browse our curated plans across Stocks, Bonds, Real Estate, and Crypto. Each plan shows its expected yield, risk level, and minimum investment so you can pick what fits your goals.</p>
            </div>
        </div>

        <div class="inv-modal-step">
            <div class="inv-modal-step-num">2</div>
            <div class="inv-modal-step-body">
                <h4>Fund Your Investment</h4>
                <p>Transfer funds from your EnzoBank wallet to your investment account. You can start with as little as $10. Add funds anytime to increase your position.</p>
            </div>
        </div>

        <div class="inv-modal-step">
            <div class="inv-modal-step-num">3</div>
            <div class="inv-modal-step-body">
                <h4>Track Performance</h4>
                <p>Monitor your portfolio in real time through your dashboard. View yield rates, portfolio composition, daily P&amp;L, and historical performance charts.</p>
            </div>
        </div>

        <div class="inv-modal-step">
            <div class="inv-modal-step-num">4</div>
            <div class="inv-modal-step-body">
                <h4>Earn &amp; Withdraw</h4>
                <p>Earnings accrue daily based on your plan's yield rate. Withdraw your returns or reinvest them to compound growth. No lock-in periods — exit anytime.</p>
            </div>
        </div>

        <div style="background:var(--border-color);border-radius:12px;padding:16px;margin-bottom:8px;">
            <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--text-secondary);margin-bottom:6px;">
                <span>Minimum investment</span>
                <span style="color:#fff;font-weight:600;">$10</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--text-secondary);margin-bottom:6px;">
                <span>Expected returns</span>
                <span style="color:var(--success);font-weight:600;">5% – 23% annually</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--text-secondary);margin-bottom:6px;">
                <span>Payout frequency</span>
                <span style="color:#fff;font-weight:600;">Daily</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--text-secondary);">
                <span>Risk levels</span>
                <span style="color:#fff;font-weight:600;">Low / Medium / High</span>
            </div>
        </div>

        <button class="inv-modal-close" onclick="document.getElementById('invModal').classList.remove('open')">Got it</button>
    </div>
</div>

{{-- ===== FIXED CTA BUTTON ===== --}}
<a href="{{ route('user.invest.new') }}" class="inv-cta">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Create Investment Plan
</a>

@endsection

@push("script")
<script>
(function(){
    // ===== ANIMATE CIRCULAR RINGS =====
    function animateRings() {
        document.querySelectorAll('.inv-ring-fg').forEach(function(ring) {
            var pct = parseFloat(ring.getAttribute('data-pct')) || 0;
            var circumference = 188.5;
            var offset = circumference - (pct / 100) * circumference;
            ring.style.strokeDashoffset = String(offset);
        });
    }

    // ===== ANIMATE PROGRESS BARS =====
    function animateBars() {
        document.querySelectorAll('.inv-progress-fill').forEach(function(bar) {
            var target = parseFloat(bar.getAttribute('data-target')) || 0;
            bar.style.width = target + '%';
        });
        document.querySelectorAll('.inv-legend-fill').forEach(function(bar) {
            var target = parseFloat(bar.getAttribute('data-target')) || 0;
            bar.style.width = target + '%';
        });
    }

    // ===== ANIMATE DONUT =====
    function animateDonut() {
        document.querySelectorAll('.inv-donut-circle').forEach(function(circle) {
            var pct = parseFloat(circle.getAttribute('data-pct')) || 0;
            var circumference = 282.7;
            var offset = circumference - (pct / 100) * circumference;
            circle.style.strokeDashoffset = String(offset);
        });
    }

    // ===== COUNTER ANIMATION =====
    function animateCounters() {
        document.querySelectorAll('.inv-ring-center-value').forEach(function(el) {
            var target = parseFloat(el.getAttribute('data-target')) || 0;
            if (target === 0) return;
            var current = 0;
            var step = Math.max(target / 30, 0.1);
            var interval = setInterval(function() {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(interval);
                }
                el.textContent = Number.isInteger(target) ? Math.round(current) : current.toFixed(1);
            }, 30);
        });
    }

    // ===== TRIGGER ALL ON LOAD =====
    var delay = 600; // Wait for entrance animations to begin
    setTimeout(function() {
        animateRings();
        animateDonut();
    }, delay);
    setTimeout(function() {
        animateBars();
        animateCounters();
    }, delay + 200);

    // ===== INTERSECTION OBSERVER (re-trigger on scroll) =====
    var observed = new Set();
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting && !observed.has(entry.target)) {
                observed.add(entry.target);
                // Re-trigger bars within this section
                entry.target.querySelectorAll('.inv-progress-fill, .inv-legend-fill').forEach(function(bar) {
                    var target = parseFloat(bar.getAttribute('data-target')) || 0;
                    bar.style.width = target + '%';
                });
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    document.querySelectorAll('.inv-progress, .inv-legend').forEach(function(el) {
        observer.observe(el);
    });

    // ===== FILTERS =====
    var filterType = document.getElementById('filterType');
    var filterStatus = document.getElementById('filterStatus');
    var filterRisk = document.getElementById('filterRisk');

    function applyFilters() {
        var type = filterType ? filterType.value : 'all';
        var status = filterStatus ? filterStatus.value : 'all';
        var risk = filterRisk ? filterRisk.value : 'all';
        var anyActive = false;

        document.querySelectorAll('.plan-card').forEach(function(card) {
            var cardType = card.getAttribute('data-type') || '';
            var cardStatus = card.getAttribute('data-status') || '';
            var cardRisk = card.getAttribute('data-risk') || '';

            var matchType = type === 'all' || cardType.toLowerCase() === type.toLowerCase();
            var matchStatus = status === 'all' || cardStatus === status;
            var matchRisk = risk === 'all' || cardRisk === risk;

            var visible = matchType && matchStatus && matchRisk;
            card.style.display = visible ? '' : 'none';
            if (visible) anyActive = true;
        });

        // Show empty state if no results
        var empty = document.getElementById('invFilterEmpty');
        if (!empty) {
            empty = document.createElement('div');
            empty.id = 'invFilterEmpty';
            empty.className = 'inv-empty';
            empty.style.cssText = 'grid-column:1/-1;display:none;';
            empty.innerHTML = '<span class="inv-empty-icon">🔍</span><span class="inv-empty-title">No matching plans</span><span class="inv-empty-sub">Try adjusting your filters</span>';
            document.getElementById('invPlansGrid').appendChild(empty);
        }
        empty.style.display = anyActive ? 'none' : 'flex';
    }

    if (filterType) filterType.addEventListener('change', applyFilters);
    if (filterStatus) filterStatus.addEventListener('change', applyFilters);
    if (filterRisk) filterRisk.addEventListener('change', applyFilters);

    // Reset filter to show all on page load
    setTimeout(applyFilters, 50);

})();
</script>
@endpush
