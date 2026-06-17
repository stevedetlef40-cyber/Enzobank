@extends('user.layouts.master')

@section('content')
<div class="dashboard-area mt-10">
    <div class="dashboard-header-wrapper">
        <h3 class="title">{{ __($page_title) }}</h3>
    </div>
</div>
<div class="custom-card mt-3 p-3">
    <form method="POST" action="{{ route('user.loans.store') }}" class="row g-3">
        @csrf
        <div class="col-md-6">
            <label class="form-label">{{ __('Loan Product') }}</label>
            <select name="loan_product_id" class="form-select">
                <option value="">{{ __('Custom') }}</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} ({{ number_format($p->interest_rate,2) }}% / {{ $p->term_months }} {{ __('mo') }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('Principal Amount') }}</label>
            <input type="number" step="0.01" min="0.01" class="form-control" name="principal" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Interest Rate (%)') }}</label>
            <input type="number" step="0.0001" min="0" class="form-control" name="interest_rate" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Term (months)') }}</label>
            <input type="number" min="1" max="480" class="form-control" name="term_months" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Start Date') }}</label>
            <input type="date" class="form-control" name="start_date">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Interest Method') }}</label>
            <select name="interest_method" class="form-select">
                <option value="amortized">{{ __('Amortized') }}</option>
                <option value="simple">{{ __('Simple') }}</option>
                <option value="compound">{{ __('Compound') }}</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Payment Frequency') }}</label>
            <select name="payment_frequency" class="form-select">
                <option value="monthly">{{ __('Monthly') }}</option>
                <option value="biweekly">{{ __('Biweekly') }}</option>
                <option value="weekly">{{ __('Weekly') }}</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Grace Days') }}</label>
            <input type="number" min="0" max="60" class="form-control" name="grace_days" value="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Late Fee Type') }}</label>
            <select name="late_fee_type" class="form-select">
                <option value="percent">{{ __('Percent') }}</option>
                <option value="flat">{{ __('Flat') }}</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Late Fee Value') }}</label>
            <input type="number" step="0.0001" min="0" class="form-control" name="late_fee_value" value="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Early Settlement Fee (%)') }}</label>
            <input type="number" step="0.0001" min="0" class="form-control" name="early_settlement_fee_percent" value="0">
        </div>
        <div class="col-12 d-flex justify-content-end">
            <button class="btn--base">{{ __('Submit Application') }}</button>
        </div>
    </form>
</div>
@endsection
