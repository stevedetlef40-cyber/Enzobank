@extends('admin.layouts.master')

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        ['name'  => __("Dashboard"), 'url'   => setRoute("admin.dashboard")],
    ], 'active' => __("Investments")])
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex flex-wrap gap-2">
            @php
                $statuses = ['all' => 'All', 'pending' => 'Pending', 'active' => 'Active', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
            @endphp
            @foreach($statuses as $key => $label)
                @php
                    $isActive = (!request('status') && $key === 'all') || request('status') === $key;
                @endphp
                <a href="{{ route('admin.invest.index', $key === 'all' ? [] : ['status' => $key]) }}"
                   class="btn btn-sm {{ $isActive ? 'btn--base' : 'btn--dark' }}">
                    {{ __($label) }} ({{ $counts[$key] ?? 0 }})
                </a>
            @endforeach
        </div>
    </div>
</div>

<div class="custom-card">
    <div class="card-header">
        <h6 class="title">{{ __("All Investments") }}</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>{{ __("User") }}</th>
                        <th>{{ __("Plan") }}</th>
                        <th>{{ __("Amount") }}</th>
                        <th>{{ __("Expected Return") }}</th>
                        <th>{{ __("Method") }}</th>
                        <th>{{ __("TX Hash") }}</th>
                        <th>{{ __("Proof") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th>{{ __("Date") }}</th>
                        <th class="text-end">{{ __("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($investments as $inv)
                    <tr>
                        <td>
                            <strong>{{ $inv->user->fullname ?? 'N/A' }}</strong>
                            <div class="small text-muted">{{ $inv->user->email ?? '' }}</div>
                        </td>
                        <td>{{ $inv->plan->name ?? 'N/A' }}</td>
                        <td>${{ number_format($inv->amount, 2) }}</td>
                        <td>${{ number_format($inv->expected_return ?? 0, 2) }}</td>
                        <td>{{ $inv->payment_method ?? 'N/A' }}</td>
                        <td style="max-width:180px;">
                            <span class="text-muted" style="word-break:break-all;font-size:12px;">{{ $inv->tx_hash ?? '—' }}</span>
                        </td>
                        <td>
                            @if($inv->proof_url)
                                <button type="button" class="btn btn-sm btn--info view-proof-btn" data-proof="{{ $inv->proof_url }}">
                                    <i class="las la-image"></i> {{ __("View") }}
                                </button>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badge = match($inv->status) {
                                    'pending'   => 'badge--warning',
                                    'active'    => 'badge--success',
                                    'completed' => 'badge--info',
                                    'cancelled' => 'badge--danger',
                                    default     => 'badge--dark',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ ucfirst($inv->status) }}</span>
                        </td>
                        <td>{{ $inv->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            @if($inv->status === 'pending')
                                <form method="POST" action="{{ route('admin.invest.approve', $inv->id) }}" class="d-inline" onsubmit="return confirm('{{ __("Approve this investment?") }}')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn--success"><i class="las la-check"></i> {{ __("Approve") }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.invest.reject', $inv->id) }}" class="d-inline" onsubmit="return confirm('{{ __("Reject this investment?") }}')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn--danger"><i class="las la-times"></i> {{ __("Reject") }}</button>
                                </form>
                            @elseif($inv->status === 'active')
                                <form method="POST" action="{{ route('admin.invest.credit', $inv->id) }}" class="d-inline" onsubmit="return confirm('{{ __("Credit earnings to user wallet?") }}')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn--base"><i class="las la-wallet"></i> {{ __("Credit Earnings") }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">{{ __("No investments found") }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $investments->links() }}
    </div>
</div>

{{-- Proof modal --}}
<div id="invest-proof-modal" class="mfp-hide large">
    <div class="modal-data">
        <div class="modal-header px-0">
            <h5 class="modal-title">{{ __("Payment Proof") }}</h5>
        </div>
        <div class="modal-form-data text-center">
            <img id="invest-proof-img" src="" alt="{{ __("Payment Proof") }}" style="max-width:100%;max-height:70vh;border-radius:8px;">
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function(){
    $(document).on('click', '.view-proof-btn', function(){
        var proof = $(this).data('proof');
        $('#invest-proof-img').attr('src', '{{ asset('') }}' + proof);
        openModalBySelector('#invest-proof-modal');
    });
});
</script>
@endpush
