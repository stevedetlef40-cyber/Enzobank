@php
    $app_local = get_default_language_code();
    $default   = App\Constants\LanguageConst::NOT_REMOVABLE;
    $slug      = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::OVERVIEW_SECTION);
    $overview  = App\Models\Admin\SiteSections::getData($slug)->first();

    $about_heading = $overview->value->language->$app_local->heading ?? $overview->value->language->$default->heading ?? 'About Us';
    $about_sub     = $overview->value->language->$app_local->sub_heading ?? $overview->value->language->$default->sub_heading ?? 'We combine traditional banking values with cutting-edge technology to deliver an unparalleled financial experience to our customers worldwide.';
    $about_image   = $overview->value->image ?? '';
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start About Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="enzo-about">
    <div class="container">
        <div class="enzo-about-grid">
            <div class="enzo-about-text" data-aos="fade-right">
                <div class="enzo-about-label">{{ __('About Us') }}</div>
                <h2 class="enzo-about-title">{{ $about_heading }}</h2>
                <p class="enzo-about-desc">{{ $about_sub }}</p>
                <a href="{{ setRoute('frontend.about') }}" class="enzo-btn-primary">{{ __('Learn More') }} →</a>
            </div>
            <div class="enzo-about-image" data-aos="fade-left" data-aos-delay="100">
                @if($about_image)
                    <img src="{{ get_image($about_image, 'site-section') }}" alt="{{ __('About EnzoBank') }}">
                @else
                    <div class="enzo-about-placeholder">
                        <i class="las la-university"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End About Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
