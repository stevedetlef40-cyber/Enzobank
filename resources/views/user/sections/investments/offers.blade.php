@extends('user.layouts.master')

@section('content')
<div class="dashboard-list-area mt-20">
    <div class="dashboard-header-wrapper">
        <h4 class="title">{{ __($page_title) }}</h4>
        <div class="dashboard-btn-wrapper d-flex align-items-center gap-2">
            <form method="GET" class="d-flex gap-2">
                <select name="type" class="form-select">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach(['fixed_deposit'=>__('Fixed Deposits'),'mutual_fund'=>__('Mutual Funds'),'gov_bond'=>__('Government Bonds'),'corp_bond'=>__('Corporate Bonds'),'stock'=>__('Stocks'),'retirement'=>__('Retirement Accounts')] as $t => $label)
                        <option value="{{ $t }}" @selected(request('type')===$t)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="btn--base">{{ __('Filter') }}</button>
            </form>
        </div>
    </div>

    <div class="custom-card p-3 mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label">{{ __('Investment Amount') }}</label>
                <input type="number" step="0.01" min="0" id="cmp-amount" class="form-control" value="1000">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">{{ __('Maturity (months)') }}</label>
                <input type="number" min="1" id="cmp-months" class="form-control" value="12">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">{{ __('Compounding') }}</label>
                <select id="cmp-frequency" class="form-select">
                    <option value="12">{{ __('Monthly') }}</option>
                    <option value="4">{{ __('Quarterly') }}</option>
                    <option value="1">{{ __('Annually') }}</option>
                    <option value="0">{{ __('Simple') }}</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <button class="btn--base w-100" id="cmp-calc">{{ __('Recalculate') }}</button>
            </div>
        </div>
    </div>

    <div class="row g-3" id="offers-grid" data-assets='@json($assetPayload)'>
        @foreach($assets as $a)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="custom-card p-3 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="fw-semibold">{{ $a->name }}</div>
                        <div class="small text-muted">{{ strtoupper(str_replace('_',' ', $a->offering_type)) }} • {{ $a->symbol }}</div>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-{{ $a->risk_score >=4 ? 'danger' : ($a->risk_score==3 ? 'warning' : 'success') }}">{{ __('Risk') }} {{ $a->risk_score }}</div>
                    </div>
                </div>
                <div class="mt-1">
                    <div class="small text-muted">{{ __('Tiered Rates') }}</div>
                    <div class="small">
                        @php $tiers = $a->tiers ?? []; @endphp
                        @if($tiers)
                            @foreach($tiers as $t)
                                <span class="me-2">{{ get_amount($t['min']) }}–{{ $t['max'] ? get_amount($t['max']) : __('∞') }}: {{ number_format($t['rate'],2) }}%</span>
                            @endforeach
                        @else
                            <span>{{ number_format($a->base_yield,2) }}%</span>
                        @endif
                    </div>
                </div>
                <div class="mt-2">
                    <div class="small text-muted">{{ __('Maturities') }}</div>
                    <div class="small">{{ collect($a->maturities ?? [6,12,24])->implode(', ') }} {{ __('months') }}</div>
                </div>
                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-muted">{{ __('Projected Earnings') }}</div>
                        <div class="fw-semibold" data-proj-earn="asset-{{ $a->id }}">—</div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">{{ __('Projected ROI') }}</div>
                        <div class="fw-semibold" data-proj-roi="asset-{{ $a->id }}">—</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%" data-goal-progress="asset-{{ $a->id }}"></div>
                    </div>
                    <div class="d-flex justify-content-between small mt-1">
                        <span>{{ __('Goal Progress') }}</span>
                        <span data-goal-text="asset-{{ $a->id }}">0%</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $assets->links() }}
</div>
@endsection

@push('script')
<script>
function tierRate(tiers, base, amount) {
    if (!tiers || !tiers.length) return base;
    let r = base;
    for (const t of tiers) {
        const min = parseFloat(t.min ?? 0);
        const max = t.max ? parseFloat(t.max) : Infinity;
        if (amount >= min && amount <= max) {
            r = parseFloat(t.rate);
            break;
        }
    }
    return r;
}
function projected(amount, months, rate, compPeriods) {
    const years = months / 12.0;
    if (compPeriods > 0) {
        const r = rate/100.0;
        const f = Math.pow(1 + r/compPeriods, compPeriods*years);
        const value = amount * f;
        return { value, earnings: value - amount, roi: ((value-amount)/amount)*100 };
    } else {
        const earnings = amount * (rate/100.0) * years;
        const value = amount + earnings;
        return { value, earnings, roi: (earnings/amount)*100 };
    }
}
function updateProjections() {
    const amount = parseFloat(document.getElementById('cmp-amount').value || 0);
    const months = parseInt(document.getElementById('cmp-months').value || 0);
    const comp = parseInt(document.getElementById('cmp-frequency').value);
    const grid = document.getElementById('offers-grid');
    const assets = JSON.parse(grid.dataset.assets || '[]');
    assets.forEach(a => {
        const rate = tierRate(a.tiers, a.base_yield, amount);
        const p = projected(amount, months, rate, comp);
        const earnEl = document.querySelector(`[data-proj-earn="asset-${a.id}"]`);
        const roiEl = document.querySelector(`[data-proj-roi="asset-${a.id}"]`);
        if (earnEl) earnEl.textContent = p.earnings.toFixed(2);
        if (roiEl) roiEl.textContent = p.roi.toFixed(2) + '%';
        const goalBar = document.querySelector(`[data-goal-progress="asset-${a.id}"]`);
        const goalText = document.querySelector(`[data-goal-text="asset-${a.id}"]`);
        const target = amount > 0 ? amount : 1;
        const progress = Math.min(100, (p.value/ (target*1.2)) * 100);
        if (goalBar) goalBar.style.width = progress.toFixed(0) + '%';
        if (goalText) goalText.textContent = progress.toFixed(0) + '%';
    });
}
document.getElementById('cmp-calc').addEventListener('click', function(e){ e.preventDefault(); updateProjections(); });
['cmp-amount','cmp-months','cmp-frequency'].forEach(id => {
    document.getElementById(id).addEventListener('input', updateProjections);
    document.getElementById(id).addEventListener('change', updateProjections);
});
updateProjections();
</script>
@endpush
