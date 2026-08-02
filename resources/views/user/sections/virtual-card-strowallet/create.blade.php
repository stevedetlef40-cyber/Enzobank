@extends('user.layouts.rise-master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __(@$page_title)])
@endsection

@section('content')
<div class="vc-create-page">
    <div class="dashboard-area mt-10">
        <div class="dashboard-header-wrapper">
            <h3 class="title">{{__(@$page_title)}}</h3>
        </div>
    </div>
    <div class="row justify-content-center">
        {{-- create card customer  --}}
        @if($user->strowallet_customer == null)
            @include('user.sections.virtual-card-strowallet.component.create-customer')
        @endif
        {{-- check and update for customer  --}}
        @if(isset($user->strowallet_customer) )
            @if(isset($user->strowallet_customer->status) && $user->strowallet_customer->status ==  global_const()::CARD_UNDER_STATUS || $user->strowallet_customer->status ==  global_const()::CARD_LOW_KYC_STATUS)
            @include('user.sections.virtual-card-strowallet.component.check-customer-status')
            @endif
        @endif
        {{-- Create card  --}}
        @if(isset($user->strowallet_customer))
            @if(isset($user->strowallet_customer->status) && $user->strowallet_customer->status ==  global_const()::CARD_HIGH_KYC_STATUS)
            @include('user.sections.virtual-card-strowallet.component.create-card')
            @endif
        @endif
    </div>
</div>

@endsection

@push('css')
<style>
.vc-create-page { animation: vcCardIn .5s ease both; }
/* Surfaces follow the app theme (fix dark-mode inconsistency) */
.vc-create-page .dash-payment-item {
    background: var(--bg-card, #FFFFFF);
    border: 1px solid var(--border-color, #E2E8F0);
    box-shadow: var(--card-shadow, 0 1px 3px rgba(0,0,0,0.06));
    border-radius: 20px;
    margin-bottom: 24px;
    animation: vcCardIn .45s cubic-bezier(.22,1,.36,1) both;
}
.vc-create-page .dash-payment-item.active { border-color: var(--border-strong, #CBD5E1); }
.vc-create-page .dash-payment-item-wrapper:nth-child(2) .dash-payment-item { animation-delay: .06s; }
/* Titles + labels use theme text tokens (fix low-contrast) */
.vc-create-page .dash-payment-title-area .title,
.vc-create-page .dashboard-header-wrapper .title { color: var(--text-primary, #0F172A); }
.vc-create-page .dash-payment-item label,
.vc-create-page .card-form label {
    color: var(--text-primary, #0F172A);
    font-weight: 600;
    margin-bottom: 8px;
}
/* Helper / asterisk text = secondary, not competing with labels */
.vc-create-page .text--base { color: var(--text-secondary, #475569) !important; font-weight: 600; }
.vc-create-page .text--base small { font-weight: 500; color: var(--text-muted, #64748B) !important; }
/* Inputs follow the app theme */
.vc-create-page .form--control {
    background-color: var(--input-bg, #F8FAFC);
    border: 1px solid var(--input-border, #E2E8F0);
    color: var(--text-primary, #0F172A);
    border-radius: 12px;
    height: 50px;
}
.vc-create-page .form--control:focus {
    border-color: var(--accent, #1D4ED8);
    box-shadow: 0 0 0 3px var(--accent-soft, rgba(29,78,216,0.12)) !important;
}
.vc-create-page .form--control::placeholder { color: var(--placeholder, #94A3B8); }
/* Consistent spacing between fields */
.vc-create-page .card-form .form-group { margin-bottom: 18px; }
.vc-create-page .card-form .row { row-gap: 4px; }
/* File inputs -> branded, rounded blue button (design-system aligned) */
.vc-create-page .form--control[type="file"] {
    background-color: var(--input-bg, #F8FAFC);
    border: 1px solid var(--input-border, #E2E8F0);
    border-radius: 12px;
    padding: 8px 12px;
}
.vc-create-page .form--control[type="file"]::before {
    border-radius: 8px;
    background: var(--gradient, linear-gradient(135deg,#1D4ED8,#2563EB));
}
/* Submit button hover / press transitions */
.vc-create-page .btn--base {
    transition: transform .15s ease, box-shadow .15s ease, opacity .15s ease, filter .15s ease;
    border-radius: 12px;
}
.vc-create-page .btn--base:hover { transform: translateY(-1px); filter: brightness(1.03); }
.vc-create-page .btn--base:active { transform: scale(.985); }
@keyframes vcCardIn { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
</style>
@endpush

@push('script')
<script>
     var defualCurrency = "{{ get_default_currency_code() }}";
     var defualCurrencyRate = "{{ get_default_currency_rate() }}";
     $(document).ready(function(){
           getLimit();
           getFees();
           getPreview();
       });
       $("input[name=card_amount]").keyup(function(){
            getFees();
            getPreview();
       });
       $("input[name=card_amount]").focusout(function(){
            enterLimit();
       });
       function getLimit() {
           var currencyCode = acceptVar().currencyCode;
           var currencyRate = acceptVar().currencyRate;

           var min_limit = acceptVar().currencyMinAmount;
           var max_limit =acceptVar().currencyMaxAmount;
           if($.isNumeric(min_limit) || $.isNumeric(max_limit)) {
               var min_limit_calc = parseFloat(min_limit/currencyRate).toFixed(2);
               var max_limit_clac = parseFloat(max_limit/currencyRate).toFixed(2);
               $('.limit-show').html("{{ __('limit') }} " + min_limit_calc + " " + currencyCode + " - " + max_limit_clac + " " + currencyCode);

               return {
                   minLimit:min_limit_calc,
                   maxLimit:max_limit_clac,
               };
           }else {
               $('.limit-show').html("--");
               return {
                   minLimit:0,
                   maxLimit:0,
               };
           }
       }
       function acceptVar() {

           var currencyCode = defualCurrency;
           var currencyRate = defualCurrencyRate;
           var currencyMinAmount ="{{getAmount($cardCharge->min_limit)}}";
           var currencyMaxAmount = "{{getAmount($cardCharge->max_limit)}}";
           var currencyFixedCharge = "{{getAmount($cardCharge->fixed_charge)}}";
           var currencyPercentCharge = "{{getAmount($cardCharge->percent_charge)}}";


           return {
               currencyCode:currencyCode,
               currencyRate:currencyRate,
               currencyMinAmount:currencyMinAmount,
               currencyMaxAmount:currencyMaxAmount,
               currencyFixedCharge:currencyFixedCharge,
               currencyPercentCharge:currencyPercentCharge,


           };
       }
       function feesCalculation() {
           var currencyCode = acceptVar().currencyCode;
           var currencyRate = acceptVar().currencyRate;
           var sender_amount = $("input[name=card_amount]").val();
           sender_amount == "" ? (sender_amount = 0) : (sender_amount = sender_amount);

           var fixed_charge = acceptVar().currencyFixedCharge;
           var percent_charge = acceptVar().currencyPercentCharge;
           if ($.isNumeric(percent_charge) && $.isNumeric(fixed_charge) && $.isNumeric(sender_amount)) {
               // Process Calculation
               var fixed_charge_calc = parseFloat(currencyRate * fixed_charge);
               var percent_charge_calc = parseFloat(currencyRate)*(parseFloat(sender_amount) / 100) * parseFloat(percent_charge);
               var total_charge = parseFloat(fixed_charge_calc) + parseFloat(percent_charge_calc);
               total_charge = parseFloat(total_charge).toFixed(2);
               // return total_charge;
               return {
                   total: total_charge,
                   fixed: fixed_charge_calc,
                   percent: percent_charge,
               };
           } else {
               // return "--";
               return false;
           }
       }

       function getFees() {
           var currencyCode = acceptVar().currencyCode;
           var percent = acceptVar().currencyPercentCharge;
           var charges = feesCalculation();
           if (charges == false) {
               return false;
           }
           $(".fees-show").html("{{ __('Fees') }}: " + parseFloat(charges.fixed).toFixed(2) + " " + currencyCode + " + " + parseFloat(charges.percent).toFixed(2) + "% = " + parseFloat(charges.total).toFixed(2) + " " + currencyCode);
       }
       function getPreview() {
            var senderAmount = $("input[name=card_amount]").val();
            var charges = feesCalculation();
            var sender_currency = acceptVar().currencyCode;
            var sender_currency_rate = acceptVar().currencyRate;

            senderAmount == "" ? senderAmount = 0 : senderAmount = senderAmount;
            // Sending Amount
            $('.request-amount').html( senderAmount + " " + sender_currency);

            // Fees
            var charges = feesCalculation();
            var total_charge = 0;
            if(senderAmount == 0){
                total_charge = 0;
            }else{
                total_charge = charges.total;
            }
            $('.fees').html( total_charge + " " + sender_currency);
            var totalPay = parseFloat(senderAmount) * parseFloat(sender_currency_rate)
            var pay_in_total = 0;
            if(senderAmount == 0 ||  senderAmount == ''){
                pay_in_total = 0;
            }else{
                pay_in_total =  parseFloat(totalPay) + parseFloat(charges.total);
            }
            $('.payable-total').html( pay_in_total + " " + sender_currency);

       }
       function enterLimit(){
        var min_limit = parseFloat("{{getAmount($cardCharge->min_limit)}}");
        var max_limit =parseFloat("{{getAmount($cardCharge->max_limit)}}");
        var currencyRate = acceptVar().currencyRate;
        var sender_amount = parseFloat($("input[name=card_amount]").val());

        if( sender_amount < min_limit ){
            throwMessage('error',['{{ __("Please follow the mimimum limit") }}']);
            $('.buyBtn').attr('disabled',true)
        }else if(sender_amount > max_limit){
            throwMessage('error',['{{ __("Please follow the maximum limit") }}']);
            $('.buyBtn').attr('disabled',true)
        }else{
            $('.buyBtn').attr('disabled',false)
        }

       }
</script>
@endpush
