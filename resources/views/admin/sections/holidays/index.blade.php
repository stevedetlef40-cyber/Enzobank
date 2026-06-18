@extends('admin.layouts.master')
@section('content')
<div class="row g-3">
    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">{{ __('Add Holiday') }}</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.holidays.store') }}" class="row g-2">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Date') }}</label>
                        <input type="date" name="holiday_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Region') }}</label>
                        <input type="text" name="region" class="form-control" maxlength="50">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" name="name" class="form-control" maxlength="120">
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn--base">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">{{ __('Holidays') }}</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Region') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($holidays as $h)
                            <tr>
                                <td>{{ $h->holiday_date->format('Y-m-d') }}</td>
                                <td>{{ $h->region ?? '—' }}</td>
                                <td>{{ $h->name ?? '—' }}</td>
                                <td class="text-end">
                                    <form action="{{ route('admin.holidays.delete', $h->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn--danger" onclick="return confirm('{{ __('Delete this holiday?') }}')">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">{{ __('No holidays added') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $holidays->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
