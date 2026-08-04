@extends('user.layouts.rise-master')

@push('css')
<style>
.loans-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px;
    text-align: center;
}
.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--accent);
}
.stat-label {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}
.loans-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.loan-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px;
    transition: all 0.2s ease;
}
.loan-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}
.loan-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
    gap: 16px;
}
.loan-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
}
.loan-meta {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}
.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 600;
}
.badge-pending_review { background: rgba(245,158,11,0.12); color: var(--warning); }
.badge-approved { background: rgba(59,130,246,0.12); color: var(--accent); }
.badge-funded { background: rgba(34,197,94,0.12); color: var(--success); }
.badge-rejected { background: rgba(239,68,68,0.12); color: var(--danger); }
.badge-pending { background: rgba(245,158,11,0.12); color: var(--warning); }
.badge-active { background: rgba(34,197,94,0.12); color: var(--success); }
.badge-closed { background: rgba(148,163,184,0.12); color: var(--text-secondary); }
.badge-defaulted { background: rgba(239,68,68,0.12); color: var(--danger); }
.loan-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
    padding: 16px;
    background: var(--bg-primary);
    border-radius: 12px;
}
.loan-stat {
    text-align: center;
}
.loan-stat-value {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
}
.loan-stat-label {
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 2px;
}
.loan-progress {
    margin-bottom: 16px;
}
.loan-progress-bar {
    height: 8px;
    background: var(--border-color);
    border-radius: 100px;
    overflow: hidden;
    margin-bottom: 8px;
}
.loan-progress-fill {
    height: 100%;
    border-radius: 100px;
    background: linear-gradient(90deg, var(--accent), #60A5FA);
    width: 0;
    transition: width 1.4s ease-out;
}
.loan-progress-info {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: var(--text-secondary);
}
.loan-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.btn {
    display: inline-flex;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.15s;
    text-decoration: none;
    border: none;
    cursor: pointer;
}
.btn.primary { background: var(--accent); color: #fff; }
.btn.success { background: var(--success); color: #fff; }
.btn.info { background: rgba(59,130,246,0.1); color: var(--accent); }
.btn.danger { background: var(--danger); color: #fff; }
.btn.outline { background: transparent; border: 1.5px solid var(--border-color); color: var(--text-primary); }
.btn:hover { opacity: 0.85; }
.empty-state {
    text-align: center;
    padding: 60px 24px;
    color: var(--text-muted);
}
.info-box {
    background: rgba(59,130,246,0.1);
    border: 1px solid var(--accent);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
}
.info-box-title {
    font-weight: 600;
    color: var(--accent);
    margin-bottom: 8px;
}
.info-box ul {
    margin: 0;
    padding-left: 20px;
    font-size: 13px;
    color: var(--text-secondary);
}
.info-box li {
    margin-bottom: 6px;
}
</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ $page_title }}</h1>
    <a href="{{ route('user.loans.create') }}" class="rw-section-link-pill">{{ __('Apply for Loan') }} →</a>
</div>

<div class="am-body" style="padding-bottom: 120px;">

    {{-- Info Box --}}
    <div class="info-box">
        <div class="info-box-title">{{ __('Investment Loan Program') }}</div>
        <ul>
            <li>{{ __('Loan funds are restricted for investment purposes only') }}</li>
            <li>{{ __('You cannot withdraw the loan principal') }}</li>
            <li>{{ __('Earnings from investments can be withdrawn after making a qualifying deposit') }}</li>
            <li>{{ __('A 2.5% withdrawal fee applies to earnings') }}</li>
            <li>{{ __('Admin approval required before funds are released') }}</li>
        </ul>
    </div>

    {{-- Stats --}}
    <div class="loans-stats">
        <div class="stat-card">
            <div class="stat-value">{{ $totalPrincipal ? '$' . number_format($totalPrincipal, 2) : '$0.00' }}</div>
            <div class="stat-label">{{ __('Total Applied') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $totalFunded ? '$' . number_format($totalFunded, 2) : '$0.00' }}</div>
            <div class="stat-label">{{ __('Total Funded') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $totalLoanWalletBalance ? '$' . number_format($totalLoanWalletBalance, 2) : '$0.00' }}</div>
            <div class="stat-label">{{ __('Available to Invest') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $totalInvestedFromLoans ? '$' . number_format($totalInvestedFromLoans, 2) : '$0.00' }}</div>
            <div class="stat-label">{{ __('Currently Invested') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $totalEarningsAvailable ? '$' . number_format($totalEarningsAvailable, 2) : '$0.00' }}</div>
            <div class="stat-label">{{ __('Withdrawable Earnings') }}</div>
        </div>
    </div>

    {{-- Loans List --}}
    <div class="loans-list">
        @if ($loans->isEmpty())
            <div class="empty-state">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 20px; opacity: 0.4;">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <path d="M9 9h6v6H9z"/>
                </svg>
                <h3>{{ __('No Loan Applications') }}</h3>
                <p>{{ __('Apply for an investment loan to grow your portfolio with EnzoBank funds.') }}</p>
                <a href="{{ route('user.loans.create') }}" class="btn primary" style="margin-top: 16px;">{{ __('Apply for Loan') }}</a>
            </div>
        @else
            @foreach ($loans as $loan)
            <div class="loan-card">
                <div class="loan-header">
                    <div>
                        <div class="loan-title">{{ $loan->product->name ?? 'Custom Loan' }}</div>
                        <div class="loan-meta">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->principal, 2) }} • {{ $loan->term_months }} months @ {{ $loan->interest_rate }}%</div>
                    </div>
                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                        <span class="badge badge-{{ $loan->approval_status }}">{{ ucfirst(str_replace('_', ' ', $loan->approval_status)) }}</span>
                        <span class="badge badge-{{ $loan->status }}" style="font-size: 10px;">{{ ucfirst($loan->status) }}</span>
                    </div>
                </div>

                <div class="loan-stats">
                    <div class="loan-stat">
                        <div class="loan-stat-value">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->funded_amount, 2) }}</div>
                        <div class="loan-stat-label">{{ __('Funded') }}</div>
                    </div>
                    <div class="loan-stat">
                        <div class="loan-stat-value">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->balance_principal, 2) }}</div>
                        <div class="loan-stat-label">{{ __('Outstanding') }}</div>
                    </div>
                    <div class="loan-stat">
                        <div class="loan-stat-value">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->available_for_investment, 2) }}</div>
                        <div class="loan-stat-label">{{ __('Available to Invest') }}</div>
                    </div>
                    <div class="loan-stat">
                        <div class="loan-stat-value">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->withdrawable_earnings, 2) }}</div>
                        <div class="loan-stat-label">{{ __('Available Earnings') }}</div>
                    </div>
                </div>

                @if ($loan->funded_amount > 0)
                <div class="loan-progress">
                    <div class="loan-progress-bar">
                        <div class="loan-progress-fill" style="width: {{ $loan->principal > 0 ? min(100, round(($loan->funded_amount / $loan->principal) * 100)) : 0 }}%;"></div>
                    </div>
                    <div class="loan-progress-info">
                        <span>{{ $loan->principal > 0 ? round(($loan->funded_amount / $loan->principal) * 100) : 0 }}% Funded</span>
                        <span>{{ $loan->principal > 0 ? $loan->currency . ' ' . number_format($loan->principal - $loan->funded_amount, 2) : $loan->currency . ' ' . number_format($loan->principal, 2) }} Remaining</span>
                    </div>
                </div>
                @endif

                <div class="loan-actions">
                    <a href="{{ route('user.loans.show', $loan->id) }}" class="btn info">{{ __('View Details') }}</a>
                    @if ($loan->wallet->first() && $loan->wallet->first()->earnings_balance > 0 && $loan->canWithdrawEarnings())
                        <a href="{{ route('user.loans.show', $loan->id) }}#withdraw" class="btn success">{{ __('Withdraw Earnings') }}</a>
                    @endif
                    @if ($loan->approval_status === \App\Models\Loan::APPROVAL_PENDING_REVIEW)
                        <a href="{{ route('user.loans.edit', $loan->id) }}" class="btn outline">{{ __('Edit') }}</a>
                    @endif
                </div>
            </div>
            @endforeach
        @endif
    </div>

    {{-- Pagination --}}
    @if (!$loans->isEmpty())
    <div style="padding: 16px 0;">
        {{ $loans->links() }}
    </div>
    @endif

</div>
@endsection