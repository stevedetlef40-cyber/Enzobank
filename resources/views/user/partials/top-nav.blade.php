<nav class="navbar-wrapper d-none d-lg-flex">
    <div class="navbar-container">
        <div class="navbar-content">
            <!-- Navigation Left: Brand & Hierarchy -->
            <div class="nav-left">
                <button class="sidebar-menu-bar" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="breadcrumb-area d-none d-sm-flex">
                    <span class="main-path"><a href="{{ setRoute('user.dashboard') }}">{{ __('Dashboard') }}</a></span>
                    <i class="las la-angle-right"></i>
                    <span class="active-path">{{ __($page_title) ?? 'Dashboard' }}</span>
                </div>
            </div>

            <!-- Navigation Right: Functional Info & User Controls -->
            <div class="nav-right">
                <!-- Account Info Pill -->
                <div class="account-pill-v2" aria-label="Account Number" data-account-number="{{ auth()->user()->account_no }}">
                    <div class="pill-label">{{ __('ACCOUNT NUMBER') }}</div>
                    <div class="pill-value-group">
                        <span class="pill-number" title="{{ auth()->user()->account_no }}">
                            <span class="full-number d-none d-md-inline">{{ auth()->user()->account_no }}</span>
                            <span class="truncated-number d-inline d-md-none">***{{ substr(auth()->user()->account_no, -4) }}</span>
                        </span>
                        <button class="copy-trigger" onclick="copyAccountNo()" aria-label="Copy Account Number">
                            <i class="las la-copy"></i>
                        </button>
                    </div>
                </div>

                <!-- User Controls Group -->
                <div class="user-controls-group">
                    <div class="control-item theme-item">
                        <label class="theme-switch-v2" for="checkbox" aria-label="Toggle Dark Mode">
                            <input type="checkbox" id="checkbox" />
                            <div class="switch-slider">
                                <i class="las la-sun sun-icon"></i>
                                <i class="las la-moon moon-icon"></i>
                            </div>
                        </label>
                    </div>

                    <div class="control-item notification-item">
                        @php
                            $user_notifications = get_user_notifications();
                            $unread_count = $user_notifications->where('seen', 0)->count();
                        @endphp
                        <button class="nav-icon-v2" id="notificationToggle" aria-label="View Notifications" aria-haspopup="true" aria-expanded="false">
                            <i class="las la-bell"></i>
                            @if($unread_count > 0)
                                <span class="notification-badge">{{ $unread_count > 99 ? '99+' : $unread_count }}</span>
                            @endif
                        </button>
                        <div class="notification-dropdown-v2" id="notificationDropdown">
                            <div class="dropdown-header">
                                <h6>{{ __('Notifications') }}</h6>
                                @if($unread_count > 0)
                                    <span class="badge bg-primary">{{ $unread_count }} {{ __('New') }}</span>
                                @endif
                            </div>
                            <div class="dropdown-body">
                                <ul class="notification-list-v2">
                                    @forelse ($user_notifications->take(10) as $item)
                                        <li class="{{ $item->seen == 0 ? 'unread' : '' }}">
                                            <div class="notification-item-content">
                                                <div class="icon-box">
                                                    <i class="las la-info-circle"></i>
                                                </div>
                                                <div class="text-box">
                                                    <p class="message">{{ __($item->message->title ?? 'Notification') }}</p>
                                                    <span class="time">{{ $item->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="empty-state">
                                            <p>{{ __('No notifications found') }}</p>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="dropdown-footer">
                                <a href="{{ setRoute('user.transactions.index') }}">{{ __('View All Transactions') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="control-item user-item">
                        <a href="{{ setRoute('user.profile.index') }}" class="user-avatar-v2" aria-label="User Profile">
                            <img src="{{ auth()->user()->userImage }}" alt="{{ auth()->user()->username }}">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile App Header -->
<header class="mobile-app-header d-flex d-lg-none">
    <div class="mobile-header-left">
        <a href="{{ setRoute('user.profile.index') }}" class="mobile-user-avatar">
            <img src="{{ auth()->user()->userImage }}" alt="{{ auth()->user()->username }}">
        </a>
        <span class="mobile-greeting">{{ __('Hi') }}, {{ auth()->user()->firstname ?? auth()->user()->username }}</span>
    </div>
    <div class="mobile-header-center">
        <a href="{{ setRoute('user.investments.offers') }}" class="earn-bonus-btn">
            💰 {{ __('Earn 3% Bonus') }}
        </a>
    </div>
    <div class="mobile-header-right">
        <label class="mobile-theme-btn" for="mobile-theme-checkbox" aria-label="Toggle Dark Mode">
            <input type="checkbox" id="mobile-theme-checkbox" />
            <i class="las la-moon"></i>
        </label>
        @php
            if (!isset($user_notifications)) {
                $user_notifications = get_user_notifications();
                $unread_count = $user_notifications->where('seen', 0)->count();
            }
        @endphp
        <button class="mobile-notif-btn" id="mobileNotifToggle" aria-label="Notifications">
            <i class="las la-bell"></i>
            @if($unread_count > 0)
                <span class="mobile-notif-badge">{{ $unread_count > 9 ? '9+' : $unread_count }}</span>
            @endif
        </button>
    </div>
</header>

@push('script')
<script>
    (function(){
        // --- Sidebar Logic ---
        const mqDesktop = window.matchMedia('(min-width: 992px)');
        const storageKey = 'sidebarVisibleDesktop';
        const body = document.body;
        const toggleBtn = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('body-overlay') || document.querySelector('.body-overlay');

        function applyInitialState() {
            if (mqDesktop.matches) {
                const saved = localStorage.getItem(storageKey);
                const visible = saved === null ? false : saved === 'true';
                body.classList.toggle('sidebar-visible', visible);
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', String(visible));
            } else {
                body.classList.remove('sidebar-visible');
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
            }
        }

        function setVisible(visible) {
            body.classList.toggle('sidebar-visible', visible);
            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', String(visible));
            if (mqDesktop.matches) {
                localStorage.setItem(storageKey, String(visible));
            } else {
                if (overlay) {
                    overlay.classList.toggle('active', visible);
                }
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e){
                e.preventDefault();
                const nowVisible = !body.classList.contains('sidebar-visible');
                setVisible(nowVisible);
            });
        }
        if (overlay) {
            overlay.addEventListener('click', function(e){
                setVisible(false);
            });
        }
        mqDesktop.addEventListener('change', applyInitialState);
        applyInitialState();

        // --- Theme Toggle Logic (Desktop) ---
        const themeToggle = document.getElementById('checkbox');
        const themeStorageKey = 'bakery-theme';

        if (themeToggle) {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            themeToggle.checked = (currentTheme === 'dark');

            themeToggle.addEventListener('change', function() {
                const newTheme = this.checked ? 'dark' : 'light';
                document.documentElement.classList.add('no-transitions');
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem(themeStorageKey, newTheme);
                setTimeout(() => {
                    document.documentElement.classList.remove('no-transitions');
                }, 300);
            });
        }

        // --- Theme Toggle Logic (Mobile) ---
        const mobileThemeToggle = document.getElementById('mobile-theme-checkbox');
        if (mobileThemeToggle) {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            mobileThemeToggle.checked = (currentTheme === 'dark');

            mobileThemeToggle.addEventListener('change', function() {
                const newTheme = this.checked ? 'dark' : 'light';
                document.documentElement.classList.add('no-transitions');
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('bakery-theme', newTheme);
                setTimeout(() => {
                    document.documentElement.classList.remove('no-transitions');
                }, 300);
            });
        }

        // --- Notification Dropdown Logic ---
        const notifyToggle = document.getElementById('notificationToggle');
        const notifyDropdown = document.getElementById('notificationDropdown');

        if (notifyToggle && notifyDropdown) {
            notifyToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isOpen = notifyDropdown.classList.contains('show');
                document.querySelectorAll('.notification-dropdown-v2.show').forEach(el => el.classList.remove('show'));
                if (!isOpen) {
                    notifyDropdown.classList.add('show');
                    this.setAttribute('aria-expanded', 'true');
                } else {
                    notifyDropdown.classList.remove('show');
                    this.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('click', function(e) {
                if (!notifyDropdown.contains(e.target) && !notifyToggle.contains(e.target)) {
                    notifyDropdown.classList.remove('show');
                    notifyToggle.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && notifyDropdown.classList.contains('show')) {
                    notifyDropdown.classList.remove('show');
                    notifyToggle.setAttribute('aria-expanded', 'false');
                    notifyToggle.focus();
                }
            });
        }

        // --- EnzoBank Click-to-Copy Logic ---
        window.copyAccountNo = async function(customValue = null, element = null) {
            const container = document.querySelector('.account-pill-v2');
            const accountNo = customValue || (container ? container.getAttribute('data-account-number') : '');
            const copyBtn = element || document.querySelector('.copy-trigger');
            const icon = copyBtn ? copyBtn.querySelector('i') : null;

            if (!accountNo) {
                showEnzoToast('error', 'Account number not found.');
                return;
            }

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(accountNo.trim());
                } else {
                    const textArea = document.createElement("textarea");
                    textArea.value = accountNo.trim();
                    textArea.style.position = "fixed";
                    textArea.style.left = "-9999px";
                    textArea.style.top = "-9999px";
                    textArea.setAttribute('readonly', '');
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    const successful = document.execCommand('copy');
                    document.body.removeChild(textArea);
                    if (!successful) throw new Error('Fallback copy failed');
                }

                if (icon) {
                    const originalClass = icon.className;
                    icon.className = 'las la-check-circle';
                    if (copyBtn) {
                        copyBtn.classList.add('copy-success');
                        copyBtn.setAttribute('aria-label', 'Account Number Copied');
                    }
                    setTimeout(() => {
                        icon.className = originalClass;
                        if (copyBtn) {
                            copyBtn.classList.remove('copy-success');
                            copyBtn.setAttribute('aria-label', 'Copy Account Number');
                        }
                    }, 2000);
                }

                showEnzoToast('success', 'Your EnzoBank account number has been copied!');
            } catch (err) {
                showEnzoToast('error', 'Unable to copy. Please try manually.');
            }
        };

        function showEnzoToast(type, message) {
            let toastContainer = document.getElementById('enzo-toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'enzo-toast-container';
                toastContainer.setAttribute('aria-live', 'polite');
                toastContainer.setAttribute('role', 'status');
                document.body.appendChild(toastContainer);
            }

            const toast = document.createElement('div');
            toast.className = `enzo-toast ${type}`;
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="las ${type === 'success' ? 'la-check-circle' : 'la-exclamation-triangle'}"></i>
                </div>
                <div class="toast-content">${message}</div>
            `;

            toastContainer.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 10);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar-wrapper');
        const scrollThreshold = 100;

        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
                if (navbar) navbar.classList.add('nav-hidden');
            } else {
                if (navbar) navbar.classList.remove('nav-hidden');
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        }, { passive: true });

    })();
</script>
@endpush
