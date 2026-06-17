@php
    $app_local  = get_default_language_code();
    $default    = App\Constants\LanguageConst::NOT_REMOVABLE;
    $slug       = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::FEATURE_SECTION);
    $feature    = App\Models\Admin\SiteSections::getData($slug)->first();

    // Baking theme defaults
    $baking_heading = "Why Choose Our Bakery?";
    $baking_sub_heading = "We take pride in our traditional baking methods, using only the finest ingredients to bring you the best quality baked goods every single day.";
    $baking_items = [
        (object) ['language' => (object) [$app_local => (object) ['item_title' => 'Daily Freshly Baked']]],
        (object) ['language' => (object) [$app_local => (object) ['item_title' => '100% Organic Ingredients']]],
        (object) ['language' => (object) [$app_local => (object) ['item_title' => 'Traditional Recipes']]],
        (object) ['language' => (object) [$app_local => (object) ['item_title' => 'Custom Cake Design']]],
    ];
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Key Features Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="key-features pt-80 pb-80">
    <div class="container">
        <div class="features-area">
            <div class="feature-tag">
                <h2 class="title"><i class="fas fa-bread-slice text--base mb-20"></i> {{ $feature->value->language->$app_local->first_heading ?? $feature->value->language->$default->first_heading ?? $baking_heading }}</h2>
            </div>
            <div class="feature-title pb-30">
               <div class="row">
                  <div class="col-xl-8 col-lg-10">
                     <h3 class="title">{{ $feature->value->language->$app_local->first_sub_heading ?? $feature->value->language->$default->first_sub_heading ?? $baking_sub_heading }}</h3>
                  </div>
               </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="featuare-content-area">
                        <ul class="feature-list">
                            @php
                                $items = $feature->value->items ?? $baking_items;
                            @endphp
                            @foreach ($items as $item)
                                @if (!isset($item->status) || $item->status == true)
                                    <li>
                                        <i class="las la-certificate"></i> {{ $item->language->$app_local->item_title ?? $item->language->$default->item_title ?? '' }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="key-deatils">
                            <h3 class="title">{{ $feature->value->language->$app_local->second_heading ?? $feature->value->language->$default->second_heading ?? "Experience Premium Taste" }}</h3>
                            <p>{{ $feature->value->language->$app_local->second_sub_heading ?? $feature->value->language->$default->second_sub_heading ?? "From crusty artisan sourdough to delicate french pastries, our master bakers ensure every product meets our high standards of excellence." }}</p>
                            <div class="contact-btn">
                                <a href="{{ setRoute('frontend.contact') }}" class="btn--base">{{ $feature->value->language->$app_local->button_name ?? $feature->value->language->$default->button_name ?? "Contact Our Bakery" }}<i class="las la-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   End Key Features Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->