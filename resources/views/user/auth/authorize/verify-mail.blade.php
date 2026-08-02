@extends('frontend.layouts.master')

@push('css')
<style>
  .reg-otp-section{
    min-height: 100vh;
    display:flex; align-items:center; justify-content:center;
    padding: 70px 16px;
    position:relative; overflow:hidden;
    background: linear-gradient(135deg,#0b1f4d 0%, #14307a 45%, #2b1d6b 100%);
    background-size: 220% 220%;
    animation: otpBgShift 14s ease infinite;
  }
  @keyframes otpBgShift{
    0%{background-position:0% 50%}
    50%{background-position:100% 50%}
    100%{background-position:0% 50%}
  }
  .reg-otp-section .blob{
    position:absolute; border-radius:50%; filter:blur(6px); opacity:.35;
    animation: floaty 9s ease-in-out infinite;
  }
  .blob.b1{width:240px;height:240px;background:#3b5bdb;top:-50px;left:-40px;animation-delay:0s}
  .blob.b2{width:170px;height:170px;background:#3B82F6;bottom:-30px;right:7%;animation-delay:2s}
  .blob.b3{width:130px;height:130px;background:#7048e8;top:28%;right:-25px;animation-delay:4.5s}
  @keyframes floaty{0%,100%{transform:translateY(0)}50%{transform:translateY(-32px)}}

  .reg-otp-card{
    position:relative; z-index:2; width:100%; max-width:460px;
    background: rgba(255,255,255,.97);
    border-radius:22px; padding:40px 34px 32px;
    box-shadow: 0 30px 70px rgba(0,0,0,.35);
    animation: cardIn .7s cubic-bezier(.2,.8,.2,1) both;
  }
  @keyframes cardIn{from{opacity:0; transform:translateY(28px) scale(.98)}to{opacity:1;transform:none}}

  .reg-otp-mail{
    width:78px;height:78px;margin:0 auto 14px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    background:linear-gradient(135deg,#1D4ED8,#3B82F6);
    color:#fff;font-size:34px;
    box-shadow:0 12px 26px rgba(37,99,235,.45);
    animation: mailPulse 2.6s ease-in-out infinite;
  }
  @keyframes mailPulse{
    0%,100%{transform:translateY(0);box-shadow:0 12px 26px rgba(37,99,235,.45)}
    50%{transform:translateY(-8px);box-shadow:0 18px 34px rgba(37,99,235,.6)}
  }
  .reg-otp-mail i{animation: mailWiggle 3.2s ease-in-out infinite}
  @keyframes mailWiggle{0%,90%,100%{transform:rotate(0)}93%{transform:rotate(-12deg)}97%{transform:rotate(12deg)}}

  .reg-otp-card h2{text-align:center;font-weight:800;color:#0b1f4d;margin:0 0 6px;font-size:24px}
  .reg-otp-sub{text-align:center;color:#5c6b8a;font-size:14px;margin:0 0 24px;line-height:1.5}
  .reg-otp-sub b{color:#3b5bdb}

  .reg-otp-inputs{display:flex;gap:10px;justify-content:center;margin-bottom:18px}
  .reg-otp-inputs input{
    width:52px;height:60px;text-align:center;font-size:24px;font-weight:700;
    border:2px solid #dfe4f0;border-radius:14px;color:#0b1f4d !important;background:#f7f9ff;-webkit-text-fill-color:#0b1f4d !important;
    transition:border-color .2s, box-shadow .2s, transform .15s; outline:none;
  }
  .reg-otp-inputs input:focus{
    border-color:#3b5bdb;
    box-shadow:0 0 0 4px rgba(59,91,219,.18);
    transform:translateY(-3px);
  }
  .reg-otp-inputs input.filled{border-color:#3B82F6;background:#eff6ff}

  .reg-otp-timer{text-align:center;color:#8a93a8;font-size:13px;margin-bottom:16px}
  .reg-otp-timer #time{font-weight:700;color:#0b1f4d}
  .reg-otp-resend{display:none;text-align:center;margin-bottom:16px;font-size:14px;color:#5c6b8a}
  .reg-otp-resend a{color:#3b5bdb;font-weight:700;text-decoration:none}
  .reg-otp-resend a:hover{text-decoration:underline}

  .reg-otp-btn{
    width:100%;border:none;border-radius:14px;padding:14px;font-weight:700;font-size:16px;
    color:#fff;cursor:pointer;
    background:linear-gradient(135deg,#3b5bdb,#5f3dc4);
    transition:transform .15s, box-shadow .2s, filter .2s;
    box-shadow:0 12px 26px rgba(59,91,219,.4);
  }
  .reg-otp-btn:hover{transform:translateY(-2px);filter:brightness(1.05);box-shadow:0 16px 32px rgba(59,91,219,.5)}
  .reg-otp-btn:active{transform:translateY(0)}
  .reg-otp-btn.loading{opacity:.7;pointer-events:none}

  .reg-otp-back{text-align:center;margin-top:16px}
  .reg-otp-back a{color:#8a93a8;font-size:13px;text-decoration:none}
  .reg-otp-back a:hover{color:#3b5bdb}

  .reg-otp-check{display:none;text-align:center;margin-bottom:14px}
  .reg-otp-check .ring{
    width:72px;height:72px;border-radius:50%;background:#eff6ff;color:#3B82F6;
    display:inline-flex;align-items:center;justify-content:center;font-size:36px;
    animation: popIn .5s cubic-bezier(.2,.8,.2,1) both;
  }
  @keyframes popIn{from{transform:scale(0)}to{transform:scale(1)}}

  @media (max-width:480px){
    .reg-otp-inputs input{width:44px;height:54px;font-size:20px}
    .reg-otp-card{padding:32px 20px 26px}
  }
</style>
@endpush

@section('content')
<section class="reg-otp-section">
    <span class="blob b1"></span>
    <span class="blob b2"></span>
    <span class="blob b3"></span>

    <div class="reg-otp-card">
        <div class="reg-otp-mail"><i class="las la-envelope"></i></div>
        <h2>{{ __('Verify your email') }}</h2>
        <p class="reg-otp-sub">{{ __('We sent a 6-digit code to') }} <b>{{ Auth::user()->email ?? '' }}</b></p>

        <div class="reg-otp-check" id="otpCheck"><span class="ring"><i class="las la-check"></i></span></div>

        <form class="reg-otp-form" id="otpForm" method="POST" action="{{ route('user.authorize.mail.verify', $token) }}">
            @csrf
            <div class="reg-otp-inputs" id="otpInputs">
                <input class="reg-otp-box" name="code[]" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="1" required style="color:#0b1f4d !important;-webkit-text-fill-color:#0b1f4d !important;">
                <input class="reg-otp-box" name="code[]" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="1" required style="color:#0b1f4d !important;-webkit-text-fill-color:#0b1f4d !important;">
                <input class="reg-otp-box" name="code[]" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="1" required style="color:#0b1f4d !important;-webkit-text-fill-color:#0b1f4d !important;">
                <input class="reg-otp-box" name="code[]" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="1" required style="color:#0b1f4d !important;-webkit-text-fill-color:#0b1f4d !important;">
                <input class="reg-otp-box" name="code[]" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="1" required style="color:#0b1f4d !important;-webkit-text-fill-color:#0b1f4d !important;">
                <input class="reg-otp-box" name="code[]" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="1" required style="color:#0b1f4d !important;-webkit-text-fill-color:#0b1f4d !important;">
            </div>

            <div class="reg-otp-timer" id="timerWrap">{{ __('Resend code in') }} <span id="time">--</span></div>
            <div class="reg-otp-resend" id="resendWrap">{{ __("Didn't get the code?") }} <a href="{{ route('user.authorize.mail.resend', $token) }}">{{ __('Resend') }}</a></div>

            <button type="submit" class="reg-otp-btn" id="otpSubmit">{{ __('Verify email') }}</button>
        </form>

        <div class="reg-otp-back">
            <a href="{{ route('user.login') }}"><i class="las la-sign-out-alt"></i> {{ __('Use a different account') }}</a>
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
  (function(){
    var boxes = Array.prototype.slice.call(document.querySelectorAll('.reg-otp-box'));
    var form  = document.getElementById('otpForm');
    if(!boxes.length || !form) return;
    boxes[0].focus();

    function onlyDigits(el){
      el.value = el.value.replace(/\D/g,'').slice(0,1);
      el.classList.toggle('filled', el.value !== '');
    }

    boxes.forEach(function(box, i){
      box.addEventListener('input', function(){
        onlyDigits(box);
        if(box.value && i < boxes.length - 1) boxes[i + 1].focus();
      });
      box.addEventListener('keydown', function(e){
        if(e.key === 'Backspace' && !box.value && i > 0){ boxes[i - 1].focus(); }
        if(e.key === 'ArrowLeft'  && i > 0){ boxes[i - 1].focus(); }
        if(e.key === 'ArrowRight' && i < boxes.length - 1){ boxes[i + 1].focus(); }
      });
      box.addEventListener('paste', function(e){
        e.preventDefault();
        var text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'').slice(0, boxes.length);
        for(var k = 0; k < text.length; k++){ boxes[k].value = text[k]; boxes[k].classList.add('filled'); }
        var next = Math.min(text.length, boxes.length - 1);
        boxes[next].focus();
      });
    });

    form.addEventListener('submit', function(){
      document.getElementById('otpSubmit').classList.add('loading');
    });

    var resendSeconds = 59;
    var remaining = resendSeconds;
    var timeEl = document.getElementById('time');
    var timerWrap = document.getElementById('timerWrap');
    var resendWrap = document.getElementById('resendWrap');

    function tick(){
      if(remaining > 0){
        var m = Math.floor(remaining / 60);
        var s = remaining % 60;
        timeEl.textContent = (m > 0 ? m + 'm ' : '') + s + 's';
        remaining--;
        setTimeout(tick, 1000);
      } else {
        timerWrap.style.display = 'none';
        resendWrap.style.display = 'block';
      }
    }
    tick();
  })();
</script>
@endpush
