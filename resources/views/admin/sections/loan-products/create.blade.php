@extends('admin.layouts.master')

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("admin.dashboard"),
        ]
    ], 'active' => __("Add Loan Product")])
@endsection

@section('content')
<div class="custom-card p-3">
    <form method="POST" action="{{ route('admin.loan.products.store') }}" class="row g-3">
        @csrf
        <div class="col-md-6">
            <label class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control" required maxlength="100">
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Interest Rate (%)') }}</label>
            <input type="number" step="0.0001" min="0" name="interest_rate" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Term (months)') }}</label>
            <input type="number" min="1" name="term_months" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Min Amount') }}</label>
            <input type="number" step="0.01" min="0" name="min_amount" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Max Amount') }}</label>
            <input type="number" step="0.01" min="0" name="max_amount" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('Description') }}</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Status') }}</label>
            <select name="status" class="form-select">
                <option value="1">{{ __('Active') }}</option>
                <option value="0">{{ __('Inactive') }}</option>
            </select>
        </div>
        <div class="col-12 d-flex justify-content-end">
            <button class="btn--base">{{ __('Create') }}</button>
        </div>
    </form>
</div>
@endsection

