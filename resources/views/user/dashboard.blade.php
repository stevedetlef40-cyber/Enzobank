@extends('user.layouts.master')

@section('content')
@php
    $default_currency = get_default_currency_code();
    $user = auth()->user();
@endphp

<!-- Mobile Currency Balance Cards -->
<div class="d-lg-none mobile-dashboard">

    <!-- USD Balance Card -->
    <div class="mobile-balance-card">
        <div class="mobile-balance-card-header">
            <div class="mobile-balance-currency-info">
                <span class="currency-flag">🇺🇸</span>
                <span class="currency-label">{{ __('USD balance') }}</span>
                <i class="las la-angle-down"></i>
            </div>
            <div class="mobile-balance-card-settings">
                <i class="las la-sliders-h"></i>
            </div>
        </div>
        <div class="mobile-balance-account">
            <span>{{ __('Acc') }}: {{ substr($user->account_no, 0, 4) }} **** {{ substr($user->account_no, -4) }}</span>
            <button class="mobile-copy-btn" onclick="copyAccountNo('{{ $user->account_no }}', this)" aria-label="{{ __('Copy') }}">
                <i class="las la-copy"></i>
            </button>
        </div>
        <div class="mobile-balance-amount-row">
            <div class="mobile-balance-amount" id="usd-balance-value">{{ get_amount($wallet->balance, 'USD') }}</div>
            <button class="mobile-eye-btn" id="toggle-usd-balance" aria-label="{{ __('Toggle Balance') }}">
                <i class="las la-eye"></i>
            </button>
        </div>
        <div class="mobile-balance-actions">
            <a href="{{ setRoute('user.add.money.index') }}" class="mobile-action-btn">
                <i class="las la-plus"></i>
                <span>{{ __('Fund') }}</span>
            </a>
            <a href="{{ setRoute('user.fund-transfer.index') }}" class="mobile-action-btn mobile-action-icon-btn">
                <i class="las la-exchange-alt"></i>
            </a>
            <a href="{{ setRoute('user.transactions.index') }}" class="mobile-action-btn mobile-action-icon-btn">
                <i class="las la-th"></i>
            </a>
        </div>
    </div>

    <!-- EUR Balance Card -->
    <div class="mobile-balance-card">
        <div class="mobile-balance-card-header">
            <div class="mobile-balance-currency-info">
                <span class="currency-flag">🇪🇺</span>
                <span class="currency-label">{{ __('EUR balance') }}</span>
                <i class="las la-angle-down"></i>
            </div>
            <div class="mobile-balance-card-settings">
                <i class="las la-sliders-h"></i>
            </div>
        </div>
        <div class="mobile-balance-account">
            <span>{{ __('Acc') }}: {{ substr($user->account_no, 0, 4) }} **** {{ substr($user->account_no, -4) }}</span>
            <button class="mobile-copy-btn" onclick="copyAccountNo('{{ $user->account_no }}', this)" aria-label="{{ __('Copy') }}">
                <i class="las la-copy"></i>
            </button>
        </div>
        <div class="mobile-balance-amount-row">
            <div class="mobile-balance-amount" id="eur-balance-value">€ 0.00</div>
            <button class="mobile-eye-btn" id="toggle-eur-balance" aria-label="{{ __('Toggle Balance') }}">
                <i class="las la-eye"></i>
            </button>
        </div>
        <div class="mobile-balance-actions">
            <a href="{{ setRoute('user.add.money.index') }}" class="mobile-action-btn">
                <i class="las la-plus"></i>
                <span>{{ __('Fund') }}</span>
            </a>
            <a href="{{ setRoute('user.fund-transfer.index') }}" class="mobile-action-btn mobile-action-icon-btn">
                <i class="las la-exchange-alt"></i>
            </a>
            <a href="{{ setRoute('user.transactions.index') }}" class="mobile-action-btn mobile-action-icon-btn">
                <i class="las la-th"></i>
            </a>
        </div>
    </div>

    <!-- Get Started Section -->
    <div class="mobile-get-started-section">
        <div class="mobile-section-title">{{ __('Get Started') }}</div>
        <div class="mobile-quick-links">
            <a href="{{ setRoute('user.add.money.index') }}" class="mobile-quick-link">
                <div class="mql-icon"><i class="las la-plus-circle"></i></div>
                <span>{{ __('Add Money') }}</span>
            </a>
            <a href="{{ setRoute('user.fund-transfer.index') }}" class="mobile-quick-link">
                <div class="mql-icon"><i class="las la-paper-plane"></i></div>
                <span>{{ __('Send') }}</span>
            </a>
            <a href="{{ setRoute('user.money-out.index') }}" class="mobile-quick-link">
                <div class="mql-icon"><i class="las la-arrow-circle-down"></i></div>
                <span>{{ __('Withdraw') }}</span>
            </a>
            <a href="{{ setRoute('user.investments.offers') }}" class="mobile-quick-link">
                <div class="mql-icon"><i class="las la-chart-pie"></i></div>
                <span>{{ __('Invest') }}</span>
            </a>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="mobile-transactions-section">
        <div class="mobile-section-header">
            <span class="mobile-section-title">{{ __('Recent Transactions') }}</span>
            <a href="{{ setRoute('user.transactions.index') }}" class="mobile-view-all">{{ __('View all') }}</a>
        </div>
        <div class="mobile-transactions-list">
            @include('user.components.transaction.log', compact('transactions'))
        </div>
    </div>
</div>

<!-- Desktop Dashboard Grid -->
<div class="dashboard-grid pt-3 d-none d-lg-grid">
    <!-- Row 1: Cards and Recent Activity (Equal Height & Width) -->
    <div class="premium-dashboard-container section-cards">
        <div class="container-title">{{ __('Your cards') }}</div>
        <div class="card-stack">
            <div class="main-bank-card" data-account-no="{{ $user->account_no }}">
                <div class="card-top">
                    <div class="card-logo">
                        <span class="logo-circle"></span>
                        <span>EnzoBank</span>
                    </div>
                    <div class="card-chip">
                        <i class="las la-wifi"></i>
                    </div>
                </div>
                <div class="card-balance-section">
                    <div class="balance-label-group">
                        <span>{{ __('Balance') }}</span>
                        <button id="toggle-balance" class="balance-toggle-btn" aria-label="Toggle Balance Visibility">
                            <i class="las la-eye"></i>
                        </button>
                    </div>
                    <div class="balance-value" id="balance-value">{{ get_amount($wallet->balance, $default_currency) }}</div>
                </div>
                <div class="card-bottom">
                    <div class="card-account-details">
                        <div class="account-number-display">**** **** **** {{ substr($user->account_no, -4) }}</div>
                        <button class="copy-btn-v2" onclick="copyAccountNo('{{ $user->account_no }}', this)" aria-label="{{ __('Copy Account Number') }}">
                            <i class="las la-copy"></i>
                        </button>
                    </div>
                    <div class="card-brand-logo">
                        <span>VISA</span>
                    </div>
                </div>
            </div>
            <div class="mini-cards">
                <div class="sage-card text-center">
                    <div class="small text-muted">{{ __('USD') }}</div>
                    <div class="fw-semibold">{{ get_amount($wallet->balance, 'USD') }}</div>
                </div>
                <div class="sage-card text-center">
                    <div class="small text-muted">{{ __('EUR') }}</div>
                    <div class="fw-semibold">€ 0.00</div>
                </div>
                <div class="sage-card text-center">
                    <div class="small text-muted">{{ __('GBP') }}</div>
                    <div class="fw-semibold">£ 0.00</div>
                </div>
            </div>
        </div>
    </div>

    <div class="premium-dashboard-container section-activity">
        <div class="container-title">{{ __('Recent activity') }}</div>
        <div class="dashboard-list-wrapper">
            <div class="item-wrapper transactions-search">
                @include('user.components.transaction.log', compact('transactions'))
            </div>
        </div>
        <div class="text-end mt-auto pt-3">
            <a href="{{ setRoute('user.transactions.index') }}" class="btn--base btn-sm">{{ __('View More') }}</a>
        </div>
    </div>

    <!-- Row 2: Summary and Other Widgets -->
    <div class="premium-dashboard-container section-summary">
        <div class="summary-header">
            <div class="fw-semibold">{{ __('Quick summary') }}</div>
            <div class="pill-switch">
                <a href="javascript:void(0)" class="active">{{ __('Month') }}</a>
            </div>
        </div>
        <div id="chart" data-transaction_chart="{{ json_encode($transaction_chart) }}" class="chart"></div>
    </div>

    <div class="premium-dashboard-container section-payments">
        <div class="container-title">{{ __('Upcoming payments') }}</div>
        <div class="payments-grid">
            <a class="sage-card d-flex align-items-center justify-content-center gap-2" href="{{ setRoute('user.add.money.index') }}">
                <i class="las la-plus-circle"></i>
                <span>{{ __('Add new') }}</span>
            </a>
            <div class="sage-card text-center">
                <div class="small text-muted">{{ __('Paypal') }}</div>
                <div class="fw-semibold">$ 45.00</div>
            </div>
            <div class="sage-card text-center">
                <div class="small text-muted">{{ __('Salary') }}</div>
                <div class="fw-semibold">$ 2,500</div>
            </div>
        </div>
        <div class="limit-widgets section-limits mt-4">
            <div class="sage-card"><div id="creditLimitChart"></div><div class="small mt-2">{{ __('Credit limit') }}</div></div>
            <div class="sage-card"><div id="onlineLimitChart"></div><div class="small mt-2">{{ __('Online limit') }}</div></div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{ asset('frontend/js/apexcharts.js') }}"></script>

    <script>
    // Mobile balance toggle
    function setupBalanceToggle(btnId, valueId) {
        const btn = document.getElementById(btnId);
        const val = document.getElementById(valueId);
        if (!btn || !val) return;
        let hidden = false;
        const original = val.innerText;
        btn.addEventListener('click', function() {
            hidden = !hidden;
            const icon = this.querySelector('i');
            if (hidden) {
                val.classList.add('blurred');
                icon.className = 'las la-eye-slash';
            } else {
                val.classList.remove('blurred');
                icon.className = 'las la-eye';
            }
        });
    }
    setupBalanceToggle('toggle-usd-balance', 'usd-balance-value');
    setupBalanceToggle('toggle-eur-balance', 'eur-balance-value');

    // Desktop balance toggle
    let isHidden = false;
    const balanceValue = document.getElementById('balance-value');
    const toggleBtn = document.getElementById('toggle-balance');

    if (toggleBtn && balanceValue) {
        toggleBtn.addEventListener('click', function() {
            isHidden = !isHidden;
            const icon = this.querySelector('i');
            icon.style.transform = 'rotate(360deg)';
            setTimeout(() => {
                if (isHidden) {
                    balanceValue.classList.add('blurred');
                    icon.className = 'las la-eye-slash';
                } else {
                    balanceValue.classList.remove('blurred');
                    icon.className = 'las la-eye';
                }
                icon.style.transform = 'rotate(0deg)';
            }, 150);
        });
    }

    // GSAP Entrance Animations
    if (typeof gsap !== 'undefined') {
        gsap.from(".premium-card", {
            duration: 1,
            y: 30,
            opacity: 0,
            stagger: 0.2,
            ease: "power4.out"
        });
    }

    const d = new Date();
    let year = d.getFullYear();

    var chartEl = document.querySelector("#chart");
    if (chartEl) {
        var transaction_chart = chartEl.getAttribute('data-transaction_chart');
        transaction_chart = JSON.parse(transaction_chart);

        var options = {
          series: [{
          name: 'Inflation',
          data: transaction_chart.transaction_data
        }],
          chart: {
          height: 350,
          type: 'bar',
          toolbar: { show: false },
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: { height: 220 },
            dataLabels: { style: { fontSize: '10px' } },
            xaxis: { labels: { style: { fontSize: '10px' } } },
            title: { offsetY: 200, style: { fontSize: '12px' } }
          }
        }],
        plotOptions: {
          bar: {
            borderRadius: 10,
            dataLabels: { position: 'top' },
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val + " {{ get_default_currency_code() }}";
          },
          offsetY: -20,
          style: { fontSize: '12px', colors: ["#304758"] }
        },
        xaxis: {
          categories: transaction_chart.transaction_month,
          position: 'top',
          axisBorder: { show: false },
          axisTicks: { show: false },
          crosshairs: {
            fill: {
              type: 'gradient',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: { enabled: true }
        },
        yaxis: {
          axisBorder: { show: false },
          axisTicks: { show: false },
          labels: {
            show: false,
            formatter: function (val) {
              return val + " {{ get_default_currency_code() }}";
            }
          }
        },
        title: {
          text: "{{ __('Yearly Transactions') }}" + ' , ' + year,
          floating: true,
          offsetY: 330,
          align: 'center',
          style: { color: '#444' }
        }
        };

        var chartInstance = new ApexCharts(document.querySelector("#chart"), options);
        chartInstance.render();

        var cl = {
            series: [75],
            chart: { type: 'radialBar', height: 180, sparkline: { enabled: true } },
            plotOptions: { radialBar: { hollow: { size: '55%' }, dataLabels: { value: { fontSize: '18px' } } } },
            colors: ['#5B7F6B'],
            labels: [''],
            responsive: [{ breakpoint: 480, options: { chart: { height: 150 } } }]
        };
        var ol = {
            series: [40],
            chart: { type: 'radialBar', height: 180, sparkline: { enabled: true } },
            plotOptions: { radialBar: { hollow: { size: '55%' }, dataLabels: { value: { fontSize: '18px' } } } },
            colors: ['#A9BEB0'],
            labels: [''],
            responsive: [{ breakpoint: 480, options: { chart: { height: 150 } } }]
        };
        new ApexCharts(document.querySelector("#creditLimitChart"), cl).render();
        new ApexCharts(document.querySelector("#onlineLimitChart"), ol).render();
    }
</script>
@endpush
