@php
    $menues      = DB::table('setup_pages')->where('status', 1)->get();
    $current_url = URL::current();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Header
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<header class="enzo-header" id="enzoHeader">
    <nav class="enzo-navbar">
        <div class="enzo-nav-container">
            <!-- Logo -->
            <a href="{{ setRoute('frontend.index') }}" class="enzo-logo">
                Enzo<span class="enzo-logo-accent">Bank</span>
            </a>

            <!-- Desktop Nav Links -->
            <ul class="enzo-nav-links d-none d-lg-flex">
                @foreach ($menues ?? [] as $item)
                    @php $title = $item->title ?? ''; @endphp
                    <li><a href="{{ url($item->url) }}" class="enzo-nav-link {{ $current_url == url($item->url) ? 'active' : '' }}">{{ __($title) }}</a></li>
                @endforeach
                <li><a href="{{ setRoute('frontend.contact') }}" class="enzo-nav-link">{{ __('Contact') }}</a></li>
            </ul>

            <!-- Desktop CTA -->
            <div class="enzo-nav-actions d-none d-lg-flex">
                @auth
                    <a href="{{ setRoute('user.dashboard') }}" class="enzo-btn-primary">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ setRoute('user.login') }}" class="enzo-btn-ghost">{{ __('Login') }}</a>
                    <a href="{{ setRoute('user.register') }}" class="enzo-btn-primary">{{ __('Get Started') }} →</a>
                @endauth
            </div>

            <!-- Mobile Hamburger -->
            <button class="enzo-hamburger d-lg-none" id="enzoMenuToggle" aria-label="Toggle navigation" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div class="enzo-mobile-menu" id="enzoMobileMenu">
            <ul class="enzo-mobile-nav-links">
                @foreach ($menues ?? [] as $item)
                    @php $title = $item->title ?? ''; @endphp
                    <li><a href="{{ url($item->url) }}">{{ __($title) }}</a></li>
                @endforeach
                <li><a href="{{ setRoute('frontend.contact') }}">{{ __('Contact') }}</a></li>
            </ul>
            <div class="enzo-mobile-actions">
                @auth
                    <a href="{{ setRoute('user.dashboard') }}" class="enzo-btn-primary w-100 text-center">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ setRoute('user.login') }}" class="enzo-btn-ghost w-100 text-center mb-2">{{ __('Login') }}</a>
                    <a href="{{ setRoute('user.register') }}" class="enzo-btn-primary w-100 text-center">{{ __('Get Started') }} →</a>
                @endauth
            </div>
        </div>
    </nav>
</header>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Header
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

@push('script')
<script>
(function() {
    const toggle = document.getElementById('enzoMenuToggle');
    const menu = document.getElementById('enzoMobileMenu');
    const header = document.getElementById('enzoHeader');

    if (toggle && menu) {
        toggle.addEventListener('click', function() {
            const open = menu.classList.toggle('open');
            toggle.classList.toggle('open', open);
            toggle.setAttribute('aria-expanded', String(open));
        });
        document.addEventListener('click', function(e) {
            if (!header.contains(e.target)) {
                menu.classList.remove('open');
                toggle.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Scroll effect
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }, { passive: true });
})();
</script>
@endpush
