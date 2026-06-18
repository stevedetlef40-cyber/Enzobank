@php
    $app_local      = get_default_language_code();
    $default        = App\Constants\LanguageConst::NOT_REMOVABLE;
    $slug           = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::SECURITY_SECTION);
    $security       = App\Models\Admin\SiteSections::getData($slug)->first();

    // Baking theme defaults
    $baking_heading = "Our Commitment to Quality";
    $baking_sub_heading = "We maintain the highest standards of food safety and hygiene, ensuring that every product delivered to you is fresh, safe, and delicious.";
    $baking_items = [
        (object) ['icon' => 'fas fa-shield-alt', 'language' => (object) [$app_local => (object) ['title' => 'Food Safety First', 'description' => 'Rigorous hygiene protocols in our state-of-the-art bakery.']]],
        (object) ['icon' => 'fas fa-leaf', 'language' => (object) [$app_local => (object) ['title' => 'Freshly Sourced', 'description' => 'We source our ingredients from local, sustainable farms.']]],
        (object) ['icon' => 'fas fa-check-double', 'language' => (object) [$app_local => (object) ['title' => 'Quality Checks', 'description' => 'Every batch is tested for taste, texture, and appearance.']]],
    ];
@endphp
<section class="security-system pt-80 pb-80">
    <div class="container">
        <div class="security-tag">
            <h2 class="title"><i class="fas fa-certificate text--base mb-20"></i> {{ $security->value->language->$app_local->heading ?? $security->value->language->$default->heading ?? $baking_heading }}</h2>
        </div>
         <div class="security-title pb-30">
            <div class="row">
                <div class="col-xl-8 col-lg-10">
                    <h3 class="title">{{ $security->value->language->$app_local->sub_heading ?? $security->value->language->$default->sub_heading ?? $baking_sub_heading }}</h3>
                </div>
            </div>
         </div>
        <div class="row justify-content-center">
            @php
                $items = $security->value->items ?? $baking_items;
            @endphp
            @foreach ($items as $item)
            <div class="col-xl-4 col-lg-6 col-md-6 pb-20">
                <div class="security-item">
                    <span class="icon"><i class="{{ $item->icon ?? '' }}"></i></span>
                    <div class="security-content">
                        <h4 class="title">{{ $item->language->$app_local->title ?? $item->language->$default->title ?? '' }}</h4>
                        <p>{{ $item->language->$app_local->description ?? $item->language->$default->description ?? '' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
    </div>
</section>
