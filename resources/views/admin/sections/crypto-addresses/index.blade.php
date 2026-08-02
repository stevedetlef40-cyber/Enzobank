@extends('admin.layouts.master')

@push('css')
<style>
.ca-form-card { max-width: 800px; }
.ca-coin-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px; border-radius: 100px;
    font-size: 11px; font-weight: 600; text-transform: uppercase;
    letter-spacing: 0.5px;
}
.qr-preview-wrapper {
    margin-top: 10px;
    padding: 10px;
    border: 1px solid var(--admin-border);
    border-radius: 8px;
    background: var(--admin-card);
    display: none;
}
.qr-preview-wrapper canvas, .qr-preview-wrapper img {
    display: inline-block !important;
    border-radius: 6px;
}
</style>
@endpush

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        ['name'  => __("Dashboard"), 'url'   => setRoute("admin.dashboard")],
    ], 'active' => __("Crypto Addresses")])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="custom-card ca-form-card">
            <div class="card-header">
                <h6 class="title">{{ __("Add / Edit Crypto Address") }}</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.crypto.addresses.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-10-none">
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Coin Name") }}<span>*</span></label>
                            <input type="text" class="form--control" name="coin_name" placeholder="e.g. Bitcoin" value="{{ old('coin_name') }}">
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Symbol") }}<span>*</span></label>
                            <input type="text" class="form--control" name="symbol" placeholder="e.g. BTC" value="{{ old('symbol') }}">
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Network") }}</label>
                            <input type="text" class="form--control" name="network" placeholder="e.g. Bitcoin Network, ERC20, TRC20" value="{{ old('network') }}">
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Color") }}</label>
                            <div class="input-group">
                                <input type="color" class="form--control form-control-color" name="color" value="{{ old('color', '#1D4ED8') }}" style="max-width:60px;padding:2px">
                                <input type="text" class="form--control color-hex-input" value="{{ old('color', '#1D4ED8') }}" placeholder="#1D4ED8" style="flex:1">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Wallet Address") }}<span>*</span></label>
                            <input type="text" class="form--control" name="wallet_address" placeholder="Enter wallet address" value="{{ old('wallet_address') }}">
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Purpose") }}</label>
                            <select name="purpose" class="form--control nice-select">
                                <option value="">{{ __("All (General)") }}</option>
                                <option value="deposit" {{ old('purpose') == 'deposit' ? 'selected' : '' }}>{{ __("Deposit") }}</option>
                                <option value="transfer" {{ old('purpose') == 'transfer' ? 'selected' : '' }}>{{ __("Transfer") }}</option>
                            </select>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Logo") }}</label>
                            @include('admin.components.form.input-file',[
                                'label'    => false,
                                'class'    => 'file-holder',
                                'name'     => 'logo',
                            ])
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("For User (optional)") }}</label>
                            <select name="user_id" class="form--control select2-auto-tokenize">
                                <option value="">{{ __("Global (all users)") }}</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>{{ $u->fullname }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __("Leave empty for global address") }}</small>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group mt-4">
                            <button type="submit" class="btn--base w-100 btn-loading">{{ __("Save Address") }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="custom-card mt-15">
            <div class="card-header">
                <h6 class="title">{{ __("All Crypto Addresses") }}</h6>
            </div>
            <div class="card-body">
                @if ($wallets->count() > 0)
                    <div class="row">
                        @foreach ($wallets as $wallet)
                            <div class="col-md-6 col-xl-4 mb-15">
                                <div class="ca-card" data-item="{{ $wallet->editData }}">
                                    <div class="ca-card-header">
                                        <div class="ca-coin-icon" style="background: {{ $wallet->color ?? '#1D4ED8' }}">
                                            @if ($wallet->logo_image)
                                                <img src="{{ $wallet->logo_image }}" alt="{{ $wallet->symbol }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                                            @else
                                                {{ $wallet->symbol }}
                                            @endif
                                        </div>
                                        <div class="ca-coin-info">
                                            <div class="ca-coin-name">{{ $wallet->coin_name }}
                                                @if ($wallet->user_id)
                                                    <span class="ca-coin-badge" style="background:rgba(29,78,216,0.12);color:#1D4ED8">{{ __("User") }}</span>
                                                @else
                                                    <span class="ca-coin-badge" style="background:rgba(37,99,235,0.12);color:#3B82F6">{{ __("Global") }}</span>
                                                @endif
                                                @if ($wallet->purpose)
                                                    <span class="ca-coin-badge" style="background:rgba(99,102,241,0.12);color:#6366F1">{{ __(ucfirst($wallet->purpose)) }}</span>
                                                @endif
                                            </div>
                                            <div class="ca-coin-network">{{ $wallet->network ?? __('No network') }}</div>
                                        </div>
                                        <div>
                                            @include('admin.components.form.switcher',[
                                                'label' => '',
                                                'name'  => 'type',
                                                'value' => $wallet->is_active,
                                                'options' => ['Active' => 1,'Inactive' => 0],
                                                'onload' => true,
                                                'permission' => 'admin.crypto.addresses.status',
                                                'attribute' => "data-target-url=".route('admin.crypto.addresses.status', $wallet->id),
                                            ])
                                        </div>
                                    </div>
                                    <div class="ca-card-body">
                                        <div class="ca-address">{{ $wallet->wallet_address }}</div>
                                        @if ($wallet->user)
                                            <div class="mb-2" style="font-size:12px;color:var(--admin-text-muted)">
                                                {{ __("Assigned to") }}: <strong>{{ $wallet->user->fullname }}</strong>
                                            </div>
                                        @endif
                                        <div class="ca-actions">
                                            <button type="button" class="enzo-admin-btn enzo-admin-btn-primary enzo-admin-btn-sm copy-addr-btn" data-address="{{ $wallet->wallet_address }}">
                                                <i class="las la-copy"></i> {{ __("Copy") }}
                                            </button>
                                            <button type="button" class="enzo-admin-btn enzo-admin-btn-success enzo-admin-btn-sm show-qr-btn" data-address="{{ $wallet->wallet_address }}" data-id="{{ $wallet->id }}" data-coin="{{ $wallet->coin_name }}">
                                                <i class="las la-qrcode"></i> {{ __("Show QR") }}
                                            </button>
                                            <button type="button" class="enzo-admin-btn enzo-admin-btn-warning enzo-admin-btn-sm edit-modal-button" data-item="{{ $wallet->editData }}">
                                                <i class="las la-pencil-alt"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.crypto.addresses.delete', $wallet->id) }}" class="d-inline" onsubmit="return confirm('{{ __("Delete this address?") }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="enzo-admin-btn enzo-admin-btn-danger enzo-admin-btn-sm">
                                                    <i class="las la-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        {{-- Inline QR code generated with qrcodejs --}}
                                        <div class="qr-preview-wrapper" id="qr-wrapper-{{ $wallet->id }}">
                                            <div class="d-flex align-items-center gap-3">
                                                <div id="qrcode-{{ $wallet->id }}"></div>
                                                <div>
                                                    <button type="button" class="btn btn--base btn-sm download-qr-btn" data-id="{{ $wallet->id }}" data-filename="{{ $wallet->symbol }}-{{ $wallet->coin_name }}-qr">
                                                        <i class="las la-download me-1"></i>{{ __("Download PNG") }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="wl-empty" style="padding:44px 20px;text-align:center">
                        <span class="wl-empty-title" style="font-size:16px;font-weight:700;color:var(--admin-text)">{{ __("No crypto addresses configured") }}</span>
                        <span class="wl-empty-sub" style="font-size:13px;color:var(--admin-text-muted)">{{ __("Add your first crypto address above") }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Edit Crypto Address Modal --}}
<div id="crypto-address-edit" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header px-0">
                <h5 class="modal-title">{{ __("Edit Crypto Address") }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="modal-form" method="POST" action="{{ setRoute('admin.crypto.addresses.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    @include('admin.components.form.hidden-input',[
                        'name'          => 'target',
                        'value'         => old('target'),
                    ])
                    <div class="row mb-10-none">
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Coin Name") }}<span>*</span></label>
                            <input type="text" class="form--control" name="coin_name" placeholder="e.g. Bitcoin" value="{{ old('coin_name') }}">
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Symbol") }}<span>*</span></label>
                            <input type="text" class="form--control" name="symbol" placeholder="e.g. BTC" value="{{ old('symbol') }}">
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Network") }}</label>
                            <input type="text" class="form--control" name="network" placeholder="e.g. Bitcoin Network, ERC20, TRC20" value="{{ old('network') }}">
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Color") }}</label>
                            <div class="input-group">
                                <input type="color" class="form--control form-control-color" name="color" value="#1D4ED8" style="max-width:60px;padding:2px">
                                <input type="text" class="form--control color-hex-input" value="#1D4ED8" placeholder="#1D4ED8" style="flex:1">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Wallet Address") }}<span>*</span></label>
                            <input type="text" class="form--control" name="wallet_address" placeholder="Enter wallet address" value="{{ old('wallet_address') }}">
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Purpose") }}</label>
                            <select name="purpose" class="form--control nice-select">
                                <option value="">{{ __("All (General)") }}</option>
                                <option value="deposit">{{ __("Deposit") }}</option>
                                <option value="transfer">{{ __("Transfer") }}</option>
                            </select>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            <label>{{ __("Logo (optional)") }}</label>
                            @include('admin.components.form.input-file',[
                                'label'    => false,
                                'class'    => 'file-holder',
                                'name'     => 'logo',
                            ])
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                            <button type="button" class="btn btn--danger modal-close">{{ __("Cancel") }}</button>
                            <button type="submit" class="btn btn--base">{{ __("Update") }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
$(document).ready(function(){
    // Edit modal
    openModalWhenError("crypto-address-edit","#crypto-address-edit");

    $(document).on('click', '.edit-modal-button', function(){
        var raw = this.getAttribute('data-item');
        if (!raw) return;
        var oldData = JSON.parse(raw);
        var editModal = $("#crypto-address-edit");
        if (!editModal.length) return;

        editModal.find('.invalid-feedback').remove();
        editModal.find('.form--control').removeClass('is-invalid');

        editModal.find("form").first().find("input[name=target]").val(oldData.id);
        editModal.find("input[name=coin_name]").val(oldData.coin_name);
        editModal.find("input[name=symbol]").val(oldData.symbol);
        editModal.find("input[name=network]").val(oldData.network);
        editModal.find("input[name=wallet_address]").val(oldData.wallet_address);
        editModal.find("select[name=purpose]").val(oldData.purpose).niceSelect('update');
        editModal.find("input[name=color]").val(oldData.color);
        editModal.find(".color-hex-input").val(oldData.color);

        openModalBySelector("#crypto-address-edit");
    });

    // Sync color picker with hex input
    $(document).on('input', 'input[type=color][name=color]', function(){
        $(this).closest('.input-group').find('.color-hex-input').val($(this).val());
    });
    $(document).on('input', '.color-hex-input', function(){
        var val = $(this).val();
        if(/^#[0-9a-f]{6}$/i.test(val)) {
            $(this).closest('.input-group').find('input[type=color]').val(val);
        }
    });

    // Copy address
    $('.copy-addr-btn').on('click', function(){
        var addr = $(this).data('address');
        navigator.clipboard.writeText(addr).then(function(){
            throwMessage('success', ['{{ __("Address copied!") }}']);
        }).catch(function(){
            var $input = $('<input>');
            $('body').append($input);
            $input.val(addr).select();
            document.execCommand('copy');
            $input.remove();
            throwMessage('success', ['{{ __("Address copied!") }}']);
        });
    });

    // Show/hide inline QR code
    $('.show-qr-btn').on('click', function(){
        var btn = $(this);
        var id = btn.data('id');
        var address = btn.data('address');
        var wrapper = $('#qr-wrapper-' + id);

        if(wrapper.is(':visible')) {
            wrapper.slideUp();
            btn.html('<i class="las la-qrcode"></i> {{ __("Show QR") }}');
            return;
        }

        btn.html('<i class="las la-times"></i> {{ __("Close QR") }}');
        wrapper.slideDown();

        var container = document.getElementById('qrcode-' + id);
        container.innerHTML = '';
        var darkColor = $('body').hasClass('dark-version') ? '#F1F5F9' : '#0F172A';

        new QRCode(container, {
            text: address,
            width: 140,
            height: 140,
            colorDark: darkColor,
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    });

    // Download QR code as PNG
    $(document).on('click', '.download-qr-btn', function(){
        var id = $(this).data('id');
        var filename = $(this).data('filename');
        var canvas = document.querySelector('#qrcode-' + id + ' canvas');
        if(!canvas) {
            throwMessage('error', ['{{ __("Generate QR code first") }}']);
            return;
        }
        var link = document.createElement('a');
        link.download = filename + '.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        throwMessage('success', ['{{ __("QR code downloaded!") }}']);
    });
});
</script>
@endpush
