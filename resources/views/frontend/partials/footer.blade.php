@php
    $app_local     = get_default_language_code();
    $default       = App\Constants\LanguageConst::NOT_REMOVABLE;
    $slug          = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::FOOTER_SECTION);
    $footer        = App\Models\Admin\SiteSections::getData($slug)->first();
    $subcribe_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::SUBSCRIBE_SECTION);
    $subscribe     = App\Models\Admin\SiteSections::getData($subcribe_slug)->first();
    $useful_links  = App\Models\Admin\UsefulLink::where('status', true)->get();

    $footer_desc      = $footer->value->footer->language->$app_local->description ?? $footer->value->footer->language->$default->description ?? 'Providing secure, innovative banking solutions for millions of customers worldwide.';
    $social_links     = $footer->value->social_links ?? [];
    $newsletter_title = $subscribe->value->language->$app_local->title ?? $subscribe->value->language->$default->title ?? 'Newsletter';
    $newsletter_desc  = $subscribe->value->language->$app_local->description ?? $subscribe->value->language->$default->description ?? 'Subscribe to get the latest updates.';
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Footer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<footer class="enzo-footer">
    <div class="enzo-footer-main">
        <div class="container">
            <div class="enzo-footer-grid">
                <!-- Brand Column -->
                <div class="enzo-footer-brand">
                    <a href="{{ setRoute('frontend.index') }}" class="enzo-footer-logo">
                        Enzo<span class="enzo-logo-accent">Bank</span>
                    </a>
                    <p class="enzo-footer-tagline">{{ $footer_desc }}</p>
                    <div class="enzo-footer-socials">
                        @forelse($social_links as $item)
                            <a href="{{ $item->link ?? '#' }}" target="_blank" rel="noopener noreferrer" class="enzo-social-link" aria-label="{{ $item->icon ?? '' }}">
                                <i class="{{ $item->icon ?? 'las la-link' }}"></i>
                            </a>
                        @empty
                            <a href="#" class="enzo-social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="enzo-social-link" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="enzo-social-link" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="enzo-social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        @endforelse
                    </div>
                </div>

                <!-- Company Links -->
                <div class="enzo-footer-col">
                    <h4 class="enzo-footer-col-title">{{ __('Company') }}</h4>
                    <ul class="enzo-footer-links">
                        <li><a href="{{ setRoute('frontend.about') }}">{{ __('About Us') }}</a></li>
                        @foreach ($useful_links ?? [] as $item)
                            <li><a href="{{ setRoute('frontend.useful.links', $item->slug) }}">{{ $item->title->language->$app_local->title ?? $item->title->language->$default->title ?? '' }}</a></li>
                        @endforeach
                        <li><a href="{{ setRoute('frontend.services') }}">{{ __('Services') }}</a></li>
                        <li><a href="{{ setRoute('frontend.contact') }}">{{ __('Contact') }}</a></li>
                    </ul>
                </div>

                <!-- Legal Links -->
                <div class="enzo-footer-col">
                    <h4 class="enzo-footer-col-title">{{ __('Legal') }}</h4>
                    <ul class="enzo-footer-links">
                        <li><a href="#">{{ __('Privacy Policy') }}</a></li>
                        <li><a href="#">{{ __('Terms of Service') }}</a></li>
                        <li><a href="#">{{ __('Cookie Policy') }}</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="enzo-footer-col">
                    <h4 class="enzo-footer-col-title">{{ $newsletter_title }}</h4>
                    <p class="enzo-footer-newsletter-desc">{{ $newsletter_desc }}</p>
                    <form id="subscribe-form" action="{{ setRoute('frontend.subscribe') }}" method="POST" class="enzo-newsletter-form">
                        @csrf
                        <input type="email" name="email" class="enzo-newsletter-input" placeholder="{{ __('Your email address') }}" required>
                        <button type="submit" class="enzo-newsletter-btn">
                            <i class="las la-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="enzo-footer-bottom">
        <div class="container">
            <p>&copy; {{ date('Y') }} <a href="{{ setRoute('frontend.index') }}">{{ $basic_settings->site_name ?? 'EnzoBank' }}</a>. {{ __('All Rights Reserved.') }}</p>
        </div>
    </div>
</footer>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/message/ZW7EJRXHGL3GG1" target="_blank" class="enzo-whatsapp-float" rel="noopener noreferrer" aria-label="Chat on WhatsApp">
    <i class="lab la-whatsapp"></i>
</a>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Footer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
