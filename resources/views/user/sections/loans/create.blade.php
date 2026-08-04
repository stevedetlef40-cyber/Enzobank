@extends('user.layouts.rise-master')

@push('css')
<style>
.loan-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}
.loan-grid .am-field-group { margin-bottom: 0; }
.loan-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: rgba(59,130,246,0.1);
    border: 1px solid var(--accent, #3B82F6);
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 18px;
    font-size: 12.5px;
    line-height: 1.5;
    color: var(--text-secondary);
}
.loan-note svg { flex-shrink: 0; margin-top: 1px; color: var(--accent, #3B82F6); }
.loan-note ul {
    margin: 8px 0 0 0;
    padding-left: 18px;
}
.loan-note li {
    margin-bottom: 4px;
}
.loan-actions { margin-top: 20px; }
.product-card {
    border: 1.5px solid var(--border-color);
    border-radius: 14px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: var(--bg-card);
}
.product-card:hover {
    border-color: var(--accent);
}
.product-card.selected {
    border-color: var(--accent);
    background: rgba(59,130,246,0.05);
}
.product-card-title {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
}
.product-card-meta {
    display: flex;
    gap: 16px;
    font-size: 12px;
    color: var(--text-secondary);
}
@media (max-width: 560px) {
    .loan-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ $page_title }}</h1>
    <a href="{{ route('user.loans.index') }}" class="rw-section-link-pill">← {{ __('Back') }}</a>
</div>

<div class="am-body">
    <div class="am-card">
        <form method="POST" action="{{ route('user.loans.store') }}">
            @csrf
            <div class="loan-note">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                <div>
                    <strong>{{ __('Investment Loan Program') }}</strong>
                    <ul>
                        <li>{{ __('Loan funds are restricted for investment purposes only') }}</li>
                        <li>{{ __('You cannot withdraw the loan principal') }}</li>
                        <li>{{ __('Earnings can be withdrawn after making a qualifying deposit') }}</li>
                        <li>{{ __('A 2.5% withdrawal fee applies to earnings') }}</li>
                        <li>{{ __('Admin approval required before funds are released') }}</li>
                    </ul>
                </div>
            </div>

            <div class="loan-grid">
                <div class="am-field-group">
                    <label class="am-label">{{ __('Country') }}</label>
                    <div class="am-input-wrap">
                        <select name="country" required>
                            <option value="">{{ __('Select country') }}</option>
                            @foreach($countries as $c)
                                <option value="{{ $c }}" @selected(old('country') === $c)>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Currency') }}</label>
                    <div class="am-input-wrap">
                        <select name="currency" required>
                            @foreach($currencies as $cur)
                                <option value="{{ $cur }}" @selected(old('currency', 'USD') === $cur)>{{ $cur }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Loan Product Selection --}}
                <div class="am-field-group" style="grid-column: 1 / -1;">
                    <label class="am-label">{{ __('Choose Loan Product') }}</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px;">
                        @foreach($products as $p)
                        <div class="product-card" data-product-id="{{ $p->id }}" onclick="selectProduct(this, {{ $p->id }}, '{{ $p->interest_rate }}', {{ $p->term_months }}, '{{ $p->investmentPlan->name ?? 'None' }}', {{ $p->min_amount }}, {{ $p->max_amount }})">
                            <div class="product-card-title">{{ $p->name }}</div>
                            <div class="product-card-meta">
                                <span>{{ number_format($p->interest_rate, 2) }}% APR</span>
                                <span>{{ $p->term_months }} months</span>
                                <span>{{ old('currency', 'USD') }} {{ number_format($p->min_amount, 2) }} - {{ number_format($p->max_amount, 2) }}</span>
                            </div>
                            @if($p->investmentPlan)
                            <div style="margin-top: 8px; font-size: 12px; color: var(--accent);">{{ __('Linked to') }}: {{ $p->investmentPlan->name }} ({{ $p->investmentPlan->roi_percent }}% ROI, {{ $p->investmentPlan->duration_days }} days)</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="loan_product_id" id="loan_product_id">
                </div>

                <div class="am-field-group">
                    <label class="am-label">{{ __('Principal Amount') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" step="0.01" min="100" name="principal" required placeholder="0.00" id="principal_input">
                        <small style="color: var(--text-muted); display: block; margin-top: 4px;" id="principal_hint">{{ __('Minimum $100') }}</small>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Interest Rate (%)') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" step="0.0001" min="0" max="50" name="interest_rate" required placeholder="e.g. 5.0" id="interest_rate_input" readonly>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Term (months)') }}</label>
                    <div class="am-input-wrap">
                        <input type="number" min="3" max="120" name="term_months" required placeholder="e.g. 12" id="term_months_input" readonly>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Start Date') }}</label>
                    <div class="am-input-wrap">
                        <input type="date" name="start_date" min="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Investment Plan (Optional)') }}</label>
                    <div class="am-input-wrap">
                        <select name="investment_plan_id" id="investment_plan_select">
                            <option value="">{{ __('Select Investment Plan') }}</option>
                            @foreach($investmentPlans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} - {{ $plan->roi_percent }}% ROI, {{ $plan->duration_days }} days ({{ $plan->currency->code ?? 'USD' }} {{ number_format($plan->min_amount, 2) }} min)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="am-field-group">
                    <label class="am-label">{{ __('Loan Purpose (Optional)') }}</label>
                    <div class="am-input-wrap">
                        <textarea name="purpose" rows="3" placeholder="{{ __('Describe how you plan to use this loan for investments...') }}"></textarea>
                    </div>
                </div>
            </div>
            <div class="loan-actions">
                <button type="submit" class="am-btn">{{ __('Submit Application') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('script')
<script>
function selectProduct(card, productId, interestRate, termMonths, investmentPlanName, minAmount, maxAmount) {
    document.querySelectorAll('.product-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    document.getElementById('loan_product_id').value = productId;
    document.getElementById('interest_rate_input').value = interestRate;
    document.getElementById('term_months_input').value = termMonths;
    document.getElementById('principal_input').min = minAmount;
    document.getElementById('principal_input').max = maxAmount;
    document.getElementById('principal_hint').textContent = `Min: ${minAmount} | Max: ${maxAmount}`;
    
    // Auto-select investment plan if linked
    if (investmentPlanName && investmentPlanName !== 'None') {
        const select = document.getElementById('investment_plan_select');
        for (let option of select.options) {
            if (option.text.includes(investmentPlanName)) {
                option.selected = true;
                break;
            }
        }
    }
}
</script>
@endpush