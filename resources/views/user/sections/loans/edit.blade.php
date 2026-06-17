@extends('user.layouts.master')

@section('content')
<div class="dashboard-area mt-10">
    <div class="dashboard-header-wrapper">
        <h3 class="title">{{ __($page_title) }}</h3>
    </div>
</div>
<div class="custom-card mt-3 p-3">
    <form method="POST" action="{{ route('user.loans.update', $loan->id) }}" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-md-4">
            <label class="form-label">{{ __('Interest Rate (%)') }}</label>
            <input type="number" step="0.0001" min="0" class="form-control" name="interest_rate" required value="{{ $loan->interest_rate }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Term (months)') }}</label>
            <input type="number" min="1" max="480" class="form-control" name="term_months" required value="{{ $loan->term_months }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Start Date') }}</label>
            <input type="date" class="form-control" name="start_date" value="{{ $loan->start_date?->format('Y-m-d') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Interest Method') }}</label>
            <select name="interest_method" class="form-select">
                @foreach(['amortized' => __('Amortized'), 'simple' => __('Simple'), 'compound' => __('Compound')] as $val => $label)
                    <option value="{{ $val }}" @selected($loan->interest_method === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Payment Frequency') }}</label>
            <select name="payment_frequency" class="form-select">
                @foreach(['monthly' => __('Monthly'), 'biweekly' => __('Biweekly'), 'weekly' => __('Weekly')] as $val => $label)
                    <option value="{{ $val }}" @selected($loan->payment_frequency === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Grace Days') }}</label>
            <input type="number" min="0" max="60" class="form-control" name="grace_days" value="{{ $loan->grace_days }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Late Fee Type') }}</label>
            <select name="late_fee_type" class="form-select">
                @foreach(['percent' => __('Percent'), 'flat' => __('Flat')] as $val => $label)
                    <option value="{{ $val }}" @selected($loan->late_fee_type === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Late Fee Value') }}</label>
            <input type="number" step="0.0001" min="0" class="form-control" name="late_fee_value" value="{{ $loan->late_fee_value }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Early Settlement Fee (%)') }}</label>
            <input type="number" step="0.0001" min="0" class="form-control" name="early_settlement_fee_percent" value="{{ $loan->early_settlement_fee_percent }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Status') }}</label>
            <select name="status" class="form-select" required>
                @foreach(['pending','active','closed','defaulted'] as $s)
                    <option value="{{ $s }}" @selected($loan->status === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 d-flex justify-content-end">
            <button class="btn--base">{{ __('Save Changes') }}</button>
        </div>
    </form>
</div>
@endsection
