@extends('admin.layouts.master')

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        ['name'  => __("Dashboard"), 'url'   => setRoute("admin.dashboard")],
    ], 'active' => __("Investment Plans")])
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-15">
        <div class="custom-card">
            <div class="card-header">
                <h6 class="title">{{ __("Add New Plan") }}</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.invest.plans.store') }}" class="row g-3">
                    @csrf
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">{{ __("Plan Name") }}<span class="text-danger">*</span></label>
                        <input type="text" class="form--control" name="name" placeholder="e.g. Basic Deluxe" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">{{ __("Min Amount ($)") }}<span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form--control" name="min_amount" placeholder="50" value="{{ old('min_amount') }}" required>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">{{ __("Max Amount ($)") }}</label>
                        <input type="number" step="0.01" min="0" class="form--control" name="max_amount" placeholder="999 (leave empty for unlimited)" value="{{ old('max_amount') }}">
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">{{ __("ROI (%)") }}<span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" max="1000" class="form--control" name="roi_percent" placeholder="15" value="{{ old('roi_percent') }}" required>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">{{ __("Duration (days)") }}<span class="text-danger">*</span></label>
                        <input type="number" min="1" class="form--control" name="duration_days" placeholder="30" value="{{ old('duration_days') }}" required>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">{{ __("Status") }}</label>
                        <select name="is_active" class="form--control nice-select">
                            <option value="1">{{ __("Active") }}</option>
                            <option value="0">{{ __("Inactive") }}</option>
                        </select>
                    </div>
                    <div class="col-xl-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn--base">{{ __("Add Plan") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="custom-card">
            <div class="card-header">
                <h6 class="title">{{ __("All Plans") }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>{{ __("Name") }}</th>
                                <th>{{ __("Min ($)") }}</th>
                                <th>{{ __("Max ($)") }}</th>
                                <th>{{ __("ROI %") }}</th>
                                <th>{{ __("Duration") }}</th>
                                <th>{{ __("Investments") }}</th>
                                <th>{{ __("Status") }}</th>
                                <th class="text-end">{{ __("Action") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plans as $plan)
                            <tr>
                                <td><strong>{{ $plan->name }}</strong></td>
                                <td>${{ number_format($plan->min_amount, 2) }}</td>
                                <td>{{ $plan->max_amount ? '$' . number_format($plan->max_amount, 2) : __('Unlimited') }}</td>
                                <td>{{ number_format($plan->roi_percent, 2) }}%</td>
                                <td>{{ $plan->duration_days }} {{ __("days") }}</td>
                                <td>{{ $plan->investments_count ?? $plan->investments()->count() }}</td>
                                <td>
                                    @include('admin.components.form.switcher',[
                                        'label' => '',
                                        'name'  => 'status',
                                        'value' => $plan->is_active,
                                        'options' => ['Active' => 1,'Inactive' => 0],
                                        'onload' => true,
                                        'permission' => 'admin.invest.plans.status',
                                        'attribute' => "data-target-url=".route('admin.invest.plans.status', $plan->id),
                                    ])
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn--info edit-plan-btn"
                                        data-id="{{ $plan->id }}"
                                        data-name="{{ $plan->name }}"
                                        data-min="{{ $plan->min_amount }}"
                                        data-max="{{ $plan->max_amount }}"
                                        data-roi="{{ $plan->roi_percent }}"
                                        data-days="{{ $plan->duration_days }}"
                                        data-active="{{ $plan->is_active ? 1 : 0 }}">
                                        <i class="las la-pencil-alt"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.invest.plans.delete', $plan->id) }}" class="d-inline" onsubmit="return confirm('{{ __("Delete this plan?") }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn--danger"><i class="las la-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">{{ __("No plans found") }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $plans->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Edit Plan Modal --}}
<div id="plan-edit-modal" class="mfp-hide large">
    <div class="modal-data">
        <div class="modal-header px-0">
            <h5 class="modal-title">{{ __("Edit Plan") }}</h5>
        </div>
        <div class="modal-form-data">
            <form class="modal-form" method="POST" action="{{ route('admin.invest.plans.update', 0) }}">
                @csrf
                @method("PUT")
                <div class="row mb-10-none">
                    <div class="col-xl-12 form-group">
                        <label>{{ __("Plan Name") }}<span>*</span></label>
                        <input type="text" class="form--control" name="name" required>
                    </div>
                    <div class="col-xl-6 form-group">
                        <label>{{ __("Min Amount ($)") }}<span>*</span></label>
                        <input type="number" step="0.01" min="0" class="form--control" name="min_amount" required>
                    </div>
                    <div class="col-xl-6 form-group">
                        <label>{{ __("Max Amount ($)") }}</label>
                        <input type="number" step="0.01" min="0" class="form--control" name="max_amount" placeholder="{{ __("Empty = unlimited") }}">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label>{{ __("ROI (%)") }}<span>*</span></label>
                        <input type="number" step="0.01" min="0" max="1000" class="form--control" name="roi_percent" required>
                    </div>
                    <div class="col-xl-6 form-group">
                        <label>{{ __("Duration (days)") }}<span>*</span></label>
                        <input type="number" min="1" class="form--control" name="duration_days" required>
                    </div>
                    <div class="col-xl-12 form-group">
                        <label>{{ __("Status") }}</label>
                        <select name="is_active" class="form--control nice-select">
                            <option value="1">{{ __("Active") }}</option>
                            <option value="0">{{ __("Inactive") }}</option>
                        </select>
                    </div>
                    <div class="col-xl-12 form-group d-flex align-items-center justify-content-between mt-4">
                        <button type="button" class="btn btn--danger modal-close">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn--base">{{ __("Update") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function(){
    $(document).on('click', '.edit-plan-btn', function(){
        var modal = $('#plan-edit-modal');
        var action = '{{ route("admin.invest.plans.update", ":id") }}';
        action = action.replace(':id', $(this).data('id'));
        modal.find('form').attr('action', action);
        modal.find('input[name=name]').val($(this).data('name'));
        modal.find('input[name=min_amount]').val($(this).data('min'));
        modal.find('input[name=max_amount]').val($(this).data('max') || '');
        modal.find('input[name=roi_percent]').val($(this).data('roi'));
        modal.find('input[name=duration_days]').val($(this).data('days'));
        modal.find('select[name=is_active]').val($(this).data('active')).niceSelect('update');
        openModalBySelector('#plan-edit-modal');
    });
});
</script>
@endpush
