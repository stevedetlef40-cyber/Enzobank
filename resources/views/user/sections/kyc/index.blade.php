@extends('user.layouts.rise-master')

@push('css')
<style>
.kyc-badge { display:inline-block; padding:3px 12px; border-radius:20px; font-size:11px; font-weight:600; }
.kyc-badge-pending { background:rgba(245,158,11,0.15); color:#F59E0B; }
.kyc-badge-approved { background:rgba(59,130,246,0.15); color:#3B82F6; }
.kyc-badge-rejected { background:rgba(239,68,68,0.15); color:#EF4444; }
.kyc-message { font-size:14px; padding:12px 0; color:#94A3B8; }
.kyc-message-approved { color:#3B82F6; font-weight:500; }
.kyc-message-pending { color:#F59E0B; }
.kyc-message-rejected { color:#EF4444; font-weight:500; }
.kyc-reject-reason { background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.2); border-radius:10px; padding:14px; margin-bottom:16px; }
.kyc-reject-reason strong { display:block; font-size:13px; color:#F59E0B; margin-bottom:4px; }
.kyc-reject-reason p { font-size:13px; color:#94A3B8; margin:0; }
.kyc-data-row { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #1E293B; }
.kyc-data-row:last-child { border-bottom:none; }
.kyc-data-label { font-size:13px; color:#94A3B8; min-width:100px; }
.kyc-data-value { font-size:14px; color:#fff; }
.kyc-data-image { width:60px; height:60px; border-radius:8px; object-fit:cover; }
.kyc-data-link { font-size:13px; color:#3B82F6; text-decoration:none; }
.kyc-back-link { font-size:13px; color:#3B82F6; text-decoration:none; }
[data-theme="light"] .kyc-data-value { color:#0B1628; }
[data-theme="light"] .kyc-message { color:#475569; }
[data-theme="light"] .kyc-reject-reason { background:rgba(239,68,68,0.05); border-color:rgba(239,68,68,0.15); }
[data-theme="light"] .kyc-reject-reason p { color:#475569; }
[data-theme="light"] .kyc-data-row { border-color:#E2E8F0; }
</style>
@endpush

@section('content')
<div class="am-header"><h1 class="am-header-title">{{ __('KYC Verification') }}</h1></div>
<div class="am-body">
    <div class="am-card">
        @include('user.components.profile.kyc', compact('user_kyc'))
    </div>
</div>
@endsection
