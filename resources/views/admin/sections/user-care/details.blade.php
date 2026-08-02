@extends('admin.layouts.master')

@push('css')
@endpush

@section('page-title')
    @include('admin.components.page-title', ['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => setRoute('admin.dashboard'),
            ],
        ],
        'active' => __('User Care'),
    ])
@endsection

@section('content')
    <div class="dashboard-area">
        <div class="dashboard-item-area">
            <div class="row">
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Current Balance") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">
                                    @foreach($user->wallets ?? collect([]) as $w)
                                        <span>{{ $w->currency->code }}: {{ get_amount($w->balance, $w->currency->code) }}</span>@if(!$loop->last) &nbsp;|&nbsp; @endif
                                    @endforeach
                                </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Transactions") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ $total_transactions ?? 0 }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Success") }} {{ $success_transactions ?? '' }}</span>
                                    <span class="badge badge--primary">{{ __("Pending") }} {{ $pending_transactions ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Tickets") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ $total_tickets ?? '' }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Active Tickets") }} {{ $active_tickets ?? '' }}</span>
                                    <span class="badge badge--info">{{ __("Pending Tickets") }} {{ $pending_tickets ?? '' }}</span>
                                    <span class="badge badge--primary">{{ __("Solved Tickets") }} {{ $solved_tickets ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($user->bankDetails->count() > 0)
                <div class="col-xxxl-12 col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-15">
                    <div class="custom-card">
                        <div class="card-header"><h6 class="title">{{ __("Bank Details") }}</h6></div>
                        <div class="card-body" style="padding:0;">
                            <div style="overflow-x:auto;">
                                <table class="data-table" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>{{ __("Recipient") }}</th>
                                            <th>{{ __("Bank") }}</th>
                                            <th>{{ __("Account / IBAN") }}</th>
                                            <th>{{ __("Country") }}</th>
                                            <th>{{ __("SWIFT / BIC") }}</th>
                                            <th>{{ __("Status") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->bankDetails as $bd)
                                        <tr>
                                            <td>{{ $bd->recipient_name }}</td>
                                            <td>{{ $bd->bank_name }}</td>
                                            <td style="font-family:monospace;">{{ $bd->account_number_iban }}</td>
                                            <td>{{ $bd->country }}</td>
                                            <td>{{ $bd->swift_bic ?? '—' }}</td>
                                            <td><span style="padding:2px 10px;border-radius:999px;font-size:11px;font-weight:600;background:{{ $bd->status ? 'rgba(59,130,246,0.12)' : 'rgba(239,68,68,0.12)' }};color:{{ $bd->status ? '#3B82F6' : '#EF4444' }};">{{ $bd->status ? 'Active' : 'Inactive' }}</span></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="custom-card mt-15">
        <div class="card-header">
            <h6 class="title">{{ __("User Overview") }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form">
                <div class="row align-items-center mb-10-none">
                    <div class="col-xl-4 col-lg-4 form-group">
                        <div class="user-action-btn-area">
                            <div class="user-action-btn">
                                <button type="button" class="bg--danger one wallet-balance-update-btn" data-bs-toggle="modal" data-bs-target="#addModal"><i class="las la-wallet me-1"></i>{{ __("Add/Subtract Balance") }}</button>
                            </div>
                            <div class="user-action-btn">
                                @include('admin.components.link.custom',[
                                    'href'          => setRoute('admin.users.login.logs',$user->username),
                                    'class'         => "bg--base two",
                                    'icon'          => "las la-sign-in-alt me-1",
                                    'text'          => __("Login Logs"),
                                    'permission'    => "admin.users.login.logs",
                                ])
                            </div>
                            <div class="user-action-btn">
                                @include('admin.components.link.custom',[
                                    'href'          => "#email-send",
                                    'class'         => "bg--warning three modal-btn",
                                    'icon'          => "las la-mail-bulk me-1",
                                    'text'          => __("Send Email"),
                                    'permission'    => "admin.users.send.mail",
                                ])
                            </div>
                            <div class="user-action-btn">
                                @include('admin.components.link.custom',[
                                    'class'         => "bg--info four login-as-member",
                                    'icon'          => "las la-user-check me-1",
                                    'text'          => __("Login as Member"),
                                    'permission'    => "admin.users.login.as.member",
                                ])
                            </div>
                            <div class="user-action-btn">
                                @include('admin.components.link.custom',[
                                    'href'          => setRoute('admin.users.mail.logs',$user->username),
                                    'class'         => "bg--success five",
                                    'icon'          => "las la-history me-1",
                                    'text'          => __("Email Logs"),
                                    'permission'    => "admin.users.mail.logs",
                                ])
                            </div>
                            <div class="user-action-btn">
                                @include('admin.components.link.custom',[
                                    'href'          => setRoute('admin.users.crypto.addresses',$user->username),
                                    'class'         => "bg--dark five",
                                    'icon'          => "las la-coins me-1",
                                    'text'          => __("Crypto Addresses"),
                                    'permission'    => "admin.users.crypto.addresses",
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 form-group">
                        <div class="user-profile-thumb">
                            <img src="{{ $user->userImage }}" alt="user">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 form-group">
                        <ul class="user-profile-list">
                            <li class="bg--base one">{{ __("Full Name") }}: <span>{{ $user->fullname }}</span></li>
                            <li class="bg--info two">{{ __("Username") }}: <span>{{ "@".$user->username }}</span></li>
                            <li class="bg--success three">{{ __("Email") }}: <span>{{ $user->email }}</span></li>
                            <li class="bg--warning four">{{ __("Status") }}: <span>{{ $user->stringStatus->value }}</span></li>
                            <li class="bg--danger five">{{ __("Last Login") }}: <span>{{ $user->lastLogin }}</span></li>
                            <li class="bg--base five">{{ __("Account Number") }}: <span>{{ $user->account_no }}</span></li>
                            <li class="bg--info five">{{ __("Intl. Bank Name") }}: <span>{{ $user->network_bank_name ?? '—' }}</span></li>
                            <li class="bg--success five">{{ __("Intl. Account Number") }}: <span>{{ $user->network_account_number ?? '—' }}</span></li>
                            <li class="bg--warning five">{{ __("IBAN") }}: <span>{{ $user->network_iban ?? '—' }}</span></li>
                            <li class="bg--danger five">{{ __("SWIFT / BIC") }}: <span>{{ $user->network_swift ?? '—' }}</span></li>
                            <li class="bg--dark five">{{ __("Deposit Gate") }}: <span>{!! $user->depositGateStatus->value !!}</span></li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="custom-card mt-15">
        <div class="card-header">
            <h6 class="title">{{ __("Information of User") }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form" method="POST" action="{{ setRoute('admin.users.details.update',$user->username) }}">
                @csrf
                <div class="row mb-10-none">
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __("First Name")."*",
                            'name'          => "firstname",
                            'value'         => old("firstname",$user->firstname),
                            'attribute'     => "required",
                            'placeholder'   => __("Write Here")."...",
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __("Last Name")."*",
                            'name'          => "lastname",
                            'value'         => old("lastname",$user->lastname),
                            'attribute'     => "required",
                            'placeholder'   => __("Write Here")."...",
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        <label>{{ __("Phone Number") }}</label>
                        <div class="input-group">
                            <div class="input-group-text phone-code">+{{ $user->mobile_code }}</div>
                            <input class="phone-code" type="hidden" name="mobile_code" value="{{ $user->mobile_code }}" />
                            <input type="text" class="form--control" placeholder="{{ __("Write Here") }}..." name="mobile" value="{{ old('mobile',$user->mobile) }}">
                        </div>
                        @error("mobile")
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __("Address"),
                            'name'          => 'address',
                            'value'         => old("address",$user->address->address ?? ""),
                            'placeholder'   => __("Write Here")."...",
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        <label>{{ __("Country") }}<span>*</span></label>
                        <select name="country" class="form--control select2-auto-tokenize country-select" data-placeholder="Select Country" data-old="{{ old('country',$user->address->country ?? "") }}"></select>
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __("State"),
                            'name'          => "state",
                            'placeholder'   => __("Enter State")."...",
                            'value'         => old('state',$user->address->state ?? "")
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __("City"),
                            'name'          => "city",
                            'placeholder'   => __("Enter City")."...",
                            'value'         => old('city',$user->address->city ?? "")
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __("Zip Code"),
                            'name'          => "zip_code",
                            'placeholder'   => __("Write Here")."...",
                            'value'         => old('zip_code',$user->address->zip ?? "")
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'         => __('User Status'),
                            'value'         => old('status',$user->status),
                            'name'          => "status",
                            'options'       => [__('Active') => 1, __('Banned') => 0],
                            'permission'    => "admin.users.details.update",
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'         => __('Email Verification'),
                            'value'         => old('email_verified',$user->email_verified),
                            'name'          => "email_verified",
                            'options'       => [__('Verified') => 1, __('Unverified') => 0],
                            'permission'    => "admin.users.details.update",
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'     => __('2FA Verification'),
                            'value'     => old('two_factor_verified',$user->two_factor_verified),
                            'name'      => "two_factor_verified",
                            'options'   => [__('Verified') => 1, __('Unverified') => 0],
                            'permission'    => "admin.users.details.update",
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'     => __('KYC Verification'),
                            'value'     => old('kyc_verified',$user->kyc_verified),
                            'name'      => "kyc_verified",
                            'options'   => [__('Verified') => 1, __('Unverified') => 0],
                            'permission'    => "admin.users.details.update",
                        ])
                    </div>

                    <div class="col-xl-12">
                        <h6 class="title mt-3">{{ __("Payment Method Access") }}</h6>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'     => __('Virtual Card'),
                            'value'     => old('virtual_card_status',$user->virtual_card_status),
                            'name'      => "virtual_card_status",
                            'options'   => [__('Active') => 1, __('Inactive') => 0],
                            'permission'    => "admin.users.details.update",
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'     => __('Card Required'),
                            'value'     => old('card_required',$user->card_required),
                            'name'      => "card_required",
                            'options'   => [__('Required') => 1, __('Not Required') => 0],
                            'permission'    => "admin.users.details.update",
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'     => __('Crypto Security Deposit'),
                            'value'     => old('crypto_status',$user->crypto_status),
                            'name'      => "crypto_status",
                            'options'   => [__('Required') => 1, __('Not Required') => 0],
                            'permission'    => "admin.users.details.update",
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'     => __('Add Money'),
                            'value'     => old('add_money_status',$user->add_money_status),
                            'name'      => "add_money_status",
                            'options'   => [__('Active') => 1, __('Inactive') => 0],
                            'permission'    => "admin.users.details.update",
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'     => __('Fund Transfer'),
                            'value'     => old('fund_transfer_status',$user->fund_transfer_status),
                            'name'      => "fund_transfer_status",
                            'options'   => [__('Active') => 1, __('Inactive') => 0],
                            'permission'    => "admin.users.details.update",
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'     => __('Money Out'),
                            'value'     => old('money_out_status',$user->money_out_status),
                            'name'      => "money_out_status",
                            'options'   => [__('Active') => 1, __('Inactive') => 0],
                            'permission'    => "admin.users.details.update",
                        ])
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 form-group">
                        @include('admin.components.form.switcher', [
                            'label'     => __('Own Bank Transfer'),
                            'value'     => old('own_bank_transfer_blocked', $user->own_bank_transfer_blocked),
                            'name'      => "own_bank_transfer_blocked",
                            'options'   => [__('Blocked') => 1, __('Allowed') => 0],
                            'onload'    => true,
                            'data_target' => $user->username,
                            'permission'    => "admin.users.details.update",
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __('Virtual Card Max Amount'),
                            'name'          => "virtual_card_limit",
                            'value'         => old('virtual_card_limit',$user->virtual_card_limit),
                            'type'          => "number",
                            'placeholder'   => __("No limit"),
                            'required'      => false,
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __('Crypto Deposit Max Amount'),
                            'name'          => "crypto_limit",
                            'value'         => old('crypto_limit',$user->crypto_limit),
                            'type'          => "number",
                            'placeholder'   => __("No limit"),
                            'required'      => false,
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __('Virtual Card Purchase Fee'),
                            'name'          => "vc_fee_override",
                            'value'         => old('vc_fee_override',$user->vc_fee_override ?? get_virtual_card_fee()),
                            'type'          => "number",
                            'placeholder'   => __("10"),
                            'required'      => false,
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __('WhatsApp Support Number'),
                            'name'          => "support_whatsapp",
                            'value'         => old('support_whatsapp',$user->support_whatsapp),
                            'type'          => "text",
                            'placeholder'   => __("447464483316 (general default)"),
                            'required'      => false,
                        ])
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group mt-4">
                        @include('admin.components.button.form-btn',[
                            'text'          => __("Update"),
                            'permission'    => "admin.users.details.update",
                            'class'         => "w-100 btn-loading",
                        ])
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Send Email Modal --}}
    @include('admin.components.modals.send-mail-user',compact("user"))

    <div id="wallet-balance-update-modal" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header px-0">
                <h5 class="modal-title">{{ __("Add/Subtract Balance") }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="modal-form" method="POST" action="{{ setRoute('admin.users.wallet.balance.update',$user->username) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-10-none">
                        <div class="col-xl-12 col-lg-12 form-group">
                            <label for="balance">{{ __("Type") }}<span>*</span></label>
                            <select name="type" id="balance" class="form--control nice-select">
                                <option disabled selected>{{ __("Select Type") }}</option>
                                <option value="add">{{ __("Balance Add") }}</option>
                                <option value="subtract">{{ __("Balance Subtract") }}</option>
                            </select>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            <label for="wallet">{{ __("User Wallet") }}<span>*</span></label>
                            <select name="wallet" id="wallet" class="form--control select2-auto-tokenize">
                                <option disabled selected>{{ __("Select User Wallet") }}</option>
                                @foreach($user->wallets ?? collect([]) as $w)
                                    <option value="{{ $w->id }}">{{ $w->currency->code }} ({{ $w->currency->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            @include('admin.components.form.input',[
                                'label'         => __('Amount').'*',
                                'name'          => 'amount',
                                'value'         => old("amount"),
                                'class'         => "number-input",
                            ])
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            @include('admin.components.form.input',[
                                'label'         => __("Remark"),
                                'name'          => "remark",
                                'value'         => old("remark"),
                            ])
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                            <button type="button" class="btn btn--danger modal-close">{{ __("Close") }}</button>
                            <button type="submit" class="btn btn--base">{{ __("Action") }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        getAllCountries("{{ setRoute('global.countries') }}");
        $(document).ready(function() {

            openModalWhenError("email-send","#email-send");
            
            $("select[name=country]").change(function(){
                var phoneCode = $("select[name=country] :selected").attr("data-mobile-code");
                placePhoneCode(phoneCode);
            });

            setTimeout(() => {
                var phoneCodeOnload = $("select[name=country] :selected").attr("data-mobile-code");
                placePhoneCode(phoneCodeOnload);
            }, 400);

            countrySelect(".country-select",$(".country-select").siblings(".select2"));
            stateSelect(".state-select",$(".state-select").siblings(".select2"));


            $(".login-as-member").click(function() {
                var action  = "{{ setRoute('admin.users.login.as.member',$user->username) }}";
                var target  = "{{ $user->username }}";
                postFormAndSubmit(action,target);
            });
        })
        $(".wallet-balance-update-btn").click(function(){
            openModalBySelector("#wallet-balance-update-modal");
        });

        switcherAjax("{{ setRoute('admin.users.own.bank.transfer.toggle', $user->username) }}");
    </script>
@endpush
