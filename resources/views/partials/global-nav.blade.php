@php
    $user_notifications = auth()->check() ? get_user_notifications() : collect([]);
    $unread_count = auth()->check() ? (new \App\Models\UserNotification)->where('user_id', auth()->id())->where('is_read', false)->count() : 0;
@endphp
<!-- ====== GLOBAL ENZOBANK NAVBAR ====== -->
<header class="global-nav" id="globalNav">
    <div class="global-nav-inner">
        <!-- Left Section -->
        <div class="global-nav-left">
            <!-- Logo -->
            <a href="{{ auth()->check() ? route('user.rise.home') : route('frontend.index') }}" class="global-logo">
                <img src="{{ asset('backend/images/web-settings/image-assets/enzobank-logo.png') }}" alt="EnzoBank" class="global-logo-img">
                <span class="global-logo-text">Enzo<span class="global-logo-accent">Bank</span></span>
            </a>
            <!-- Breadcrumb (App) -->
            @auth
                <div class="global-breadcrumb d-none d-md-flex">
                    <a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
                    @if(isset($page_title))
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                        <span>{{ __($page_title) }}</span>
                    @endif
                </div>
            @endauth
        </div>

        <!-- Center: Nav Links (Public) -->
        @guest
        <nav class="global-nav-links d-none d-lg-flex">
            <a href="{{ route('frontend.index') }}" class="global-nav-link {{ request()->routeIs('frontend.index') ? 'active' : '' }}">Home</a>
            <a href="{{ route('frontend.index') }}#features" class="global-nav-link">Features</a>
            <a href="{{ route('frontend.index') }}#how-it-works" class="global-nav-link">How It Works</a>
            <a href="{{ route('frontend.index') }}#security" class="global-nav-link">Security</a>
            <a href="{{ route('frontend.index') }}#testimonials" class="global-nav-link">Reviews</a>
            <a href="{{ route('frontend.contact') }}" class="global-nav-link">Contact</a>
        </nav>
        @endguest

        <!-- Right Section -->
        <div class="global-nav-right">
            <!-- Theme Toggle (always visible) -->
            <button class="global-theme-toggle" id="globalThemeToggle" aria-label="Toggle theme">
                <svg class="global-sun-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="display:none"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <svg class="global-moon-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </button>

            @auth
                <!-- Greeting + Promo -->
                <div class="global-greeting d-none d-md-flex">
                    <span>{{ __('Hi') }}, {{ auth()->user()->firstname ?? auth()->user()->username }}</span>
                </div>
                <a href="{{ route('user.investments.offers') }}" class="global-promo-pill d-none d-lg-flex">
                    💰 {{ __('Earn 3% Bonus') }}
                </a>
                <!-- Account Pill -->
                <div class="global-account-pill d-none d-md-flex" data-account-number="{{ auth()->user()->account_no }}">
                    <span class="global-pill-label">{{ __('ACCOUNT') }}</span>
                    <span class="global-pill-number">***{{ substr(auth()->user()->account_no, -4) }}</span>
                    <button class="global-copy-btn" onclick="copyAccountNo()" aria-label="Copy">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    </button>
                </div>
                <!-- Notifications -->
                <div class="global-notif-wrapper">
                    <button class="global-notif-btn" id="globalNotifToggle" aria-label="Notifications">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        @if($unread_count > 0)
                            <span class="global-notif-badge">{{ $unread_count > 99 ? '99+' : $unread_count }}</span>
                        @endif
                    </button>
                    <!-- Notification Dropdown -->
                    <div class="global-notif-dropdown" id="globalNotifDropdown">
                        <div class="global-notif-header">
                            <h6>{{ __('Notifications') }}</h6>
                            @if($unread_count > 0)
                            <button class="global-mark-read" id="globalMarkRead" onclick="window.location='{{ route('user.notifications.readAll') }}'">{{ __('Mark all read') }}</button>
                            @endif
                        </div>
                        <div class="global-notif-body">
                            @forelse ($user_notifications->take(10) as $item)
                                <a href="{{ route('user.notifications.show', $item->id) }}" class="global-notif-item {{ !$item->is_read ? 'unread' : '' }}" style="text-decoration:none;display:block;">
                                    <div class="global-notif-icon"><i class="las la-info-circle"></i></div>
                                    <div class="global-notif-text">
                                        <p>{{ __($item->message->title ?? 'Notification') }}</p>
                                        <span>{{ $item->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="global-notif-empty">
                                    <i class="las la-bell-slash"></i>
                                    <p>{{ __('No notifications yet') }}</p>
                                </div>
                            @endforelse
                            <a href="{{ route('user.notifications.index') }}" class="global-notif-footer">{{ __('View All Notifications') }} &rarr;</a>
                        </div>
                    </div>
                </div>
                <!-- More Dropdown -->
                <div class="global-more-wrapper">
                    <button class="global-more-btn" id="globalMoreToggle" aria-label="More">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                    </button>
                    <div class="global-more-dropdown" id="globalMoreDropdown">
                        <a href="{{ route('user.rise.account') }}" class="global-dropdown-item"><i class="las la-cog"></i> {{ __('Settings') }}</a>
                        <a href="{{ route('frontend.contact') }}" class="global-dropdown-item"><i class="las la-headset"></i> {{ __('Help & Support') }}</a>
                        <a href="#" class="global-dropdown-item"><i class="las la-share-alt"></i> {{ __('Refer a Friend') }}</a>
                        <div class="global-dropdown-divider"></div>
                        <a href="{{ route('frontend.index') }}" class="global-dropdown-item"><i class="las la-file-alt"></i> {{ __('Privacy Policy') }}</a>
                        <a href="#" class="global-dropdown-item"><i class="las la-file-contract"></i> {{ __('Terms of Service') }}</a>
                        <div class="global-dropdown-divider"></div>
                        <form method="POST" action="{{ route('user.logout') }}" id="logoutForm" style="display:contents;">
                            @csrf
                            <button type="submit" class="global-dropdown-item global-dropdown-danger" style="border:none;background:none;width:100%;cursor:pointer;"><i class="las la-sign-out-alt"></i> {{ __('Log Out') }}</button>
                        </form>
                    </div>
                </div>
                <!-- User Avatar -->
                <a href="{{ route('user.rise.account') }}" class="global-user-avatar">
                    <img src="{{ auth()->user()->userImage }}" alt="{{ auth()->user()->username }}">
                </a>
            @else
                <!-- Auth Buttons (Public) -->
                <a href="{{ route('user.login') }}" class="global-btn global-btn-ghost d-none d-sm-inline-flex">{{ __('Sign In') }}</a>
                <a href="{{ route('user.register') }}" class="global-btn global-btn-primary d-none d-sm-inline-flex">{{ __('Get Started') }}</a>
                <!-- Hamburger for mobile (public) -->
                <button class="global-hamburger d-lg-none" id="globalHamburger" aria-label="Menu" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            @endauth
        </div>
    </div>
</header>

<!-- Mobile menu (public) -->
@guest
<div class="global-mobile-menu" id="globalMobileMenu">
    <nav class="global-mobile-nav">
        <a href="{{ route('frontend.index') }}" class="global-mobile-link {{ request()->routeIs('frontend.index') ? 'active' : '' }}">Home</a>
        <a href="{{ route('frontend.index') }}#features" class="global-mobile-link">Features</a>
        <a href="{{ route('frontend.index') }}#how-it-works" class="global-mobile-link">How It Works</a>
        <a href="{{ route('frontend.index') }}#security" class="global-mobile-link">Security</a>
        <a href="{{ route('frontend.index') }}#testimonials" class="global-mobile-link">Reviews</a>
        <a href="{{ route('frontend.contact') }}" class="global-mobile-link">Contact</a>
    </nav>
    <div class="global-mobile-auth">
        <a href="{{ route('user.login') }}" class="global-btn global-btn-ghost">{{ __('Sign In') }}</a>
        <a href="{{ route('user.register') }}" class="global-btn global-btn-primary">{{ __('Get Started') }}</a>
    </div>
</div>
@endguest

@push('script')
<script>
(function(){
    // --- Sidebar Toggle ---
    const sidebarToggle = document.getElementById('globalSidebarToggle');
    const body = document.body;
    const overlay = document.getElementById('body-overlay') || document.querySelector('.body-overlay');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            body.classList.toggle('sidebar-visible');
            const visible = body.classList.contains('sidebar-visible');
            this.setAttribute('aria-expanded', String(visible));
            if (overlay) overlay.classList.toggle('active', visible);
        });
        if (overlay) {
            overlay.addEventListener('click', function() {
                body.classList.remove('sidebar-visible');
                sidebarToggle.setAttribute('aria-expanded', 'false');
                overlay.classList.remove('active');
            });
        }
    }

    // --- Theme Toggle ---
    const themeToggle = document.getElementById('globalThemeToggle');
    if (themeToggle) {
        const sunIcon = themeToggle.querySelector('.global-sun-icon');
        const moonIcon = themeToggle.querySelector('.global-moon-icon');
        function updateThemeIcons(theme) {
            sunIcon.style.display = theme === 'light' ? 'block' : 'none';
            moonIcon.style.display = theme === 'dark' ? 'block' : 'none';
        }
        updateThemeIcons(document.documentElement.getAttribute('data-theme') || 'dark');
        themeToggle.addEventListener('click', function() {
            const cur = document.documentElement.getAttribute('data-theme') || 'dark';
            const next = cur === 'dark' ? 'light' : 'dark';
            document.documentElement.classList.add('no-transitions');
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateThemeIcons(next);
            setTimeout(() => document.documentElement.classList.remove('no-transitions'), 300);
        });
    }

    // --- Dropdown Helpers ---
    function closeAllGlobalDropdowns() {
        document.querySelectorAll('.global-notif-dropdown.show, .global-more-dropdown.show').forEach(el => el.classList.remove('show'));
    }

    // Notification Dropdown
    const notifToggle = document.getElementById('globalNotifToggle');
    const notifDropdown = document.getElementById('globalNotifDropdown');
    if (notifToggle && notifDropdown) {
        notifToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = notifDropdown.classList.contains('show');
            closeAllGlobalDropdowns();
            if (!isOpen) notifDropdown.classList.add('show');
        });
    }

    // More Dropdown
    const moreToggle = document.getElementById('globalMoreToggle');
    const moreDropdown = document.getElementById('globalMoreDropdown');
    if (moreToggle && moreDropdown) {
        moreToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = moreDropdown.classList.contains('show');
            closeAllGlobalDropdowns();
            if (!isOpen) moreDropdown.classList.add('show');
        });
    }

    // Outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.global-notif-wrapper') && !e.target.closest('.global-more-wrapper')) {
            closeAllGlobalDropdowns();
        }
    });

    // Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const open = document.querySelector('.global-notif-dropdown.show, .global-more-dropdown.show');
            if (open) closeAllGlobalDropdowns();
        }
    });

    // --- Copy Account ---
    window.copyAccountNo = async function(customValue, element) {
        const container = document.querySelector('.global-account-pill');
        const accountNo = customValue || (container ? container.getAttribute('data-account-number') : '');
        const copyBtn = element || document.querySelector('.global-copy-btn');
        if (!accountNo) return;
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(accountNo.trim());
            } else {
                const textArea = document.createElement("textarea");
                textArea.value = accountNo.trim();
                textArea.style.position = "fixed";
                textArea.style.left = "-9999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
            }
            if (copyBtn) {
                copyBtn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
                setTimeout(() => {
                    copyBtn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>';
                }, 2000);
            }
        } catch(err) {}
    };

    // --- Navbar scroll effect (transparent -> solid) ---
    // rAF-throttled so heavy logic never runs on every scroll frame.
    const nav = document.querySelector('.global-nav');
    const scrollThreshold = 60; // toggles solid after ~60px of scroll
    let navTicking = false;
    function updateNavState() {
        const st = window.pageYOffset || document.documentElement.scrollTop || 0;
        if (st > scrollThreshold) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
        navTicking = false;
    }
    if (nav) {
        window.addEventListener('scroll', function () {
            if (!navTicking) {
                navTicking = true;
                window.requestAnimationFrame(updateNavState);
            }
        }, { passive: true });
        // Set correct state on load (e.g. restored scroll / back-forward navigation)
        updateNavState();
    }

    // --- Hamburger toggle (public) ---
    const hamburger = document.getElementById('globalHamburger');
    if (hamburger) {
        const mobileMenu = document.getElementById('globalMobileMenu');
        hamburger.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true' ? false : true;
            this.setAttribute('aria-expanded', String(expanded));
            this.classList.toggle('active');
            if (mobileMenu) {
                mobileMenu.classList.toggle('open');
                body.classList.toggle('mobile-menu-open', expanded);
                if (overlay) overlay.classList.toggle('active', expanded);
            }
        });
        if (overlay) {
            overlay.addEventListener('click', function() {
                hamburger.setAttribute('aria-expanded', 'false');
                hamburger.classList.remove('active');
                if (mobileMenu) mobileMenu.classList.remove('open');
                body.classList.remove('mobile-menu-open');
                overlay.classList.remove('active');
            });
        }
    }
})();

// Logout loader
var logoutForm = document.getElementById('logoutForm');
if (logoutForm) {
    logoutForm.addEventListener('submit', function() {
        showLoader('Signing out');
    });
}
</script>
@endpush

@include('partials.app-loader')

