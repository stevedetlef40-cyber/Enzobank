@extends('admin.layouts.master')

@push('css')
<style>
.loan-detail-header {
    padding: 24px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    margin-bottom: 24px;
}
.loan-detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}
.detail-item {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 16px;
}
.detail-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.detail-value {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
    margin-top: 4px;
}
.section-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    margin-bottom: 24px;
    overflow: hidden;
}
.section-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.section-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
}
.section-body {
    padding: 24px;
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}
.payments-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 4px;
}
.payments-table th {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 12px;
    text-align: left;
}
.payments-table td {
    padding: 12px;
    background: var(--bg-primary);
    font-size: 13px;
    color: var(--text-secondary);
}
.payments-table tr td:first-child { border-radius: 8px 0 0 8px; }
.payments-table tr td:last-child { border-radius: 0 8px 8px 0; }
.badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 600;
}
.badge-due { background: rgba(245,158,11,0.12); color: var(--warning); }
.badge-paid { background: rgba(34,197,94,0.12); color: var(--success); }
.badge-late { background: rgba(239,68,68,0.12); color: var(--danger); }
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
.action-form {
    display: inline;
}
</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ $page_title }}</h1>
    <a href="{{ route('admin.loans.index') }}" class="rw-section-link-pill">← {{ __('Back') }}</a>
</div>

<div style="padding: 0 24px 24px;">

    {{-- Header Card --}}
    <div class="loan-detail-header">
        <div class="loan-detail-grid">
            <div class="detail-item">
                <div class="detail-label">{{ __('Applicant') }}</div>
                <div class="detail-value">{{ $loan->user->fullname ?? $loan->user->email }}</div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">{{ $loan->user->email }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Loan Product') }}</div>
                <div class="detail-value">{{ $loan->product->name ?? 'Custom' }}</div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">{{ $loan->loan_type }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Principal') }}</div>
                <div class="detail-value">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->principal, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Term') }}</div>
                <div class="detail-value">{{ $loan->term_months }} months @ {{ $loan->interest_rate }}%</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Approval Status') }}</div>
                <div class="detail-value">
                    <span class="badge badge-{{ $loan->approval_status }}">{{ ucfirst(str_replace('_', ' ', $loan->approval_status)) }}</span>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Loan Status') }}</div>
                <div class="detail-value">
                    <span class="badge badge-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Funded Amount') }}</div>
                <div class="detail-value">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->funded_amount, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Outstanding') }}</div>
                <div class="detail-value">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->balance_principal, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Investment Plan') }}</div>
                <div class="detail-value">{{ $loan->investmentPlan->name ?? 'None assigned' }}</div>
            </div>
        </div>
    </div>

    {{-- Financial Details --}}
    <div class="section-card">
        <div class="section-header">
            <h3 class="section-title">{{ __('Financial Details') }}</h3>
        </div>
        <div class="section-body">
            <div class="info-grid">
                <div class="detail-item">
                    <div class="detail-label">{{ __('Interest Method') }}</div>
                    <div class="detail-value">{{ ucfirst($loan->interest_method) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Payment Frequency') }}</div>
                    <div class="detail-value">{{ ucfirst($loan->payment_frequency) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Rate Type') }}</div>
                    <div class="detail-value">{{ ucfirst($loan->rate_type) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Grace Days') }}</div>
                    <div class="detail-value">{{ $loan->grace_days }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Late Fee') }}</div>
                    <div class="detail-value">{{ $loan->late_fee_type === 'percent' ? $loan->late_fee_value . '%' : $loan->currency . ' ' . number_format($loan->late_fee_value, 2) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Early Settlement Fee') }}</div>
                    <div class="detail-value">{{ $loan->early_settlement_fee_percent }}%</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Origination Fee') }}</div>
                    <div class="detail-value">{{ $loan->origination_fee_percent }}% ({{ $loan->currency ?? 'USD' }} {{ number_format($loan->origination_fee_amount, 2) }})</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Service Fee') }}</div>
                    <div class="detail-value">{{ $loan->service_fee_percent }}%</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Withdrawal Fee') }}</div>
                    <div class="detail-value">{{ $loan->withdrawal_fee_percent }}%</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Withdrawal Restricted') }}</div>
                    <div class="detail-value">{{ $loan->withdrawal_restricted ? 'Yes' : 'No' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Deposit Required for Withdrawal') }}</div>
                    <div class="detail-value">{{ $loan->deposit_required_for_withdrawal ? 'Yes' : 'No' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Approval & Funding History --}}
    <div class="section-card">
        <div class="section-header">
            <h3 class="section-title">{{ __('Approval & Funding') }}</h3>
        </div>
        <div class="section-body">
            <div class="info-grid">
                <div class="detail-item">
                    <div class="detail-label">{{ __('Approval Status') }}</div>
                    <div class="detail-value">
                        <span class="badge badge-{{ $loan->approval_status }}">{{ ucfirst(str_replace('_', ' ', $loan->approval_status)) }}</span>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Approved By') }}</div>
                    <div class="detail-value">{{ $loan->approvedBy->name ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Approved At') }}</div>
                    <div class="detail-value">{{ $loan->approved_at ? $loan->approved_at->format('M d, Y H:i') : '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Funded By') }}</div>
                    <div class="detail-value">{{ $loan->fundedBy->name ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Funded At') }}</div>
                    <div class="detail-value">{{ $loan->funded_at ? $loan->funded_at->format('M d, Y H:i') : '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Rejection Reason') }}</div>
                    <div class="detail-value">{{ $loan->rejection_reason ?? '—' }}</div>
                </div>
            </div>

            @if ($loan->fundings->count() > 0)
            <div style="margin-top: 24px;">
                <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">{{ __('Funding History') }}</h4>
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Admin') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Fee') }}</th>
                            <th>{{ __('Net') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->fundings as $funding)
                        <tr>
                            <td>{{ $funding->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $funding->admin->name ?? '—' }}</td>
                            <td>{{ $loan->currency ?? 'USD' }} {{ number_format($funding->amount, 2) }}</td>
                            <td>{{ $loan->currency ?? 'USD' }} {{ number_format($funding->fee_deducted, 2) }}</td>
                            <td>{{ $loan->currency ?? 'USD' }} {{ number_format($funding->net_amount, 2) }}</td>
                            <td><span class="badge badge-{{ $funding->status }}">{{ ucfirst($funding->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Payment Schedule --}}
    <div class="section-card">
        <div class="section-header">
            <h3 class="section-title">{{ __('Payment Schedule') }}</h3>
        </div>
        <div class="section-body">
            @if ($loan->payments->count() > 0)
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th>{{ __('Period') }}</th>
                            <th>{{ __('Due Date') }}</th>
                            <th>{{ __('Amount Due') }}</th>
                            <th>{{ __('Principal') }}</th>
                            <th>{{ __('Interest') }}</th>
                            <th>{{ __('Fee') }}</th>
                            <th>{{ __('Amount Paid') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->payments->sortBy('period_number') as $payment)
                        <tr>
                            <td>{{ $payment->period_number }}</td>
                            <td>{{ $payment->due_date->format('M d, Y') }}</td>
                            <td>{{ $loan->currency ?? 'USD' }} {{ number_format($payment->amount_due, 2) }}</td>
                            <td>{{ $loan->currency ?? 'USD' }} {{ number_format($payment->principal_due, 2) }}</td>
                            <td>{{ $loan->currency ?? 'USD' }} {{ number_format($payment->interest_due, 2) }}</td>
                            <td>{{ $loan->currency ?? 'USD' }} {{ number_format($payment->fee_due, 2) }}</td>
                            <td>{{ $loan->currency ?? 'USD' }} {{ number_format($payment->amount_paid, 2) }}</td>
                            <td><span class="badge badge-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: var(--text-muted); text-align: center; padding: 40px;">{{ __('No payment schedule generated yet.') }}</p>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="section-card">
        <div class="section-header">
            <h3 class="section-title">{{ __('Actions') }}</h3>
        </div>
        <div class="section-body" style="display: flex; gap: 12px; flex-wrap: wrap;">
            @if ($loan->approval_status === \App\Models\Loan::APPROVAL_PENDING_REVIEW)
                <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" class="action-form">
                    @csrf
                    <button type="submit" class="btn-sm primary">{{ __('Approve') }}</button>
                </form>
                <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" class="action-form">
                    @csrf
                    <button type="submit" class="btn-sm danger">{{ __('Reject') }}</button>
                </form>
            @elseif ($loan->approval_status === \App\Models\Loan::APPROVAL_APPROVED && !$loan->isFullyFunded())
                <a href="{{ route('admin.loans.fund', $loan->id) }}" class="btn-sm success">{{ __('Fund Loan') }}</a>
            @elseif ($loan->approval_status === \App\Models\Loan::APPROVAL_FUNDED && $loan->status !== \App\Models\Loan::STATUS_ACTIVE)
                <form action="{{ route('admin.loans.disbursse', $loan->id) }}" method="POST" class="action-form">
                    @csrf
                    <button type="submit" class="btn-sm primary">{{ __('Disburse & Generate Schedule') }}</button>
                </form>
            @endif

            <a href="{{ route('admin.loans.index') }}" class="btn-sm info">{{ __('Back to List') }}</a>
        </div>
    </div>

</div>
@endsection