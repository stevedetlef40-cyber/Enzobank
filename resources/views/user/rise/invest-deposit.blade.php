@extends('user.layouts.rise-master')

@section('content')
@php
$plan = $plan ?? null;
$wallet = $wallet ?? null;
$amount = $amount ?? 0;
$returnAmount = $returnAmount ?? 0;
@endphp

<div class="am-header">
    <h1 class="am-header-title">Complete Deposit</h1>
</div>

<div class="am-body">
    <!-- Plan Summary -->
    <div class="am-card ip-accent-left">
        <div class="ip-text-muted" style="font-size:13px;margin-bottom:4px;">{{ $plan->name ?? '-' }}</div>
        <div style="font-weight:700;font-size:20px;">${{ number_format($amount, 2) }}</div>
        <div class="ip-text-green" style="font-size:13px;margin-top:4px;">Returns: ${{ number_format($returnAmount, 2) }} ({{ $plan->roi_percent ?? 0 }}% ROI)</div>
    </div>

    <!-- Wallet Address Card -->
    <div class="am-card">
        <div class="am-card-title">Send Payment To</div>
        
        <!-- QR Code -->
        <div style="display:flex;justify-content:center;margin-bottom:20px;">
            <div id="qrcode" class="ip-qr" style="background: var(--bg-elevated);border:none;" data-address="{{ $wallet->wallet_address ?? '' }}"></div>
        </div>

        <!-- Wallet Address -->
        <div class="am-field-group">
            <label class="am-label">Wallet Address ({{ $wallet->network ?? '' }})</label>
            <div style="display:flex;gap:8px;">
                <input class="ps-input" id="walletAddr" value="{{ $wallet->wallet_address ?? '' }}" readonly style="flex:1;font-size:12px;word-break:break-all;">
                <button class="vc-action-btn" style="flex-shrink:0;padding:10px 14px;" onclick="copyAddr()">📋 Copy</button>
            </div>
        </div>

        <!-- Warning -->
        <div class="ip-warning" style="margin-top:12px;">
            <strong>⚠️ Important</strong><br>
            Send exactly <strong>${{ number_format($amount, 2) }}</strong> worth of <strong>{{ $wallet->symbol ?? '' }}</strong> ({{ $wallet->network ?? '' }}). Wrong amount or network = lost funds.
        </div>

        <!-- Timer -->
        <div class="ip-text-muted" style="text-align:center;margin-top:12px;font-size:14px;">
            ⏱ This address expires in <span id="timer" class="ip-text-blue" style="font-weight:700;">30:00</span>
        </div>
    </div>

    <!-- Upload Proof Form -->
    <form class="am-card" method="POST" action="{{ route('user.invest.submit.proof') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="plan_id" value="{{ $plan->id ?? '' }}">
        <input type="hidden" name="amount" value="{{ $amount }}">
        <input type="hidden" name="method" value="{{ $wallet->symbol ?? '' }}">
        <input type="hidden" name="network" value="{{ $wallet->network ?? '' }}">
        <input type="hidden" name="wallet_address_used" value="{{ $wallet->wallet_address ?? '' }}">

        <div class="am-card-title">Upload Payment Proof</div>

        <div class="am-field-group">
            <label class="am-label">Upload Screenshot</label>
            <div class="ip-upload" onclick="document.getElementById('proofInput').click()">
                <div class="ip-upload-icon">📁</div>
                <div class="ip-upload-hint" style="margin-top:8px;">Tap to upload transaction screenshot</div>
                <input type="file" id="proofInput" name="proof" accept="image/*" style="display:none;" onchange="this.closest('div').querySelector('.file-name').textContent = this.files[0]?.name || ''">
                <div class="file-name ip-upload-name" style="margin-top:4px;"></div>
            </div>
        </div>

        <div class="am-field-group">
            <label class="am-label">Or enter Transaction Hash/ID</label>
            <div class="am-input-wrap">
                <input type="text" name="tx_hash" placeholder="0x... / TX Hash / Transaction ID" required>
            </div>
        </div>

        <button type="submit" class="am-btn" style="border-radius:100px;">Submit for Review →</button>
    </form>
</div>

@push('script')
<script src="{{ asset('frontend/js/qrcode.min.js') }}?v=1"></script>
<script>
(function () {
    var box = document.getElementById('qrcode');
    if (!box) return;
    var addr = box.getAttribute('data-address') || '';
    if (!addr || typeof QRCode === 'undefined') {
        box.innerHTML = '<span class="ip-qr-text" style="color: var(--text-primary);">' + (addr || 'No address') + '</span>';
        return;
    }
    try {
        new QRCode(box, {
            text: addr,
            width: 160,
            height: 160,
            colorDark: '#0F172A',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
    } catch (e) {
        box.innerHTML = '<span class="ip-qr-text" style="color: var(--text-primary);">' + addr + '</span>';
    }
})();
</script>
<script>
function copyAddr() {
    const input = document.getElementById('walletAddr');
    input.select();
    navigator.clipboard.writeText(input.value).then(() => {
        alert('Address copied!');
    });
}

// Countdown timer
let minutes = 29;
let seconds = 59;
function updateTimer() {
    document.getElementById('timer').textContent = 
        String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    if (seconds === 0) {
        if (minutes === 0) { return; }
        minutes--;
        seconds = 59;
    } else {
        seconds--;
    }
}
setInterval(updateTimer, 1000);
</script>
@endpush
@endsection
