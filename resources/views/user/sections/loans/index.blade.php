@extends('user.layouts.master')

@section('content')
<div class="dashboard-list-area mt-20">
    <div class="dashboard-header-wrapper">
        <h4 class="title">{{ __($page_title) }}</h4>
        <div class="dashboard-btn-wrapper d-flex align-items-center gap-2">
            <div class="header-search-wrapper">
                <div class="position-relative">
                    <input class="form-control search" type="text" name="q" placeholder="{{ __('Search by product or status') }}" aria-label="Search">
                    <span class="las la-search"></span>
                </div>
            </div>
            <a href="{{ route('user.loans.create') }}" class="btn--base"><i class="las la-plus me-1"></i> {{ __('Apply Loan') }}</a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-6">
            <div class="custom-card p-3">
                <h6 class="mb-2">{{ __('Loan Overview') }}</h6>
                <div id="loanSummaryChart" data-endpoint="{{ route('user.loans.stats') }}" style="min-height: 260px;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="custom-card p-3">
                <h6 class="mb-2">{{ __('Upcoming Payments') }}</h6>
                <div id="upcomingPayments" class="small"></div>
            </div>
        </div>
    </div>

    <div class="table-area mt-10">
        <div class="table-wrapper">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Principal') }}</th>
                            <th>{{ __('Rate') }}</th>
                            <th>{{ __('Term') }}</th>
                            <th>{{ __('Balance') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Next Due') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="loans-table">
                        @forelse($loans as $loan)
                        <tr>
                            <td>{{ $loan->product->name ?? __('Custom') }}</td>
                            <td>{{ get_amount($loan->principal) }}</td>
                            <td>{{ number_format($loan->interest_rate,2) }}%</td>
                            <td>{{ $loan->term_months }} {{ __('mo') }}</td>
                            <td>{{ get_amount($loan->balance_principal) }}</td>
                            <td><span class="badge {{ $loan->status == 'active' ? 'badge--success' : ($loan->status == 'pending' ? 'badge--warning' : 'badge--dark') }}">{{ ucfirst($loan->status) }}</span></td>
                            <td>{{ $loan->next_due_date ? $loan->next_due_date->format('Y-m-d') : '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('user.loans.schedule', $loan->id) }}" class="btn btn-sm btn--info">{{ __('Schedule') }}</a>
                                <form method="POST" action="{{ route('user.loans.pay.next') }}" class="d-inline d-flex gap-1 align-items-center justify-content-end">
                                    @csrf
                                    <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control form-control-sm" style="width:120px" placeholder="{{ __('Amount') }}">
                                    <button class="btn btn-sm btn--success">{{ __('Pay Next') }}</button>
                                </form>
                                <a href="{{ route('user.loans.edit', $loan->id) }}" class="btn btn-sm btn--base">{{ __('Edit') }}</a>
                                <form method="POST" action="{{ route('user.loans.delete', $loan->id) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn--danger" onclick="return confirm('{{ __('Delete this loan?') }}')">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">{{ __('No loans found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ $loans->links() }}
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('public/frontend/js/apexcharts.js') }}"></script>
<script>
    function renderLoanCharts(stats) {
        const el = document.querySelector('#loanSummaryChart');
        el.innerHTML = '';
        const series = [{
            name: "{{ __('Principal') }}",
            data: [stats.total_principal]
        },{
            name: "{{ __('Balance') }}",
            data: [stats.total_balance]
        }];
        const options = {
            chart: { type: 'bar', height: 260, toolbar: { show: false } },
            series: series,
            xaxis: { categories: ["{{ __('Loans') }}"] },
            colors: ['#5B7F6B','#A9BEB0'],
            plotOptions: { bar: { horizontal: false, columnWidth: '40%' } },
            dataLabels: { enabled: false },
            legend: { position: 'bottom' }
        };
        new ApexCharts(el, options).render();

        const upc = document.querySelector('#upcomingPayments');
        upc.innerHTML = stats.upcoming.map(i => `<div class="d-flex justify-content-between border-bottom py-1"><span>${i.due_date}</span><strong>${i.amount_due}</strong></div>`).join('') || '<div class="text-muted">{{ __('No upcoming payments') }}</div>';
    }
    async function fetchStats() {
        const endpoint = document.querySelector('#loanSummaryChart').dataset.endpoint;
        try {
            const res = await fetch(endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            renderLoanCharts(data);
        } catch(e) { console.error(e); }
    }
    fetchStats();
    setInterval(fetchStats, 30000);

    document.querySelector('.search').addEventListener('input', function() {
        const val = this.value;
        const url = new URL(window.location.href);
        if(val) url.searchParams.set('q', val); else url.searchParams.delete('q');
        window.location.href = url.toString();
    });
</script>
@endpush
