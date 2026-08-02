@extends("user.layouts.rise-master")

@push("css")
<style>
.cd-addr-page { background: var(--bg-primary); min-height: calc(100vh - 72px); padding-bottom: 24px; }
.cd-addr-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 16px; position: sticky; top: 0; background: var(--bg-primary); z-index: 10; }
.cd-addr-header-left { display: flex; align-items: center; gap: 12px; }
.cd-addr-back { width: 36px; height: 36px; border-radius: 50%; background: var(--bg-card); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); box-shadow: var(--card-shadow); text-decoration: none; }
.cd-addr-title { font-size: 16px; font-weight: 700; color: var(--text-primary); }
.cd-addr-body { padding: 0 16px; display: flex; flex-direction: column; gap: 16px; }
.cd-warning-banner { display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; background: var(--warning-bg); border: 1px solid var(--warning); border-radius: 14px; font-size: 13px; color: var(--warning-text); line-height: 1.5; }
.cd-warning-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
.cd-qr-card { background: var(--bg-card); border-radius: 20px; padding: 28px 20px; box-shadow: var(--card-shadow); display: flex; flex-direction: column; align-items: center; gap: 12px; }
.cd-qr-img { width: 240px; height: 240px; border-radius: 12px; }
.cd-qr-caption { font-size: 13px; color: var(--text-muted); }
.cd-amount-card { background: var(--gradient); border-radius: 16px; padding: 20px; color: var(--text-on-accent); text-align: center; }
.cd-amount-label { font-size: 13px; opacity: 0.85; margin-bottom: 4px; }
.cd-amount-value { font-size: 32px; font-weight: 800; letter-spacing: -0.5px; }
.cd-amount-usd { font-size: 14px; opacity: 0.75; margin-top: 4px; }
.cd-address-card { background: var(--bg-card); border-radius: 16px; padding: 20px; box-shadow: var(--card-shadow); }
.cd-address-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; }
.cd-address-text { font-size: 13px; font-family: "SF Mono", "Monaco", "Cascadia Code", "Roboto Mono", monospace; word-break: break-all; line-height: 1.6; color: var(--text-primary); background: var(--input-bg); padding: 12px; border-radius: 10px; }
.cd-address-row { display: flex; align-items: flex-start; gap: 10px; }
.cd-address-row .cd-address-text { flex: 1; }
.cd-copy-btn { width: 40px; height: 40px; border-radius: 10px; background: var(--accent-soft); color: var(--accent); border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: all 0.15s; }
.cd-copy-btn:hover { background: var(--accent); color: var(--text-on-accent); }
.cd-action-row { display: flex; gap: 12px; }
.cd-action-primary { flex: 1; padding: 14px; background: var(--accent); color: var(--text-on-accent); border: none; border-radius: 999px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.15s; }
.cd-action-primary:hover { background: var(--blue); }
.cd-action-secondary { flex: 1; padding: 14px; background: var(--bg-card); color: var(--accent); border: 1.5px solid var(--accent); border-radius: 999px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.15s; }
.cd-action-secondary:hover { background: var(--accent-soft); }
.cd-toast { position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%) translateY(10px); background: var(--accent); color: var(--text-on-accent); padding: 12px 24px; border-radius: 999px; font-size: 14px; font-weight: 600; opacity: 0; transition: all 0.3s; pointer-events: none; z-index: 100; }
.cd-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
.cd-confirm-section { background: var(--bg-card); border-radius: 16px; padding: 20px; box-shadow: var(--card-shadow); }
.cd-confirm-title { font-size: 16px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px; }
.cd-checkbox-wrap { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
.cd-checkbox-wrap input[type="checkbox"] { width: 20px; height: 20px; accent-color: var(--accent); cursor: pointer; }
.cd-checkbox-wrap label { font-size: 14px; color: var(--text-secondary); cursor: pointer; }
.cd-confirm-btn { width: 100%; padding: 16px; background: var(--accent); color: var(--text-on-accent); border: none; border-radius: 999px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.15s; }
.cd-confirm-btn:disabled { background: var(--border-strong); color: var(--text-muted); cursor: not-allowed; }
.cd-confirm-btn:not(:disabled):hover { background: var(--blue); }
.cd-share-link { text-decoration: none; display: inline-flex; }
.cd-help-card { background: var(--bg-card); border: 1px solid var(--border-color); border-left: 4px solid #3B82F6; border-radius: 16px; padding: 20px; box-shadow: var(--card-shadow); }
.cd-help-head { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 14px; }
.cd-help-icon { width: 42px; height: 42px; border-radius: 12px; background: rgba(59,130,246,0.12); color: #3B82F6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.cd-help-text { flex: 1; }
.cd-help-title { font-size: 15px; font-weight: 700; color: var(--text-primary); }
.cd-help-sub { font-size: 12.5px; color: var(--text-secondary); line-height: 1.5; margin-top: 3px; }
.cd-help-cta { display: flex; align-items: center; gap: 10px; width: 100%; padding: 14px 16px; background: #3B82F6; color: #fff; border: none; border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; transition: background 0.15s; }
.cd-help-cta:hover { background: #1D4ED8; color: #fff; }
.cd-help-cta i { font-size: 20px; }
.cd-help-cta-arrow { margin-left: auto; }
.cd-help-accordion { display: flex; align-items: center; justify-content: space-between; gap: 10px; width: 100%; margin-top: 12px; padding: 12px 14px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 12px; font-size: 13px; font-weight: 600; color: var(--text-primary); cursor: pointer; text-align: left; transition: all 0.15s; }
.cd-help-accordion:hover { border-color: var(--border-strong); background: var(--bg-primary); }
.cd-help-chevron { color: var(--text-muted); transition: transform 0.2s; }
.cd-help-steps { display: none; margin-top: 12px; }
.cd-help-step { display: flex; gap: 12px; padding: 10px 0; }
.cd-help-step + .cd-help-step { border-top: 1px solid var(--border-color); }
.cd-help-step-num { width: 24px; height: 24px; border-radius: 50%; background: var(--accent); color: var(--text-on-accent); font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
.cd-help-step-body { flex: 1; }
.cd-help-step-title { font-size: 13.5px; font-weight: 700; color: var(--text-primary); }
.cd-help-step-desc { font-size: 12.5px; color: var(--text-secondary); line-height: 1.5; margin-top: 3px; }
.cd-help-note { margin-top: 10px; padding: 10px 14px; background: var(--success-bg); border: 1px solid var(--success); border-radius: 10px; font-size: 12.5px; color: var(--success-text); line-height: 1.5; }
</style>
@endpush

@section("content")
<div class="cd-addr-page">
    <div class="cd-addr-header">
        <div class="cd-addr-header-left">
            <a href="{{ setRoute("user.crypto.deposit.index") }}" class="cd-addr-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            </a>
            <span class="cd-addr-title">Deposit {{ $coin["coin"] }} ({{ $coin["network"] }})</span>
        </div>
    </div>

    <div class="cd-addr-body">
        <div class="cd-warning-banner">
            <span class="cd-warning-icon">&#9888;&#65039;</span>
            <span>Send <strong>{{ $coin["coin"] }} ({{ $coin["network"] }})</strong> to this address only. Sending other assets to this address cannot be recovered.</span>
        </div>

        <div class="cd-qr-card">
            <div id="qrcode" style="display:flex;justify-content:center;"></div>
            <span class="cd-qr-caption">{{ __("Scan to copy address") }}</span>
        </div>

        <div class="cd-amount-card">
            <div class="cd-amount-label">Send exactly:</div>
            <div class="cd-amount-value">
                @php
                    $cryptoAmount = $amount;
                    if ($coin["coin"] === "BTC") $cryptoAmount = number_format($amount / 60000, 8);
                    elseif ($coin["coin"] === "USDT") $cryptoAmount = number_format($amount, 2);
                    elseif ($coin["coin"] === "ETH") $cryptoAmount = number_format($amount / 1800, 6);
                    elseif ($coin["coin"] === "BCH") $cryptoAmount = number_format($amount / 300, 6);
                    else $cryptoAmount = number_format($amount, 2);
                @endphp
                {{ $cryptoAmount }} {{ $coin["coin"] }}
            </div>
            <div class="cd-amount-usd">${{ number_format($amount, 2) }} USD</div>
        </div>

        <div class="cd-address-card">
            <div class="cd-address-label">Wallet Address</div>
            <div class="cd-address-row">
                <div class="cd-address-text" id="walletAddress">{{ $coin["address"] }}</div>
                <button class="cd-copy-btn" onclick="copyAddress()" title="Copy address">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                </button>
            </div>
        </div>

        <div class="cd-action-row">
            <button class="cd-action-primary" onclick="copyAddress()">
                &#128203; Copy Address
            </button>
            <a href="https://wa.me/?text={{ urlencode("Please send {$cryptoAmount} {$coin["coin"]} to this address: {$coin["address"]}") }}"
               target="_blank" class="cd-action-secondary cd-share-link">
                &#8599;&#65039; Share Address
            </a>
        </div>

        @include("user.partials.crypto-support-help", [
            "coin" => $coin,
            "amount" => $amount,
            "cryptoAmount" => $cryptoAmount,
            "walletAddress" => $coin["address"],
        ])

        <div class="cd-confirm-section">
            <div class="cd-confirm-title">Confirm Payment</div>
            <div class="cd-checkbox-wrap">
                <input type="checkbox" id="sentCheckbox">
                <label for="sentCheckbox">I have sent the exact amount to this address</label>
            </div>
            <form method="GET" action="{{ setRoute("user.crypto.deposit.confirm", ["coin_key" => $coinKey, "amount" => $amount]) }}">
                <button type="submit" class="cd-confirm-btn" id="madePaymentBtn" disabled>
                    I&rsquo;ve Made Payment &rarr;
                </button>
            </form>
        </div>
    </div>

    <div class="cd-toast" id="toast">Copied!</div>
</div>

@push("script")
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var addr = '{{ $coin["address"] }}';
    new QRCode(document.getElementById("qrcode"), {
        text: addr,
        width: 240,
        height: 240,
        colorDark: '#111827',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });
});

function copyAddress() {
    var addr = document.getElementById("walletAddress");
    var text = addr.textContent.trim();

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() {
            showToast();
        });
    } else {
        var textarea = document.createElement("textarea");
        textarea.value = text;
        textarea.style.position = "fixed";
        textarea.style.opacity = "0";
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
        showToast();
    }
}

function showToast() {
    var toast = document.getElementById("toast");
    toast.classList.add("show");
    setTimeout(function() {
        toast.classList.remove("show");
    }, 2000);
}

document.addEventListener("DOMContentLoaded", function() {
    var checkbox = document.getElementById("sentCheckbox");
    var btn = document.getElementById("madePaymentBtn");
    checkbox.addEventListener("change", function() {
        btn.disabled = !this.checked;
    });
});
</script>
@endpush
@endsection
