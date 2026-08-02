@extends('user.layouts.rise-master')

@section('content')
@php
$user = auth()->user();
$referralLink = url('/register/' . $user->username);
@endphp

<div class="am-header">
    <h1 class="am-header-title">{{ __('Refer & Earn') }}</h1>
    <span style="font-size:12px;font-weight:600;color:#3B82F6;background:rgba(59,130,246,0.12);padding:4px 12px;border-radius:12px;">$50 / referral</span>
</div>

<div class="am-body">

    {{-- Banner --}}
    <div style="background:linear-gradient(135deg,#0F172A,#1E3A5F,#1E40AF);border-radius:16px;padding:24px 20px;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;border-radius:50%;background:rgba(59,130,246,0.12);"></div>
        <div style="position:absolute;bottom:-20px;left:-20px;width:80px;height:80px;border-radius:50%;background:rgba(59,130,246,0.08);"></div>
        <div style="position:relative;z-index:1;">
            <div style="font-size:20px;font-weight:700;color:#fff;margin-bottom:4px;">{{ __('Share & Start Earning') }}</div>
            <div style="font-size:13px;color:rgba(255,255,255,0.7);line-height:1.5;">{{ __('Invite your friends to join EnzoBank. You earn $50 for each friend who signs up and deposits $600 or more.') }}</div>
        </div>
    </div>

    {{-- Welcome Message --}}
    <div style="font-size:18px;font-weight:700;color:var(--text-primary);padding:0 2px;">{{ __('Welcome back') }}, {{ $user->fullname ?? $user->username }}!</div>

    {{-- Stats Row --}}
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
        <div style="background:var(--bg-elevated);border:1px solid var(--border-color);border-radius:14px;padding:16px 12px;text-align:center;">
            <div style="font-size:24px;font-weight:800;color:var(--text-primary);">{{ $referral_count ?? 0 }}</div>
            <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">{{ __('Referrals') }}</div>
        </div>
        <div style="background:var(--bg-elevated);border:1px solid var(--border-color);border-radius:14px;padding:16px 12px;text-align:center;">
            <div style="font-size:24px;font-weight:800;color:var(--text-primary);" data-currency-amount="{{ number_format($usd_balance ?? 0, 2) }}">${{ number_format($usd_balance ?? 0, 2) }}</div>
            <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">{{ __('Wallet') }}</div>
        </div>
        <div style="background:var(--bg-elevated);border:1px solid var(--border-color);border-radius:14px;padding:16px 12px;text-align:center;">
            <div style="font-size:24px;font-weight:800;color:#3B82F6;" data-currency-amount="{{ number_format($referral_earnings ?? 0, 2) }}">${{ number_format($referral_earnings ?? 0, 2) }}</div>
            <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">{{ __('Earned') }}</div>
        </div>
    </div>

    {{-- Referral Link --}}
    <div class="ps-card">
        <div class="ps-card-title">{{ __('Your Referral Link') }}</div>
        <p style="font-size:13px;color:var(--text-secondary);margin:-8px 0 12px;">{{ __('Share this link with friends to earn rewards.') }}</p>
        <div style="display:flex;gap:8px;">
            <input type="text" id="referLinkInput" value="{{ $referralLink }}" readonly
                   style="flex:1;padding:12px 14px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--input-bg);color:var(--text-primary);outline:none;">
            <button onclick="copyRefLink()" id="copyRefBtn"
                    style="padding:12px 18px;background:#3B82F6;color:var(--text-primary);border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;">{{ __('Copy') }}</button>
        </div>
        <div style="display:flex;gap:8px;margin-top:10px;">
            <button onclick="shareRefLink()" style="flex:1;padding:10px;border-radius:10px;background:var(--input-bg);color:#3B82F6;font-size:13px;font-weight:600;border:1px solid var(--border-color);cursor:pointer;">{{ __('Share via') }} WhatsApp</button>
            <button onclick="shareRefSMS()" style="flex:1;padding:10px;border-radius:10px;background:var(--input-bg);color:#3B82F6;font-size:13px;font-weight:600;border:1px solid var(--border-color);cursor:pointer;">{{ __('Share via') }} SMS</button>
        </div>
    </div>

    {{-- How It Works --}}
    <div class="ps-card">
        <div class="ps-card-title">{{ __('How It Works') }}</div>
        <div style="display:flex;flex-direction:column;gap:16px;">
            {{-- Step 1 --}}
            <div style="display:flex;gap:14px;align-items:flex-start;">
                <div style="width:32px;height:32px;border-radius:50%;background:rgba(59,130,246,0.12);color:#3B82F6;font-size:14px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">1</div>
                <div>
                    <div style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:2px;">{{ __('Share Your Link') }}</div>
                    <div style="font-size:13px;color:var(--text-secondary);line-height:1.4;">{{ __('Send your unique referral link to friends via WhatsApp, SMS, email, or social media.') }}</div>
                </div>
            </div>
            {{-- Step 2 --}}
            <div style="display:flex;gap:14px;align-items:flex-start;">
                <div style="width:32px;height:32px;border-radius:50%;background:rgba(59,130,246,0.12);color:#3B82F6;font-size:14px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">2</div>
                <div>
                    <div style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:2px;">{{ __('Friend Signs Up') }}</div>
                    <div style="font-size:13px;color:var(--text-secondary);line-height:1.4;">{{ __('They register using your link and create an account. You\'re credited as their referrer.') }}</div>
                </div>
            </div>
            {{-- Step 3 --}}
            <div style="display:flex;gap:14px;align-items:flex-start;">
                <div style="width:32px;height:32px;border-radius:50%;background:rgba(59,130,246,0.12);color:#3B82F6;font-size:14px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">3</div>
                <div>
                    <div style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:2px;">{{ __('They Deposit $600+') }}</div>
                    <div style="font-size:13px;color:var(--text-secondary);line-height:1.4;">{{ __('Your friend adds $600 or more to their account. Both of you unlock withdrawal access.') }}</div>
                </div>
            </div>
            {{-- Step 4 --}}
            <div style="display:flex;gap:14px;align-items:flex-start;">
                <div style="width:32px;height:32px;border-radius:50%;background:rgba(59,130,246,0.12);color:#3B82F6;font-size:14px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">4</div>
                <div>
                    <div style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:2px;">{{ __('You Earn $50') }}</div>
                    <div style="font-size:13px;color:var(--text-secondary);line-height:1.4;">{{ __('Once their deposit is confirmed, $50 is credited to your wallet. Refer more, earn more!') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Withdrawal Instructions --}}
    <div class="ps-card">
        <div class="ps-card-title">{{ __('Withdraw Your Referral Earnings') }}</div>
        <p style="font-size:13px;color:var(--text-secondary);margin:-8px 0 12px;">{{ __('Referral earnings can be withdrawn once your referred friends have met the deposit requirement.') }}</p>
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <span style="width:20px;height:20px;border-radius:50%;background:rgba(59,130,246,0.1);color:#3B82F6;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">1</span>
                <span style="font-size:13px;color:var(--text-secondary);line-height:1.4;">{{ __('Ensure at least one referred friend has deposited $600 or more (status: completed).') }}</span>
            </div>
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <span style="width:20px;height:20px;border-radius:50%;background:rgba(59,130,246,0.1);color:#3B82F6;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">2</span>
                <span style="font-size:13px;color:var(--text-secondary);line-height:1.4;">{{ __('Go to the Withdraw page and select your preferred withdrawal method.') }}</span>
            </div>
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <span style="width:20px;height:20px;border-radius:50%;background:rgba(59,130,246,0.1);color:#3B82F6;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">3</span>
                <span style="font-size:13px;color:var(--text-secondary);line-height:1.4;">{{ __('Enter the amount you wish to withdraw (up to your available referral earnings).') }}</span>
            </div>
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <span style="width:20px;height:20px;border-radius:50%;background:rgba(59,130,246,0.1);color:#3B82F6;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">4</span>
                <span style="font-size:13px;color:var(--text-secondary);line-height:1.4;">{{ __('Confirm the withdrawal. Funds are sent to your selected account or payment method.') }}</span>
            </div>
        </div>
        <a href="{{ route('user.money-out.index') }}" class="ps-btn-blue" style="display:block;text-align:center;margin-top:16px;">{{ __('Withdraw Now') }}</a>
    </div>

    {{-- Referral List (if any) --}}
    @php
    $referredUsers = \App\Models\User::where('referral_id', $user->id)->orderBy('created_at', 'desc')->take(20)->get();
    @endphp
    @if($referredUsers->count() > 0)
    <div class="ps-card">
        <div class="ps-card-title">{{ __('Your Referrals') }} <span style="font-size:12px;font-weight:500;color:var(--text-secondary);">({{ $referredUsers->count() }})</span></div>
        <div style="display:flex;flex-direction:column;">
            @foreach($referredUsers as $ref)
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border-color);">
                <div style="width:36px;height:36px;border-radius:50%;background:rgba(59,130,246,0.1);color:#3B82F6;font-size:14px;font-weight:700;display:flex;align-items:center;justify-content:center;text-transform:uppercase;flex-shrink:0;">
                    {{ substr($ref->fullname ?? $ref->username, 0, 1) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:14px;font-weight:600;color:var(--text-primary);">{{ $ref->fullname ?? $ref->username }}</div>
                    <div style="font-size:12px;color:var(--text-secondary);">{{ $ref->email }}</div>
                </div>
                <div style="font-size:12px;color:var(--text-secondary);white-space:nowrap;">{{ $ref->created_at->format('M d, Y') }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div style="display:flex;flex-direction:column;align-items:center;padding:40px 20px;text-align:center;gap:8px;background:var(--bg-elevated);border:1px solid var(--border-color);border-radius:16px;">
        <div style="font-size:40px;line-height:1;">👥</div>
        <div style="font-size:16px;font-weight:700;color:var(--text-primary);">{{ __('No referrals yet') }}</div>
        <div style="font-size:13px;color:var(--text-secondary);max-width:280px;">{{ __('Share your referral link with friends and family to start earning $50 per referral.') }}</div>
    </div>
    @endif

</div>
@endsection

@push('script')
<script>
function copyRefLink() {
    var input = document.getElementById('referLinkInput');
    var btn = document.getElementById('copyRefBtn');
    if (navigator.clipboard) {
        navigator.clipboard.writeText(input.value).then(function() {
            var orig = btn.textContent;
            btn.textContent = 'Copied!';
            setTimeout(function() { btn.textContent = orig; }, 2000);
        });
    } else {
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand('copy');
    }
}

function shareRefLink() {
    var link = document.getElementById('referLinkInput').value;
    var text = "{{ __('Join EnzoBank and earn rewards! Sign up using my referral link:') }} " + link;
    var url = 'https://wa.me/?text=' + encodeURIComponent(text);
    window.open(url, '_blank');
}

function shareRefSMS() {
    var link = document.getElementById('referLinkInput').value;
    var text = "{{ __('Join EnzoBank and earn rewards! Sign up using my referral link:') }} " + link;
    window.open('sms:?body=' + encodeURIComponent(text), '_blank');
}
</script>
@endpush
