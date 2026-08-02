@extends('user.layouts.rise-master')

@section('content')
@php
$user = auth()->user();
@endphp

<div class="ps-banner">
    <div class="ps-banner-img" style="background:linear-gradient(135deg, #0F172A, #1E3A5F);opacity:0.3;"></div>
</div>

<div class="ps-avatar-section">
    <div class="ps-avatar-wrap" style="cursor:pointer;" title="Change photo" onclick="document.getElementById('avatarInput').click()">
        @if($user->image)
            <img id="avatarImg" src="{{ $user->userImage }}" alt="avatar" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
        @else
            <span id="avatarInitial">{{ substr($user->fullname ?? $user->username, 0, 1) }}</span>
        @endif
        <span class="ps-avatar-upload">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </span>
    </div>
    <div class="ps-user-info">
        <div class="ps-username">{{ '@' . $user->username }}</div>
    </div>
</div>

<div style="padding:0 16px;display:flex;gap:10px;margin-bottom:8px;">
    <div class="ps-info-row" style="flex:1;">
        <div class="ps-info-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </div>
        <span class="ps-info-text">{{ $user->email }}</span>
    </div>
</div>
<div style="padding:0 16px;display:flex;gap:10px;margin-bottom:16px;">
    <div class="ps-info-row" style="flex:1;">
        <div class="ps-info-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
        </div>
        <span class="ps-info-text">{{ $user->account_no ?? 'N/A' }}</span>
        <span class="ps-copy-btn" onclick="navigator.clipboard.writeText('{{ $user->account_no ?? '' }}')">Copy</span>
    </div>
</div>

<div class="ps-body">
    <!-- Refer & Earn -->
    <div class="ps-card">
        <div class="ps-card-title" style="display:flex;align-items:center;justify-content:space-between;">
            <span>{{ __('Refer & Earn') }}</span>
            <span style="font-size:12px;font-weight:500;color:#3B82F6;background:rgba(59,130,246,0.12);padding:3px 10px;border-radius:12px;">$50 / referral</span>
        </div>
        <p style="font-size:13px;color:#94A3B8;margin:0 0 12px;">{{ __('Share your referral link and earn $50 for each friend who joins and deposits.') }}</p>
        @php $referralLink = url('/register/' . auth()->user()->username); @endphp
        <div style="display:flex;gap:8px;margin-bottom:12px;">
            <input type="text" id="referralLinkInput" value="{{ $referralLink }}" readonly style="flex:1;padding:12px 14px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background: var(--input-bg);color: var(--text-primary);outline:none;">
            <button onclick="copyReferralLink()" style="padding:12px 18px;background:#3B82F6;color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;">{{ __('Copy') }}</button>
        </div>
        <a href="{{ route('user.rise.refer') }}" style="font-size:13px;color:#3B82F6;text-decoration:none;font-weight:500;">{{ __('View full referral details →') }}</a>
        <script>
        function copyReferralLink() {
            var input = document.getElementById('referralLinkInput');
            if (navigator.clipboard) {
                navigator.clipboard.writeText(input.value).then(function() {
                    var btn = input.nextElementSibling;
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
        </script>
    </div>

    <!-- Edit Profile -->
    <div class="ps-card">
        <div class="ps-card-title">Edit Profile</div>
        <form method="POST" action="{{ setRoute('user.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="file" id="avatarInput" name="image" accept="image/*" style="display:none;" onchange="uploadAvatar(this)">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div class="ps-field">
                    <label class="ps-label">First Name</label>
                    <input class="ps-input" name="firstname" value="{{ $user->firstname ?? '' }}">
                </div>
                <div class="ps-field">
                    <label class="ps-label">Last Name</label>
                    <input class="ps-input" name="lastname" value="{{ $user->lastname ?? '' }}">
                </div>
            </div>
            <div class="ps-field">
                <label class="ps-label">Country</label>
                <select class="ps-select" name="country">
                    <option value="United States" {{ ($user->address->country ?? '') == 'United States' ? 'selected' : '' }}>United States</option>
                    <option value="United Kingdom" {{ ($user->address->country ?? '') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                </select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div class="ps-field">
                    <label class="ps-label">Phone</label>
                    <input class="ps-input" name="mobile" value="{{ $user->full_mobile ?? '' }}" placeholder="+1 XXX XXX XXXX">
                </div>
                <div class="ps-field">
                    <label class="ps-label">Gender</label>
                    <select class="ps-select" name="gender">
                        <option value="">Select</option>
                        <option value="male" {{ ($user->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ ($user->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
            </div>
            <div class="ps-field">
                <label class="ps-label">Date of Birth</label>
                <input class="ps-input" name="birthdate" type="date" value="{{ $user->birthdate ?? '' }}">
            </div>
            <div class="ps-field">
                <label class="ps-label">Address</label>
                <input class="ps-input" name="address_line" value="{{ $user->address->address ?? '' }}">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div class="ps-field">
                    <label class="ps-label">City</label>
                    <input class="ps-input" name="city" value="{{ $user->address->city ?? '' }}">
                </div>
                <div class="ps-field">
                    <label class="ps-label">State</label>
                    <input class="ps-input" name="state" value="{{ $user->address->state ?? '' }}">
                </div>
            </div>
            <div class="ps-field">
                <label class="ps-label">Zip Code</label>
                <input class="ps-input" name="zip" value="{{ $user->address->zip ?? '' }}">
            </div>
            <button type="submit" class="ps-btn-blue" style="margin-top:8px;">Update</button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="ps-card">
        <div class="ps-card-title">Change Password</div>
        <form method="POST" action="{{ setRoute('user.profile.password.update') }}">
            @csrf
            @method('PUT')
            <div class="ps-field">
                <label class="ps-label">Current Password</label>
                <div class="ps-password-wrap">
                    <input class="ps-input" type="password" name="current_password">
                    <span class="ps-eye-btn" onclick="togglePass(this)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                </div>
            </div>
            <div class="ps-field">
                <label class="ps-label">New Password</label>
                <div class="ps-password-wrap">
                    <input class="ps-input" type="password" name="password">
                    <span class="ps-eye-btn" onclick="togglePass(this)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                </div>
            </div>
            <div class="ps-field">
                <label class="ps-label">Confirm Password</label>
                <div class="ps-password-wrap">
                    <input class="ps-input" type="password" name="password_confirmation">
                    <span class="ps-eye-btn" onclick="togglePass(this)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                </div>
            </div>
            <button type="submit" class="ps-btn-blue" style="margin-top:8px;">Update Password</button>
        </form>
    </div>

    <!-- Navigation Links -->
    <div class="ps-card">
        <a href="{{ setRoute('user.kyc.index') }}" style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #1E293B;">
            <div class="ps-info-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
            <span style="flex:1;font-size:14px;font-weight:500;">KYC Verification</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
        <a href="{{ setRoute('user.setup.pin.index') }}" style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #1E293B;">
            <div class="ps-info-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="12 1 12 5"/><path d="M17 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
            <span style="flex:1;font-size:14px;font-weight:500;">Change PIN</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
        <a href="{{ setRoute('user.security.google.2fa') }}" style="display:flex;align-items:center;gap:12px;padding:10px 0;">
            <div class="ps-info-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
            <span style="flex:1;font-size:14px;font-weight:500;">Two-Factor Auth</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>

    <!-- Logout -->
    <div class="ps-card">
        <form method="POST" action="{{ route('user.logout') }}" style="display:contents;">
            @csrf
            <button type="submit" class="ps-card-title" style="display:flex;align-items:center;gap:12px;padding:10px 0;color:#EF4444;text-decoration:none;background:none;border:none;width:100%;cursor:pointer;">
                <div class="ps-info-icon" style="background:rgba(239,68,68,0.1);color:#EF4444;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </div>
                <span style="flex:1;font-size:14px;font-weight:500;">{{ __('Logout') }}</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </form>
    </div>
</div>

@push('script')
<script>
function togglePass(el) {
    const input = el.parentElement.querySelector('input');
    if (input) {
        input.type = input.type === 'password' ? 'text' : 'password';
    }
}
function uploadAvatar(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const wrap = document.querySelector('.ps-avatar-wrap');
        let img = document.getElementById('avatarImg');
        const init = document.getElementById('avatarInitial');
        if (!img) {
            img = document.createElement('img');
            img.id = 'avatarImg';
            img.alt = 'avatar';
            img.style.cssText = 'width:100%;height:100%;border-radius:50%;object-fit:cover;';
            wrap.insertBefore(img, wrap.firstChild);
        }
        img.src = e.target.result;
        if (init) init.style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
    // Persist via the existing profile-update endpoint
    if (input.closest('form')) input.closest('form').submit();
}
</script>
@endpush
@endsection
