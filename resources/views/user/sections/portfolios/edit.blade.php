@extends('user.layouts.master')

@section('content')
<div class="dashboard-area mt-10">
    <div class="dashboard-header-wrapper">
        <h3 class="title">{{ __($page_title) }}</h3>
    </div>
</div>
<div class="custom-card mt-3 p-3">
    <form method="POST" action="{{ route('user.portfolios.update', $portfolio->id) }}" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-md-6">
            <label class="form-label">{{ __('Portfolio Name') }}</label>
            <input type="text" class="form-control" name="name" required maxlength="100" value="{{ $portfolio->name }}">
        </div>
        <div class="col-12 d-flex justify-content-end">
            <button class="btn--base">{{ __('Save Changes') }}</button>
        </div>
    </form>
</div>
@endsection

