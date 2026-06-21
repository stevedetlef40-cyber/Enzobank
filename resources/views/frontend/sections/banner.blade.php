@php
    $app_local = get_default_language_code();
    $default   = App\Constants\LanguageConst::NOT_REMOVABLE;
    $slug      = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::BANNER_SECTION);
    $banner    = App\Models\Admin\SiteSections::getData($slug)->first();

    $heading     = $banner->value->language->$app_local->heading ?? $banner->value->language->$default->heading ?? 'Banking Reimagined for the Digital Age';
    $sub_heading = $banner->value->language->$app_local->sub_heading ?? $banner->value->language->$default->sub_heading ?? 'Experience secure, seamless banking with competitive rates, instant transfers, and world-class customer support — all in one place.';
    $btn_name    = $banner->value->language->$app_local->button_name ?? $banner->value->language->$default->button_name ?? 'Get Started';

    // Split heading at last word for cyan accent
    $words = explode(' ', trim($heading));
    $lastWord = array_pop($words);
    $mainHeading = implode(' ', $words);
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Hero Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="enzo-hero">
    <div class="enzo-hero-bg"></div>
    <div class="container">
        <div class="enzo-hero-content">
            <!-- Trust Badge -->
            <div class="enzo-trust-badge" data-aos="fade-down">
                <span class="badge-dot"></span>
                {{ __('TRUSTED BY 2M+ CUSTOMERS WORLDWIDE') }}
            </div>

            <!-- Headline -->
            <h1 class="enzo-hero-title" data-aos="fade-up" data-aos-delay="100">
                {{ $mainHeading }} <span class="enzo-accent">{{ $lastWord }}</span>
            </h1>

            <!-- Sub-heading -->
            <p class="enzo-hero-sub" data-aos="fade-up" data-aos-delay="200">
                {{ $sub_heading }}
            </p>

            <!-- CTA Buttons -->
            <div class="enzo-hero-actions" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ setRoute('user.register') }}" class="enzo-btn-primary enzo-btn-lg">
                    {{ $btn_name }} →
                </a>
                <a href="{{ setRoute('frontend.about') }}" class="enzo-btn-outline enzo-btn-lg">
                    {{ __('Explore Services') }}
                </a>
            </div>

            <!-- Stats Grid -->
            <div class="enzo-stats-grid" data-aos="fade-up" data-aos-delay="400">
                <div class="enzo-stat">
                    <div class="enzo-stat-value">2M+</div>
                    <div class="enzo-stat-label">{{ __('Happy Customers') }}</div>
                </div>
                <div class="enzo-stat-divider"></div>
                <div class="enzo-stat">
                    <div class="enzo-stat-value">$50B+</div>
                    <div class="enzo-stat-label">{{ __('Assets Managed') }}</div>
                </div>
                <div class="enzo-stat-divider"></div>
                <div class="enzo-stat">
                    <div class="enzo-stat-value">99.9%</div>
                    <div class="enzo-stat-label">{{ __('Uptime Guarantee') }}</div>
                </div>
                <div class="enzo-stat-divider"></div>
                <div class="enzo-stat">
                    <div class="enzo-stat-value">24/7</div>
                    <div class="enzo-stat-label">{{ __('Customer Support') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Hero Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
