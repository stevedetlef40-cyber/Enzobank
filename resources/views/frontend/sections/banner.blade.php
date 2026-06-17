@php
    $app_local  = get_default_language_code();
    $default    = App\Constants\LanguageConst::NOT_REMOVABLE;
    $slug       = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::BANNER_SECTION);
    $banner     = App\Models\Admin\SiteSections::getData($slug)->first();
    
    // Baking theme defaults
    $baking_heading = "Artisanal | Bakery | Craftsmanship | & Quality";
    $baking_sub_heading = "Indulge in our premium selection of handcrafted pastries, artisan breads, and bespoke cakes. Experience the true taste of tradition and quality in every bite.";
    $baking_button = "Explore Our Products";
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Banner Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="banner-section banner-overlay  bg_img" data-background="{{ get_image($banner->value->image ?? '' ,'site-section') }}">
    <div class="container">
        <div class="row">
            <div class="col-xxl-8 col-lx-8 col-lg-10">
                <div class="banner-content">
                    @php
                        $heading = $banner->value->language->$app_local->heading ?? $banner->value->language->$default->heading ?? $baking_heading;
                        $headingParts = explode('|', $heading);
                    @endphp
                    <h1 class="title baking-title">
                        {{ isset($headingParts[0]) ? trim($headingParts[0]) : '' }} 
                        @if(isset($headingParts[1]))
                            <span class="text--base">{{ trim($headingParts[1]) }}</span>
                        @endif
                        {{ isset($headingParts[2]) ? trim($headingParts[2]) : '' }}
                        @if(isset($headingParts[3]))
                            <span class="text--base">{{ trim($headingParts[3]) }}</span>
                        @endif
                    </h1>
                    <p class="baking-sub-text">{{ $banner->value->language->$app_local->sub_heading ??  $banner->value->language->$default->sub_heading ?? $baking_sub_heading }}</p>
                    <div class="banner-btn baking-btn-wrapper">
                        <a href="{{ setRoute('user.login') }}" class="btn--base">{{ $banner->value->language->$app_local->button_name ??  $banner->value->language->$default->button_name ?? $baking_button }} <i class="las la-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('script')
<script>
    if(window.gsap) {
        // Entrance animation for title
        gsap.from(".baking-title", {
            duration: 1.5,
            y: 100,
            opacity: 0,
            skewY: 7,
            ease: "power4.out"
        });

        // Entrance animation for sub-text
        gsap.from(".baking-sub-text", {
            duration: 1.5,
            y: 50,
            opacity: 0,
            delay: 0.3,
            ease: "power3.out"
        });

        // Entrance animation for button
        gsap.from(".baking-btn-wrapper", {
            duration: 1.5,
            scale: 0.8,
            opacity: 0,
            delay: 0.6,
            ease: "back.out(1.7)"
        });
    }
</script>
@endpush

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Banner Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->