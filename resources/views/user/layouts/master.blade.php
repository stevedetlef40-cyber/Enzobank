<!DOCTYPE html>
<html lang="{{ get_default_language_code() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/baking-theme.css') }}">
    <script>document.documentElement.classList.add('no-transitions');</script>
    <!-- Theme Toggle Script (Blocking to prevent flicker) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('bakery-theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <title>{{ (isset($page_title) ? __($page_title) : __("Dashboard")) }}</title>

    @include('partials.header-asset')
    @stack("css")
</head>
<body>

    @include('frontend.partials.body-overlay')

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Dashboard
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="page-wrapper" >
    @include('user.partials.side-nav')
    <div class="main-wrapper">
        <div class="main-body-wrapper">
            @include('user.partials.top-nav')
            <div class="body-wrapper">
                @yield('content')
            </div>
        </div>
    </div>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Dashboard
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!-- WhatsApp Floating Widget -->
<div class="whatsapp-widget" data-aos="zoom-in" data-aos-delay="500">
    <a href="https://wa.me/message/ZW7EJRXHGL3GG1" target="_blank" class="whatsapp-btn" rel="noopener noreferrer" aria-label="Contact Support on WhatsApp">
        <div class="whatsapp-icon">
            <i class="lab la-whatsapp"></i>
            <span class="online-dot"></span>
        </div>
        <div class="whatsapp-text d-none d-lg-flex">
            <span>{{ __('Chat with us') }}</span>
        </div>
    </a>
</div>

<!-- Mobile Bottom Navigation -->
<nav class="mobile-bottom-nav d-flex d-lg-none">
    <a href="{{ setRoute('user.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
        <i class="las la-home"></i>
        <span>{{ __('Home') }}</span>
    </a>
    <a href="{{ setRoute('user.investments.offers') }}" class="mobile-nav-item {{ request()->routeIs('user.investments.*') ? 'active' : '' }}">
        <i class="las la-chart-line"></i>
        <span>{{ __('Invest') }}</span>
    </a>
    <a href="{{ setRoute('user.add.money.index') }}" class="mobile-nav-item {{ request()->routeIs('user.add.money.*') ? 'active' : '' }}">
        <i class="las la-wallet"></i>
        <span>{{ __('Wallet') }}</span>
    </a>
    <a href="{{ setRoute('user.transactions.index') }}" class="mobile-nav-item {{ request()->routeIs('user.transactions.*') ? 'active' : '' }}">
        <i class="las la-rss"></i>
        <span>{{ __('Feed') }}</span>
    </a>
    <a href="{{ setRoute('user.profile.index') }}" class="mobile-nav-item {{ request()->routeIs('user.profile.*') ? 'active' : '' }}">
        <i class="las la-user"></i>
        <span>{{ __('Account') }}</span>
    </a>
</nav>

@include('partials.footer-asset')
@include('user.partials.push-notification')

<!-- GSAP Animation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<!-- AOS Animation -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    AOS.init({
        duration: 1000,
        once: true,
    });

    // Copy Account Number Function
    function copyAccountNo() {
        var copyText = document.getElementById("bakery-account-no").innerText;
        navigator.clipboard.writeText(copyText).then(() => {
            // Use existing toastr if available, or simple alert
            if(typeof throwMessage === 'function') {
                throwMessage('success', ["Bakery ID copied successfully!"]);
            } else {
                alert("Bakery ID copied: " + copyText);
            }
        });
        
        // Button animation
        gsap.to(".copy-btn", { scale: 1.5, duration: 0.1, yoyo: true, repeat: 1 });
    }

    // Floating animation for WhatsApp widget
    gsap.to(".whatsapp-btn", {
        y: -10,
        duration: 2,
        repeat: -1,
        yoyo: true,
        ease: "power1.inOut"
    });
</script>

@stack("script")

<script>
    (function () {
        document.addEventListener('DOMContentLoaded', function(){ document.documentElement.classList.remove('no-transitions'); }, { once: true });
        const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
        const themeAttr = 'data-theme';
        const storageKey = 'bakery-theme';

        // Immediately set the theme on page load
        const savedTheme = localStorage.getItem(storageKey);
        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        const initialTheme = savedTheme || systemTheme;
        document.documentElement.setAttribute(themeAttr, initialTheme);
        if (toggleSwitch) {
            toggleSwitch.checked = (initialTheme === 'dark');
        }

        function switchTheme(e) {
            const theme = e.target.checked ? 'dark' : 'light';
            document.documentElement.setAttribute(themeAttr, theme);
            localStorage.setItem(storageKey, theme);

            // Optional: Add a subtle transition
            if (typeof gsap !== 'undefined') {
                gsap.to('body', { opacity: 1, duration: 0.5 });
            }
        }

        if (toggleSwitch) {
            toggleSwitch.addEventListener('change', switchTheme, false);
        }

        // Listen for system preference changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            // Only switch if the user hasn't made a manual selection
            if (!localStorage.getItem(storageKey)) {
                const newTheme = e.matches ? 'dark' : 'light';
                document.documentElement.setAttribute(themeAttr, newTheme);
                if (toggleSwitch) {
                    toggleSwitch.checked = (newTheme === 'dark');
                }
            }
        });
    })();
</script>

<script>
    $(document).on('click', '.verify-otp-btn', function(e){
        e.preventDefault();
        sendVerificationCode("{{ setRoute('user.verification-code.send') }}",'GET', "{{ setRoute('user.verification-code.resend') }}");
    });
</script>

</body>
</html>
