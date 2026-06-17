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
    ], 'active' => __("Edit Asset")])
@endsection

@section('content')
<div class="custom-card p-3">
    <form method="POST" action="{{ route('admin.investment.assets.update',$asset->id) }}" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-md-4">
            <label class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control" required maxlength="100" value="{{ $asset->name }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">{{ __('Symbol') }}</label>
            <input type="text" name="symbol" class="form-control" required maxlength="20" value="{{ $asset->symbol }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Type') }}</label>
            @php $types = ['stock','fund','bond','crypto','cash']; @endphp
            <select name="asset_type" class="form-select">
                @foreach($types as $t)
                <option value="{{ $t }}" @selected($asset->asset_type === $t)>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Offering Type') }}</label>
            @php $offerings = ['fixed_deposit','mutual_fund','gov_bond','corp_bond','stock','retirement']; @endphp
            <select name="offering_type" class="form-select">
                @foreach($offerings as $t)
                <option value="{{ $t }}" @selected($asset->offering_type === $t)>{{ strtoupper(str_replace('_',' ',$t)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Risk Level') }}</label>
            <input type="text" name="risk_level" class="form-control" maxlength="50" value="{{ $asset->risk_level }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Risk Score (1-5)') }}</label>
            <input type="number" min="1" max="5" name="risk_score" class="form-control" required value="{{ $asset->risk_score }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Current Price') }}</label>
            <input type="number" step="0.000001" min="0" name="current_price" class="form-control" required value="{{ $asset->current_price }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Base Yield (%)') }}</label>
            <input type="number" step="0.0001" min="0" name="base_yield" class="form-control" value="{{ $asset->base_yield }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('Tiers JSON') }}</label>
            <textarea name="tiers" class="form-control" rows="4">{{ $asset->tiers ? json_encode($asset->tiers) : '' }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('Maturities JSON') }}</label>
            <textarea name="maturities" class="form-control" rows="4">{{ $asset->maturities ? json_encode($asset->maturities) : '' }}</textarea>
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Status') }}</label>
            <select name="status" class="form-select">
                <option value="1" @selected($asset->status)>{{ __('Active') }}</option>
                <option value="0" @selected(!$asset->status)>{{ __('Inactive') }}</option>
            </select>
        </div>
        <div class="col-12 d-flex justify-content-end">
            <button class="btn--base">{{ __('Save Changes') }}</button>
        </div>
    </form>
</div>
@endsection
