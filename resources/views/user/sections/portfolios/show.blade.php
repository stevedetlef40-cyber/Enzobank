@extends('user.layouts.master')

@section('content')
<div class="dashboard-area mt-10">
    <div class="dashboard-header-wrapper">
        <h3 class="title">{{ __($page_title) }}</h3>
    </div>
</div>
<div class="row g-3">
    <div class="col-12 col-lg-6">
        <div class="custom-card p-3">
            <h6 class="mb-2">{{ __('Allocation') }}</h6>
            <div id="allocationChart" data-endpoint="{{ route('user.portfolios.performance', $portfolio->id) }}" style="min-height: 280px;"></div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="custom-card p-3">
            <h6 class="mb-2">{{ __('Performance Timeline') }}</h6>
            <div id="performanceChart" style="min-height: 280px;"></div>
        </div>
    </div>
</div>

<div class="custom-card mt-3 p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">{{ __('Holdings') }}</h6>
        <form method="POST" action="{{ route('user.portfolios.holding.add', $portfolio->id) }}" class="d-flex gap-2 flex-wrap">
            @csrf
            <select name="asset_id" class="form-select" required>
                <option value="">{{ __('Select asset') }}</option>
                @foreach($assets as $a)
                <option value="{{ $a->id }}">{{ $a->symbol }} — {{ $a->name }}</option>
                @endforeach
            </select>
            <input type="number" step="0.00000001" min="0.00000001" name="quantity" class="form-control" placeholder="{{ __('Quantity') }}" required>
            <input type="number" step="0.000001" min="0" name="price" class="form-control" placeholder="{{ __('Price') }}" required>
            <button class="btn--base">{{ __('Add') }}</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>{{ __('Asset') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('Avg. Cost') }}</th>
                    <th>{{ __('Current Price') }}</th>
                    <th>{{ __('Value') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($portfolio->holdings as $h)
                <tr>
                    <td>{{ $h->asset->symbol }} — {{ $h->asset->name }}</td>
                    <td>{{ ucfirst($h->asset->asset_type) }}</td>
                    <td>{{ rtrim(rtrim(number_format($h->quantity,8,'.',''), '0'),'.') }}</td>
                    <td>{{ get_amount($h->avg_cost) }}</td>
                    <td>{{ get_amount($h->asset->current_price) }}</td>
                    <td>{{ get_amount($h->quantity * $h->asset->current_price) }}</td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('user.portfolios.holding.remove', $portfolio->id) }}" class="d-inline">
                            @csrf @method('DELETE')
                            <input type="hidden" name="holding_id" value="{{ $h->id }}">
                            <button class="btn btn-sm btn--danger" onclick="return confirm('{{ __('Remove holding?') }}')">{{ __('Remove') }}</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('public/frontend/js/apexcharts.js') }}"></script>
<script>
async function loadPerformance() {
    const endpoint = document.querySelector('#allocationChart').dataset.endpoint;
    try {
        const res = await fetch(endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();

        const alloc = new ApexCharts(document.querySelector('#allocationChart'), {
            chart: { type: 'donut', height: 280 },
            labels: data.allocation.map(i => i.name),
            series: data.allocation.map(i => i.y),
            colors: ['#5B7F6B','#A9BEB0','#7BA690','#D9E3DD','#3C5846'],
            legend: { position: 'bottom' }
        });
        alloc.render();

        const perf = new ApexCharts(document.querySelector('#performanceChart'), {
            chart: { type: 'area', height: 280, toolbar: { show: false } },
            series: [{ name: "{{ __('Net Flow') }}", data: data.timeline }],
            xaxis: { type: 'datetime' },
            dataLabels: { enabled: false },
            colors: ['#5B7F6B']
        });
        perf.render();
    } catch(e) { console.error(e); }
}
loadPerformance();
setInterval(loadPerformance, 30000);
</script>
@endpush

