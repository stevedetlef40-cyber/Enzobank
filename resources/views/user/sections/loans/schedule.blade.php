@extends('user.layouts.rise-master')

@push('css')
<style>
.sch-summary {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}
.sch-summary .sch-item {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 12px 14px;
}
.sch-summary .sch-label {
    font-size: 11px;
    font-weight: 500;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.sch-summary .sch-value {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
    margin-top: 4px;
}
.sch-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.sch-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
    min-width: 640px;
}
.sch-table th {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 6px 12px;
    text-align: left;
}
.sch-table td {
    padding: 12px;
    background: var(--bg-card);
    border-top: 1px solid var(--border-color);
    border-bottom: 1px solid var(--border-color);
    font-size: 13px;
    color: var(--text-secondary);
}
.sch-table tr td:first-child {
    border-left: 1px solid var(--border-color);
    border-radius: 10px 0 0 10px;
}
.sch-table tr td:last-child {
    border-right: 1px solid var(--border-color);
    border-radius: 0 10px 10px 0;
}
.sch-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
}
.sch-badge.paid { background: rgba(59,130,246,0.12); color: var(--success); }
.sch-badge.due { background: rgba(245,158,11,0.12); color: var(--warning); }
.sch-badge.late { background: rgba(239,68,68,0.12); color: var(--danger); }
.sch-badge.pending { background: rgba(148,163,184,0.12); color: var(--text-muted); }
.sch-actions { margin-top: 16px; }
</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ __('Repayment Schedule') }}</h1>
    <a href="{{ route('user.loans.index') }}" class="rw-section-link-pill">← {{ __('Back') }}</a>
</div>

<div class="am-body">
    <div class="am-card">
        <div class="sch-summary">
            <div class="sch-item">
                <div class="sch-label">{{ __('Principal') }}</div>
                <div class="sch-value">{{ get_amount($loan->principal) }}</div>
            </div>
            <div class="sch-item">
                <div class="sch-label">{{ __('Rate') }}</div>
                <div class="sch-value">{{ number_format($loan->interest_rate,2) }}%</div>
            </div>
            <div class="sch-item">
                <div class="sch-label">{{ __('Method') }}</div>
                <div class="sch-value">{{ ucfirst($loan->interest_method) }}</div>
            </div>
            <div class="sch-item">
                <div class="sch-label">{{ __('Frequency') }}</div>
                <div class="sch-value">{{ ucfirst($loan->payment_frequency) }}</div>
            </div>
        </div>

        <div class="sch-table-wrap">
            <table class="sch-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Due Date') }}</th>
                        <th>{{ __('Principal Due') }}</th>
                        <th>{{ __('Interest Due') }}</th>
                        <th>{{ __('Fee Due') }}</th>
                        <th>{{ __('Amount Due') }}</th>
                        <th>{{ __('Paid') }}</th>
                        <th>{{ __('Remaining Principal') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loan->payments->sortBy('due_date') as $p)
                    <tr>
                        <td>{{ $p->period_number }}</td>
                        <td>{{ $p->due_date?->format('Y-m-d') }}</td>
                        <td>{{ get_amount($p->principal_due) }}</td>
                        <td>{{ get_amount($p->interest_due) }}</td>
                        <td>{{ get_amount($p->fee_due) }}</td>
                        <td>{{ get_amount($p->amount_due) }}</td>
                        <td>{{ get_amount($p->amount_paid) }}</td>
                        <td>{{ get_amount($p->remaining_principal) }}</td>
                        <td><span class="sch-badge {{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="sch-actions">
            <a href="{{ route('user.loans.index') }}" class="am-btn">{{ __('Back to Loans') }}</a>
        </div>
    </div>
</div>
@endsection
