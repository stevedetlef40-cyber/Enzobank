@extends('user.layouts.rise-master')

@push('css')

@endpush

@section('content')
@php
$instance = $temp_data->data->instance ?? $temp_data->data;
@endphp

<div class="am-header">
    <h1 class="am-header-title">{{ __($page_title) }}</h1>
</div>

<div class="am-body">
    <div class="am-card">
        <div class="am-card-title">Confirm Payment</div>

        <div class="am-preview-row">
            <div class="am-preview-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <span class="am-preview-label">{{ __('Request Amount') }}</span>
            <span class="am-preview-value enter-amount">{{ get_amount($instance->amount->requested_amount, $instance->amount->default_currency) }}</span>
        </div>
        <div class="am-preview-row">
            <div class="am-preview-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
            <span class="am-preview-label">{{ __('Exchange Rate') }}</span>
            <span class="am-preview-value exchange-rate">1 {{ get_default_currency_code() }} = {{ get_amount($instance->amount->exchange_rate,$instance->amount->sender_cur_code,2) }}</span>
        </div>
        <div class="am-preview-row">
            <div class="am-preview-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <span class="am-preview-label">{{ __('Total Fees & Charges') }}</span>
            <span class="am-preview-value fees">{{ get_amount($instance->amount->total_charge, $instance->amount->default_currency) }}</span>
        </div>
        <div class="am-preview-row">
            <div class="am-preview-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            </div>
            <span class="am-preview-label">{{ __('Will Get') }}</span>
            <span class="am-preview-value will-get">{{ get_amount($instance->amount->will_get, $instance->amount->default_currency) }}</span>
        </div>
        <div class="am-preview-row">
            <div class="am-preview-icon" style="border-color:#3B82F6;color:#3B82F6;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <span class="am-preview-label">{{ __('Total Payable Amount') }}</span>
            <span class="am-preview-value" style="color:#3B82F6;">{{ get_amount($instance->amount->total_amount, $instance->amount->sender_cur_code) }}</span>
        </div>

        <div style="margin-top:20px;">
            <form action="{{ route('user.add.money.preview.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="temp_token" value="{{ $temp_data->identifier }}">
                <button type="button" class="am-btn" data-bs-toggle="modal" data-bs-target="#checkPin">{{ __('Confirm') }} →</button>
                @include('user.components.modal.pin-check')
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $('.finalConfirmed').hide();
    $('.pinCheck').on('keyup',function(e){
        var url = "{{ route('user.check.pin') }}";
        var pin = $(this).val();
        var token = '{{ csrf_token() }}';
        if ($(this).attr('name') == 'pin') {
            var data = {pin:pin,_token:token}
        }
        $.post(url,data,function(response) {
            if(response == 1){
                if($('.exist_pi').hasClass('text--danger')){
                    $('.exist_pi').removeClass('text--danger');
                }
                $('.exist_pi').text(`{{ __('Pin matched successfully.') }}`).addClass('text--success');
                $('.finalConfirmed').show();
                $('.finalConfirmed').attr('disabled',false)
            } else {
                if($('.exist_pi').hasClass('text--success')){
                    $('.exist_pi').removeClass('text--success');
                }
                $('.exist_pi').text('{{ __("Your entered pin does not matched.") }}').addClass('text--danger');
                $('.finalConfirmed').attr('disabled',true)
                $('.finalConfirmed').hide();
                return false
            }
        });
    });
</script>
@endpush

@push('script')
<script>
    $('.finalConfirmed').hide();
    $('.pinCheck').on('keyup',function(e){
        var url = "{{ route('user.check.pin') }}";
        var pin = $(this).val();
        var token = '{{ csrf_token() }}';
        if ($(this).attr('name') == 'pin') {
            var data = {pin:pin,_token:token}
        }
        $.post(url,data,function(response) {
            if(response == 1){
                if($('.exist_pi').hasClass('text--danger')){
                    $('.exist_pi').removeClass('text--danger');
                }
                $('.exist_pi').text(`{{ __('Pin matched successfully.') }}`).addClass('text--success');
                $('.finalConfirmed').show();
                $('.finalConfirmed').attr('disabled',false)
            } else {

                if($('.exist_pi').hasClass('text--success')){
                    $('.exist_pi').removeClass('text--success');
                }
                $('.exist_pi').text('{{ __("Your entered pin does not matched.") }}').addClass('text--danger');
                $('.finalConfirmed').attr('disabled',true)
                $('.finalConfirmed').hide();
                return false
            }

        });
    });
</script>
@endpush
