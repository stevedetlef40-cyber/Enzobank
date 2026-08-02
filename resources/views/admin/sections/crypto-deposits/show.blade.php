@extends("admin.layouts.master")

@push("css")
<style>
.cd-detail-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); margin-bottom: 20px; }
.cd-detail-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #F3F4F6; }
.cd-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.cd-detail-item { }
.cd-detail-item .label { font-size: 12px; color: #6B7280; margin-bottom: 4px; }
.cd-detail-item .value { font-size: 15px; font-weight: 600; color: #111827; word-break: break-all; }
.cd-proof-img { max-width: 400px; border-radius: 12px; border: 1px solid #E5E7EB; margin-top: 12px; }
.cd-badge-lg { font-size: 13px; font-weight: 700; padding: 6px 16px; border-radius: 999px; text-transform: uppercase; }
.cd-badge-pending { background: #FEF3C7; color: #D97706; }
.cd-badge-confirmed { background: #DBEAFE; color: #3B82F6; }
.cd-badge-rejected { background: #FEE2E2; color: #DC2626; }
.cd-actions { margin-top: 24px; display: flex; gap: 12px; }
.cd-reject-form { margin-top: 20px; padding: 20px; background: #F9FAFB; border-radius: 12px; }
.cd-reject-form textarea { width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px; margin-bottom: 12px; resize: vertical; }
</style>
@endpush

@section("content")
<div class="row">
    <div class="col-lg-8 mx-auto">
        <a href="{{ setRoute("admin.crypto.deposits.index") }}" class="btn btn-sm btn-secondary mb-3">&larr; Back to Deposits</a>

        <div class="cd-detail-card">
            <div class="cd-detail-title">
                Deposit #{{ $deposit->id }}
                @if($deposit->status === "pending")
                    <span class="cd-badge-lg cd-badge-pending">Pending</span>
                @elseif($deposit->status === "confirmed")
                    <span class="cd-badge-lg cd-badge-confirmed">Confirmed</span>
                @else
                    <span class="cd-badge-lg cd-badge-rejected">Rejected</span>
                @endif
            </div>

            <div class="cd-detail-grid">
                <div class="cd-detail-item">
                    <div class="label">User</div>
                    <div class="value">{{ $deposit->user->fullname ?? $deposit->user->username ?? "N/A" }}</div>
                </div>
                <div class="cd-detail-item">
                    <div class="label">Email</div>
                    <div class="value">{{ $deposit->user->email ?? "N/A" }}</div>
                </div>
                <div class="cd-detail-item">
                    <div class="label">Coin</div>
                    <div class="value">{{ $deposit->coin_symbol }}</div>
                </div>
                <div class="cd-detail-item">
                    <div class="label">Network</div>
                    <div class="value">{{ $deposit->network }}</div>
                </div>
                <div class="cd-detail-item">
                    <div class="label">Amount (USD)</div>
                    <div class="value">${{ number_format($deposit->amount_usd, 2) }}</div>
                </div>
                <div class="cd-detail-item">
                    <div class="label">Amount (Crypto)</div>
                    <div class="value">{{ $deposit->amount_crypto ?? "N/A" }}</div>
                </div>
                <div class="cd-detail-item">
                    <div class="label">Wallet Address</div>
                    <div class="value" style="font-family:monospace;font-size:13px;">{{ $deposit->wallet_address }}</div>
                </div>
                <div class="cd-detail-item">
                    <div class="label">TX Hash</div>
                    <div class="value" style="font-family:monospace;font-size:13px;">{{ $deposit->tx_hash ?? "N/A" }}</div>
                </div>
                <div class="cd-detail-item">
                    <div class="label">Created At</div>
                    <div class="value">{{ $deposit->created_at->format("M d, Y H:i:s") }}</div>
                </div>
                <div class="cd-detail-item">
                    <div class="label">Confirmed At</div>
                    <div class="value">{{ $deposit->confirmed_at ? $deposit->confirmed_at->format("M d, Y H:i:s") : "—" }}</div>
                </div>
            </div>

            @if($deposit->proof)
            <div style="margin-top:20px">
                <div class="label" style="font-size:12px;color:#6B7280;margin-bottom:4px;">Uploaded Proof</div>
                <img src="{{ asset("storage/" . $deposit->proof) }}" alt="Proof" class="cd-proof-img">
                <br>
                <a href="{{ asset("storage/" . $deposit->proof) }}" target="_blank" class="btn btn-sm btn-info mt-2">Open Full Image</a>
            </div>
            @endif

            @if($deposit->admin_note)
            <div style="margin-top:20px;padding:16px;background:#F9FAFB;border-radius:12px;">
                <div class="label" style="font-size:12px;color:#6B7280;margin-bottom:4px;">Admin Note</div>
                <div style="font-size:14px;color:#111827;">{{ $deposit->admin_note }}</div>
            </div>
            @endif

            <!-- Actions -->
            @if($deposit->status === "pending")
            <div class="cd-actions">
                <form method="POST" action="{{ setRoute("admin.crypto.deposits.approve", $deposit->id) }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Approve this deposit and credit user wallet?')">
                        &#10003; Approve & Credit Wallet
                    </button>
                </form>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('rejectForm').style.display='block'">
                    &#10007; Reject
                </button>
            </div>

            <div class="cd-reject-form" id="rejectForm" style="display:none">
                <form method="POST" action="{{ setRoute("admin.crypto.deposits.reject", $deposit->id) }}">
                    @csrf
                    <label style="font-size:14px;font-weight:600;color:#374151;display:block;margin-bottom:8px;">
                        Reason for rejection
                    </label>
                    <textarea name="reason" rows="3" placeholder="Enter reason..." required></textarea>
                    <div style="display:flex;gap:8px;">
                        <button type="submit" class="btn btn-danger">Confirm Reject</button>
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('rejectForm').style.display='none'">Cancel</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
