@extends('admin.layouts.master')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __($page_title) }}</h5>
        <a href="{{ route('admin.investment.assets.create') }}" class="btn btn--base">{{ __('Create') }}</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Symbol') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Offering') }}</th>
                        <th>{{ __('Risk') }}</th>
                        <th>{{ __('Yield %') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $a)
                    <tr>
                        <td>{{ $a->name }}</td>
                        <td>{{ $a->symbol }}</td>
                        <td>{{ ucfirst($a->asset_type) }}</td>
                        <td>{{ strtoupper(str_replace('_',' ',$a->offering_type)) }}</td>
                        <td>{{ $a->risk_score }}</td>
                        <td>{{ number_format($a->base_yield,2) }}</td>
                        <td><span class="badge {{ $a->status ? 'badge--success' : 'badge--danger' }}">{{ $a->status ? __('Active') : __('Inactive') }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.investment.assets.edit', $a->id) }}" class="btn btn-sm btn--info">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.investment.assets.delete', $a->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn--danger" onclick="return confirm('{{ __('Delete this asset?') }}')">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted">{{ __('No assets found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $assets->links() }}
    </div>
@endsection

