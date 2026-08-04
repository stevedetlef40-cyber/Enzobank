@extends('admin.layouts.master')

@push('css')
<style>
.loans-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
    padding: 0 24px;
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
.loans-table-container {
    padding: 0 24px 24px;
}
.loans-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
}
.loans-table th {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 12px;
    text-align: left;
}
.loans-table td {
    padding: 16px;
    background: var(--bg-card);
    font-size: 13px;
    color: var(--text-secondary);
}
.loans-table tr td:first-child { border-radius: 12px 0 0 12px; }
.loans-table tr td:last-child { border-radius: 0 12px 12px 0; }
.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 600;
}
.badge-pending-review { background: rgba(245,158,11,0.12); color: var(--warning); }
.badge-approved { background: rgba(59,130,246,0.12); color: var(--accent); }
.badge-funded { background: rgba(34,197,94,0.12); color: var(--success); }
.badge-rejected { background: rgba(239,68,68,0.12); color: var(--danger); }
.badge-pending { background: rgba(245,158,11,0.12); color: var(--warning); }
.badge-active { background: rgba(34,197,94,0.12); color: var(--success); }
.badge-closed { background: rgba(148,163,184,0.12); color: var(--text-secondary); }
.badge-defaulted { background: rgba(239,68,68,0.12); color: var(--danger); }
.btn-sm {
    display: inline-flex;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.15s;
    text-decoration: none;
    border: none;
    cursor: pointer;
}
.btn-sm.primary { background: var(--accent); color: #fff; }
.btn-sm.success { background: var(--success); color: #fff; }
.btn-sm.danger { background: var(--danger); color: #fff; }
.btn-sm.info { background: rgba(59,130,246,0.1); color: var(--accent); }
.btn-sm:hover { opacity: 0.85; }
.empty-state {
    text-align: center;
    padding: 60px 24px;
    color: var(--text-muted);
}
.filters {
    padding: 0 24px 16px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}
.filters select, .filters input {
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--bg-card);
    color: var(--text-primary);
}
</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ $page_title }}</h1>
</div>

<div class="loans-stats">
    <div class="stat-card">
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">{{ __('Total Applications') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['pending_review'] }}</div>
        <div class="stat-label">{{ __('Pending Review') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['approved'] }}</div>
        <div class="stat-label">{{ __('Approved') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['funded'] }}</div>
        <div class="stat-label">{{ __('Funded') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['rejected'] }}</div>
        <div class="stat-label">{{ __('Rejected') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['active'] }}</div>
        <div class="stat-label">{{ __('Active Loans') }}</div>
    </div>
</div>

<div class="filters">
    <form method="GET">
        <input type="text" name="q" placeholder="{{ __('Search...') }}" value="{{ request('q') }}">
        <select name="approval_status">
            <option value="">{{ __('All Approval Status') }}</option>
            <option value="pending_review" {{ request('approval_status') === 'pending_review' ? 'selected' : '' }}>{{ __('Pending Review') }}</option>
            <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
            <option value="funded" {{ request('approval_status') === 'funded' ? 'selected' : '' }}>{{ __('Funded') }}</option>
            <option value="rejected" {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
        </select>
        <select name="status">
            <option value="">{{ __('All Status') }}</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>{{ __('Closed') }}</option>
            <option value="defaulted" {{ request('status') === 'defaulted' ? 'selected' : '' }}>{{ __('Defaulted') }}</option>
        </select>
        <button type="submit" class="btn-sm primary">{{ __('Filter') }}</button>
        <a href="{{ route('admin.loans.index') }}" class="btn-sm info">{{ __('Reset') }}</a>
    </form>
</div>

<div class="loans-table-container">
    @if ($loans->isEmpty())
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 16px; opacity: 0.5;">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <path d="M9 9h6v6H9z"/>
            </svg>
            <h3>{{ __('No loan applications found') }}</h3>
            <p>{{ __('Loan applications will appear here when users apply.') }}</p>
        </div>
    @else
        <table class="loans-table">
            <thead>
                <tr>
                    <th>{{ __('Applicant') }}</th>
                    <th>{{ __('Product') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Approval') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Funded') }}</th>
                    <th>{{ __('Applied') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loans as $loan)
                <tr>
                    <td>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $loan->user->fullname ?? $loan->user->email }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $loan->user->email }}</div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $loan->product->name ?? 'Custom' }}</div>
                        <div style="font-size: 11px; color: var(--text-muted);">{{ $loan->loan_type ?? 'investment' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->principal, 2) }}</div>
                        <div style="font-size: 11px; color: var(--text-muted);">{{ $loan->term_months }} mo @ {{ $loan->interest_rate }}%</div>
                    </td>
                    <td>
                        @php
                            $status = $loan->approval_status;
                            $class = 'badge-' . $status;
                        @endphp
                        <span class="badge {{ $class }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                    </td>
                    <td>
                        @php
                            $status = $loan->status;
                            $class = 'badge-' . $status;
                        @endphp
                        <span class="badge {{ $class }}">{{ ucfirst($status) }}</span>
                    </td>
                    <td>
                        @if ($loan->funded_amount > 0)
                            <div style="font-weight: 600; color: var(--success);">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->funded_amount, 2) }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">
                                {{ $loan->principal > 0 ? round(($loan->funded_amount / $loan->principal) * 100) : 0 }}%
                            </div>
                        @else
                            <span style="color: var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td>{{ $loan->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.loans.show', $loan->id) }}" class="btn-sm info">{{ __('View') }}</a>
                        @if ($loan->approval_status === \App\Models\Loan::APPROVAL_PENDING_REVIEW)
                            <a href="{{ route('admin.loans.approve', $loan->id) }}" class="btn-sm primary">{{ __('Approve') }}</a>
                            <a href="{{ route('admin.loans.reject', $loan->id) }}" class="btn-sm danger">{{ __('Reject') }}</a>
                        @elseif ($loan->approval_status === \App\Models\Loan::APPROVAL_APPROVED && !$loan->isFullyFunded())
                            <a href="{{ route('admin.loans.fund', $loan->id) }}" class="btn-sm success">{{ __('Fund') }}</a>
                        @elseif ($loan->approval_status === \App\Models\Loan::APPROVAL_FUNDED && $loan->status !== \App\Models\Loan::STATUS_ACTIVE)
                            <a href="{{ route('admin.loans.disbursse', $loan->id) }}" class="btn-sm primary">{{ __('Disburse') }}</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding: 16px 24px;">
            {{ $loans->links() }}
        </div>
    @endif
</div>
@endsection