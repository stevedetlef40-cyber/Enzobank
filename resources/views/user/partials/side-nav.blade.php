<div class="sidebar">
    <div class="sidebar-inner">
        <div class="resize-handle"></div>
        <div class="sidebar-inner-wrapper">
            <div class="sidebar-logo">
                <a href="{{ setRoute('frontend.index') }}" class="sidebar-main-logo">
                    <img src="{{ get_logo($basic_settings) }}" alt="logo">
                </a>
                <button class="sidebar-menu-bar">
                    <i class="fas fa-exchange-alt"></i>
                </button>
            </div>
            <div class="sidebar-menu-wrapper">
                <ul class="sidebar-menu">
                    <li class="sidebar-menu-item-header">{{ __('My Accounts') }}</li>
                    <li class="sidebar-menu-item {{ menuActive('user.dashboard') }}">
                        <a href="{{ route('user.dashboard') }}">
                            <i class="menu-icon las la-tachometer-alt"></i>
                            <span class="menu-title">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ menuActive('user.transactions.index') }}">
                        <a href="{{ setRoute('user.transactions.index') }}">
                            <i class="menu-icon las la-receipt"></i>
                            <span class="menu-title">{{ __('Transactions') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item" {{ menuActive('user.statements.index') }}>
                        <a href="{{ setRoute('user.statements.index') }}">
                            <i class="menu-icon las la-file-invoice-dollar"></i>
                            <span class="menu-title">{{ __('Statements') }}</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item-header">{{ __('Payments & Transfers') }}</li>
                    <li class="sidebar-menu-item {{ menuActive(['user.fund-transfer.index', 'user.fund-transfer.create', 'user.fund-transfer.preview', 'user.fund-transfer.transaction.success']) }}">
                        <a href="{{ route('user.fund-transfer.index') }}">
                            <i class="menu-icon las la-paper-plane"></i>
                            <span class="menu-title">{{ __('Send Funds') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{  menuActive('user.add.money.index') }}">
                        <a href="{{ setRoute('user.add.money.index') }}">
                            <i class="menu-icon las la-plus-circle"></i>
                            <span class="menu-title">{{ __('Add Money') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{  menuActive('user.money-out.index') }}">
                        <a href="{{ setRoute('user.money-out.index') }}">
                            <i class="menu-icon las la-arrow-circle-down"></i>
                            <span class="menu-title">{{ __('Withdraw') }}</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item-header">{{ __('Financial Tools') }}</li>
                    <li class="sidebar-menu-item">
                        <a href="{{ setRoute('user.strowallet.virtual.card.index') }}">
                            <i class="menu-icon las la-credit-card"></i>
                            <span class="menu-title">{{ __("Virtual Card") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ menuActive('user.loans.index') }}">
                        <a href="{{ route('user.loans.index') }}">
                            <i class="menu-icon las la-chart-line"></i>
                            <span class="menu-title">{{ __('Loan Management') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ menuActive('user.investments.offers') }}">
                        <a href="{{ route('user.investments.offers') }}">
                            <i class="menu-icon las la-chart-pie"></i>
                            <span class="menu-title">{{ __('Investment Opportunities') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ menuActive('user.portfolios.index') }}">
                        <a href="{{ route('user.portfolios.index') }}">
                            <i class="menu-icon las la-piggy-bank"></i>
                            <span class="menu-title">{{ __('Investment Portfolio') }}</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item-header">{{ __('Manage & Support') }}</li>
                    <li class="sidebar-menu-item {{ menuActive(['user.beneficiary.index', 'user.beneficiary.create', 'user.beneficiary.preview']) }}">
                        <a href="{{ setRoute('user.beneficiary.index') }}">
                            <i class="menu-icon las la-user-friends"></i>
                            <span class="menu-title">{{ __('Beneficiaries') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-cog"></i>
                            <span class="menu-title">{{ __("Settings") }}</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class="sidebar-menu-item" {{ menuActive('user.setup.pin.index') }}>
                                <a href="{{ setRoute('user.setup.pin.index') }}">
                                    <i class="menu-icon las la-shield-alt"></i>
                                    <span class="menu-title">{{ __('Security Pin') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a href="{{ setRoute('user.security.google.2fa') }}" class="nav-link" >
                                    <i class="menu-icon lab la-google"></i>
                                    <span class="menu-title ms-1">{{ __('2FA Security') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a href="{{ setRoute('user.kyc.index') }}" class="nav-link">
                                    <i class="menu-icon las la-id-card"></i>
                                    <span class="menu-title ms-1">{{ __('KYC Verification') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-menu-item">
            <a href="{{ setRoute('user.support.ticket.index') }}">
                <i class="menu-icon las la-headset"></i>
                <span class="menu-title">{{ __("Support") }}</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="#">
                <i class="menu-icon las la-book"></i>
                <span class="menu-title">{{ __("Documentation") }}</span>
            </a>
        </li>
                </ul>
            </div>
        </div>
        <div class="sidebar-bottom-area">
            <a href="javascript:void(0)" class="logout-btn">
                <i class="menu-icon las la-sign-out-alt"></i>
                <span class="menu-title">{{__('Logout')}}</span>
            </a>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(".logout-btn").click(function(){
            var actionRoute =  "{{ setRoute('user.logout') }}";
            var target      = 1;
            var message     = `{{ __("Are you sure to") }} <strong>{{ __("Logout") }}</strong>?`;

            openAlertModal(actionRoute,target,message,"{{ __('Logout') }}","POST");
                /**
            * Function for open delete modal with method DELETE
            * @param {string} URL
            * @param {string} target
            * @param {string} message
            * @returns
            */
            function openAlertModal(URL,target,message,actionBtnText = "{{ __('Remove') }}",method = "DELETE"){
            if(URL == "" || target == "") {
            return false;
            }

            if(message == "") {
            message = "Are you sure to delete ?";
            }
            var method = `<input type="hidden" name="_method" value="${method}">`;
            openModalByContent(
            {
                content: `<div class="card modal-alert border-0">
                            <div class="card-body">
                                <form method="POST" action="${URL}">
                                    <input type="hidden" name="_token" value="${laravelCsrf()}">
                                    ${method}
                                    <div class="head mb-3">
                                        ${message}
                                        <input type="hidden" name="target" value="${target}">
                                    </div>
                                    <div class="foot d-flex align-items-center justify-content-between">
                                        <button type="button" class="modal-close btn--base btn-for-modal">{{ __("Close") }}</button>
                                        <button type="submit" class="alert-submit-btn btn--base bg-danger btn-loading btn-for-modal">${actionBtnText}</button>
                                    </div>
                                </form>
                            </div>
                        </div>`,
            },

            );
            }
        });
    </script>
@endpush
