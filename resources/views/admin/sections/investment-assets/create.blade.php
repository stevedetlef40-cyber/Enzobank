@extends('admin.layouts.master')
@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">{{ __($page_title) }}</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.investment.assets.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">{{ __('Name') }}</label>
                <input name="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Symbol') }}</label>
                <input name="symbol" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Asset Type') }}</label>
                <select name="asset_type" class="form-select">
                    @foreach(['stock','fund','bond','crypto','cash'] as $t)
                    <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Offering Type') }}</label>
                <select name="offering_type" class="form-select">
                    @foreach(['fixed_deposit','mutual_fund','gov_bond','corp_bond','stock','retirement'] as $t)
                    <option value="{{ $t }}">{{ strtoupper(str_replace('_',' ',$t)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Risk Level') }}</label>
                <input name="risk_level" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Risk Score (1-5)') }}</label>
                <input type="number" min="1" max="5" name="risk_score" class="form-control" value="3" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Current Price') }}</label>
                <input type="number" step="0.000001" min="0" name="current_price" class="form-control" value="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Base Yield (%)') }}</label>
                <input type="number" step="0.0001" min="0" name="base_yield" class="form-control" value="0">
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Tiers JSON') }}</label>
                <textarea name="tiers" class="form-control" rows="4" placeholder='[{"min":0,"max":5000,"rate":4.0},{"min":5000,"max":null,"rate":5.0}]'></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Maturities JSON') }}</label>
                <textarea name="maturities" class="form-control" rows="4" placeholder='[6,12,24]'></textarea>
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('Status') }}</label>
                <select name="status" class="form-select">
                    <option value="1">{{ __('Active') }}</option>
                    <option value="0">{{ __('Inactive') }}</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn--base">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
