@extends('user.layouts.master')

@section('content')
<div class="dashboard-list-area mt-20">
    <div class="dashboard-header-wrapper">
        <h4 class="title">{{ __($page_title) }}</h4>
        <div class="dashboard-btn-wrapper d-flex align-items-center gap-2">
            <div class="header-search-wrapper">
                <div class="position-relative">
                    <input class="form-control search" type="text" name="q" placeholder="{{ __('Search by name') }}" aria-label="Search">
                    <span class="las la-search"></span>
                </div>
            </div>
            <a href="{{ route('user.portfolios.create') }}" class="btn--base"><i class="las la-plus me-1"></i> {{ __('Create Portfolio') }}</a>
        </div>
    </div>

    <div class="table-area mt-10">
        <div class="table-wrapper">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Holdings') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="portfolios-table">
                        @forelse($portfolios as $pf)
                        <tr>
                            <td>{{ $pf->name }}</td>
                            <td>{{ $pf->holdings_count }}</td>
                            <td>{{ $pf->created_at->format('Y-m-d') }}</td>
                            <td class="text-end">
                                <a href="{{ route('user.portfolios.show', $pf->id) }}" class="btn btn-sm btn--base">{{ __('Open') }}</a>
                                <a href="{{ route('user.portfolios.edit', $pf->id) }}" class="btn btn-sm btn--info">{{ __('Edit') }}</a>
                                <form method="POST" action="{{ route('user.portfolios.delete', $pf->id) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn--danger" onclick="return confirm('{{ __('Delete this portfolio?') }}')">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">{{ __('No portfolios found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ $portfolios->links() }}
    </div>
</div>
@endsection

@push('script')
<script>
    document.querySelector('.search').addEventListener('input', function() {
        const val = this.value;
        const url = new URL(window.location.href);
        if(val) url.searchParams.set('q', val); else url.searchParams.delete('q');
        window.location.href = url.toString();
    });
</script>
@endpush

