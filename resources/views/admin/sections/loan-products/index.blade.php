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
    ], 'active' => __("Loan Products")])
@endsection

@section('content')
<div class="table-area">
    <div class="table-wrapper">
        <div class="table-header">
            <h5 class="title">{{ __($page_title) }}</h5>
            <div class="table-btn-area d-flex align-items-center gap-2">
                <form method="GET" action="{{ route('admin.loan.products.index') }}" class="d-flex align-items-center">
                    <input class="form-control" type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Search by name') }}">
                </form>
                <a href="{{ route('admin.loan.products.create') }}" class="btn--base">{{ __('Add Product') }}</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Rate') }}</th>
                        <th>{{ __('Term') }}</th>
                        <th>{{ __('Min') }}</th>
                        <th>{{ __('Max') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ number_format($p->interest_rate,2) }}%</td>
                        <td>{{ $p->term_months }} {{ __('mo') }}</td>
                        <td>{{ get_amount($p->min_amount) }}</td>
                        <td>{{ get_amount($p->max_amount) }}</td>
                        <td><span class="badge {{ $p->status ? 'badge--success':'badge--danger' }}">{{ $p->status ? __('Active'):__('Inactive') }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.loan.products.edit',$p->id) }}" class="btn btn-sm btn--info">{{ __('Edit') }}</a>
                            <form method="POST" action="{{ route('admin.loan.products.delete',$p->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn--danger" onclick="return confirm('{{ __('Delete this product?') }}')">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">{{ __('No products found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ get_paginate($products) }}
    </div>
@endsection

