@extends('user.layouts.master')

@section('content')
<div class="dashboard-area mt-10">
    <div class="dashboard-header-wrapper">
        <h3 class="title">{{ __($page_title) }}</h3>
    </div>
</div>
<div class="custom-card mt-3 p-3">
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-3"><div class="small text-muted">{{ __('Principal') }}</div><div class="fw-semibold">{{ get_amount($loan->principal) }}</div></div>
        <div class="col-12 col-md-3"><div class="small text-muted">{{ __('Rate') }}</div><div class="fw-semibold">{{ number_format($loan->interest_rate,2) }}%</div></div>
        <div class="col-12 col-md-3"><div class="small text-muted">{{ __('Method') }}</div><div class="fw-semibold">{{ ucfirst($loan->interest_method) }}</div></div>
        <div class="col-12 col-md-3"><div class="small text-muted">{{ __('Frequency') }}</div><div class="fw-semibold">{{ ucfirst($loan->payment_frequency) }}</div></div>
    </div>
    <div class="table-responsive">
        <table class="custom-table">
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
                    <td><span class="badge {{ $p->status === 'paid' ? 'badge--success' : ($p->status === 'late' ? 'badge--danger' : 'badge--warning') }}">{{ ucfirst($p->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <a href="{{ route('user.loans.index') }}" class="btn--base">{{ __('Back to Loans') }}</a>
    </div>
</div>
@endsection

