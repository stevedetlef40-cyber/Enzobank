@extends('user.layouts.rise-master')

@push('css')
<style>
/* ── Receipt Page Styles ── */
.rcpt-hero { text-align:center; padding:32px 16px 16px; }
.rcpt-hero-icon {
    width:72px; height:72px; border-radius:50%; margin:0 auto 14px;
    display:flex; align-items:center; justify-content:center;
    background:var(--success-bg);
    animation: rcptPop .5s cubic-bezier(.2,.8,.3,1.2) both;
}
@keyframes rcptPop { from { opacity:0; transform:scale(.8); } to { opacity:1; transform:scale(1); } }
.rcpt-hero-icon svg { width:32px; height:32px; color:var(--success); }
.rcpt-hero-title { font-size:20px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
.rcpt-hero-sub { font-size:13px; color:var(--text-secondary); }

/* Action buttons row */
.rcpt-actions { display:flex; gap:10px; padding:0 16px 20px; overflow-x:auto; }
.rcpt-actions::-webkit-scrollbar { display:none; }
.rcpt-action-btn {
    flex-shrink:0; display:flex; align-items:center; gap:8px;
    padding:12px 18px; border-radius:12px; border:1.5px solid var(--border-color);
    background:var(--bg-card); color:var(--text-primary); font-size:13px; font-weight:600;
    cursor:pointer; transition:all .2s; text-decoration:none; white-space:nowrap;
}
.rcpt-action-btn:hover { border-color:var(--accent); color:var(--accent); background:var(--accent-soft); }
.rcpt-action-btn svg { width:18px; height:18px; flex-shrink:0; }
.rcpt-action-btn.primary { background:var(--accent); border-color:var(--accent); color:var(--text-on-accent); }
.rcpt-action-btn.primary:hover { filter:brightness(1.1); }
.rcpt-action-btn.whatsapp { background:#3B82F6; border-color:#3B82F6; color:#fff; }
.rcpt-action-btn.whatsapp:hover { filter:brightness(1.1); }

/* Receipt Card */
#receiptArea {
    margin:0 16px 20px; background:var(--bg-card); border:1px solid var(--border-color);
    border-radius:16px; overflow:hidden;
}
.rcpt-header {
    padding:24px 20px 16px; text-align:center;
    border-bottom:1px solid var(--border-color);
}
.rcpt-header img { max-width:120px; margin-bottom:8px; }
.rcpt-header h3 { font-size:16px; font-weight:700; color:var(--text-primary); margin:0; }
.rcpt-summary {
    margin:16px 20px; padding:18px; border-radius:12px; text-align:center;
    background:var(--success-bg); border:1px solid var(--success-bg);
}
.rcpt-summary-amount { font-size:28px; font-weight:800; color:var(--success); }
.rcpt-summary-status { margin:4px 0; }
.rcpt-summary-status span { display:inline-block; padding:3px 12px; border-radius:30px; font-size:12px; font-weight:600; }
.rcpt-summary-meta { display:flex; gap:16px; justify-content:center; flex-wrap:wrap; font-size:12px; color:var(--text-muted); margin-top:6px; }
.rcpt-summary-meta svg { width:14px; height:14px; margin-right:4px; vertical-align:middle; }
.rcpt-body { padding:4px 20px 20px; }
.rcpt-info { display:flex; justify-content:space-between; margin-bottom:12px; }
.rcpt-info-label { font-size:11px; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px; }
.rcpt-info-value { font-size:13px; font-weight:600; color:var(--text-primary); text-align:right; }
.rcpt-table { width:100%; border-collapse:collapse; }
.rcpt-table td { padding:10px 0; border-bottom:1px solid var(--border-color); font-size:13px; }
.rcpt-table td:last-child { text-align:right; font-weight:600; color:var(--text-primary); }
.rcpt-table td:first-child { color:var(--text-secondary); }
.rcpt-table tr:last-child td { border-bottom:none; }
.rcpt-total td { padding-top:14px !important; font-size:15px !important; border-top:2px solid var(--border-color) !important; }
.rcpt-total td:last-child { color:var(--accent) !important; font-size:16px !important; }
.rcpt-footer { padding:16px 20px; text-align:center; border-top:1px solid var(--border-color); font-size:12px; color:var(--text-muted); }

/* Toast notification */
.rcpt-toast {
    position:fixed; bottom:100px; left:50%; transform:translateX(-50%) translateY(10px);
    background:var(--bg-card); border:1px solid var(--border-color); border-radius:12px;
    padding:12px 20px; display:flex; align-items:center; gap:10px;
    font-size:13px; font-weight:500; color:var(--text-primary);
    box-shadow:var(--shadow-strong); z-index:999;
    opacity:0; transition:all .3s ease; pointer-events:none;
}
.rcpt-toast.show { opacity:1; transform:translateX(-50%) translateY(0); pointer-events:auto; }
.rcpt-toast svg { width:18px; height:18px; color:var(--success); flex-shrink:0; }
</style>
@endpush

@section('content')
@php $basic = \App\Providers\Admin\BasicSettingsProvider::get(); @endphp

<div class="rcpt-hero">
    <div class="rcpt-hero-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div class="rcpt-hero-title">{{ __('Transfer Completed') }}</div>
    <div class="rcpt-hero-sub">{{ __('Your money has been sent successfully.') }}</div>
</div>

<div class="rcpt-actions">
    <a href="{{ setRoute('user.fund-transfer.index') }}" class="rcpt-action-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
        {{ __('New Transfer') }}
    </a>
    <button class="rcpt-action-btn" onclick="downloadPDF()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        {{ __('PDF') }}
    </button>
    <button class="rcpt-action-btn" onclick="downloadImage()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        {{ __('Image') }}
    </button>
    <button class="rcpt-action-btn whatsapp" onclick="shareWhatsApp()">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
        {{ __('WhatsApp') }}
    </button>
</div>

{{-- Receipt --}}
@if($transaction)
<div id="receiptArea">
    <div class="rcpt-header">
        <img src="{{ get_logo() }}" alt="EnzoBank">
        <h3>{{ __('Transfer Receipt') }}</h3>
    </div>

    <div class="rcpt-summary">
        <div class="rcpt-summary-amount">{{ get_amount($transaction->request_amount, $transaction->request_currency) }}</div>
        <div class="rcpt-summary-status"><span class="{{ $transaction->stringStatus->class }}">{{ $transaction->stringStatus->value }}</span></div>
        <div class="rcpt-summary-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> {{ dateFormat('d F Y', $transaction->created_at) }}</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg> #{{ $transaction->trx_id }}</span>
        </div>
    </div>

    <div class="rcpt-body">
        <div class="rcpt-info">
            <span class="rcpt-info-label">{{ __('Account Holder') }}</span>
            <span class="rcpt-info-value">{{ $transaction->user->fullname ?? $transaction->user->username ?? 'N/A' }}</span>
        </div>
        <div class="rcpt-info">
            <span class="rcpt-info-label">{{ __('Account Number') }}</span>
            <span class="rcpt-info-value">{{ $transaction->user->account_no ?? 'N/A' }}</span>
        </div>

        <table class="rcpt-table">
            <tbody>
                <tr>
                    <td>{{ __('Transaction ID') }}</td>
                    <td>{{ $transaction->trx_id }}</td>
                </tr>
                @if($transaction->fundReceiverInfo)
                <tr>
                    <td>{{ $transaction->fundReceiverInfo->receiver_holder_title }}</td>
                    <td>{{ $transaction->fundReceiverInfo->receiver_holder_value }}</td>
                </tr>
                <tr>
                    <td>{{ $transaction->fundReceiverInfo->receiver_number_title }}</td>
                    <td>{{ $transaction->fundReceiverInfo->receiver_number_value }}</td>
                </tr>
                @endif
                <tr>
                    <td>{{ __('Amount Sent') }}</td>
                    <td>{{ get_amount($transaction->request_amount, $transaction->request_currency) }}</td>
                </tr>
                <tr>
                    <td>{{ __('Fees & Charges') }}</td>
                    <td>{{ get_amount($transaction->total_charge, $transaction->payment_currency) }}</td>
                </tr>
                @if($transaction->remark)
                <tr>
                    <td>{{ __('Remark') }}</td>
                    <td>{{ $transaction->remark }}</td>
                </tr>
                @endif
                <tr>
                    <td>{{ __('Status') }}</td>
                    <td><span class="{{ $transaction->stringStatus->class }}">{{ $transaction->stringStatus->value }}</span></td>
                </tr>
                <tr class="rcpt-total">
                    <td>{{ __('Total Deducted') }}</td>
                    <td>{{ get_amount($transaction->total_payable, $transaction->request_currency) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="rcpt-footer">
        {{ __('For inquiries, contact us at') }} {{ $basic->site_name ?? 'EnzoBank' }}<br>
        {{ __('Thank you for banking with us.') }}
    </div>
</div>
@endif

<div id="rcptToast" class="rcpt-toast">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    <span id="rcptToastMsg">{{ __('Receipt saved') }}</span>
</div>

@push('script')
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
function downloadPDF() {
    window.location.href = '{{ route("user.fund-transfer.pdf.download", $trx_id) }}';
}

function downloadImage() {
    var area = document.getElementById('receiptArea');
    if (!area) return;
    showToast('Generating image...');
    html2canvas(area, {
        scale: 2,
        backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--bg-card').trim() || '#ffffff',
        useCORS: true,
        logging: false
    }).then(function(canvas) {
        var link = document.createElement('a');
        link.download = 'EnzoBank_Receipt_{{ $trx_id }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        showToast('Receipt image downloaded');
    }).catch(function(err) {
        showToast('Could not generate image. Try PDF instead.');
    });
}

function shareWhatsApp() {
    var text = '🏦 *EnzoBank Transfer Receipt*%0A';
    text += '📄 ID: {{ $transaction->trx_id }}%0A';
    text += '💰 Amount: {{ get_amount($transaction->request_amount, $transaction->request_currency) }}%0A';
    text += '📅 Date: {{ dateFormat("d F Y", $transaction->created_at) }}%0A';
    @if($transaction->fundReceiverInfo)
    text += '👤 {{ $transaction->fundReceiverInfo->receiver_holder_title }}: {{ $transaction->fundReceiverInfo->receiver_holder_value }}%0A';
    @endif
    text += '✅ Status: {{ $transaction->stringStatus->value }}';
    window.open('https://wa.me/?text=' + text, '_blank');
}

function showToast(msg) {
    var toast = document.getElementById('rcptToast');
    var toastMsg = document.getElementById('rcptToastMsg');
    if (!toast || !toastMsg) return;
    toastMsg.textContent = msg;
    toast.classList.add('show');
    clearTimeout(window._rcptToastTimer);
    window._rcptToastTimer = setTimeout(function() { toast.classList.remove('show'); }, 2500);
}
</script>
@endpush
@endsection