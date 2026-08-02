@extends('user.layouts.rise-master')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0" style="color:#F1F5F9;font-weight:700;">{{ __("Notifications") }}</h4>
        @if ($unreadCount > 0)
        <form method="POST" action="{{ route('user.notifications.readAll') }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-sm" style="background:rgba(59,130,246,0.15);color:#60A5FA;border:none;border-radius:8px;padding:6px 16px;">
                {{ __("Mark All as Read") }}
            </button>
        </form>
        @endif
    </div>

    @php
        $typeLabels = [
            "ADD-MONEY" => "Deposit", "MONEY-OUT" => "Withdrawal", "WITHDRAW" => "Withdrawal",
            "BONUS" => "Referral Bonus", "COMMISSION" => "Commission",
            "OWN-BANK-TRANSFER" => "Bank Transfer", "OTHER-BANK-TRANSFER" => "Bank Transfer",
            "TRANSFER-MONEY" => "Transfer", "MONEY-EXCHANGE" => "Currency Exchange",
            "ADD-SUBTRACT-BALANCE" => "Adjustment", "MAKE-PAYMENT" => "Payment",
            "CAPITAL-RETURN" => "Capital Return", "VIRTUAL-CARD" => "Virtual Card",
            "MOBILE-WALLET-TRANSFER" => "Mobile Wallet", "Salary Disbursement" => "Salary",
            "FUND-RECEIVED" => "Money Received", "SECURITY" => "Security Alert",
        ];
        $creditTypes = ["ADD-MONEY","BONUS","COMMISSION","CAPITAL-RETURN","Salary Disbursement","FUND-RECEIVED"];
    @endphp

    @if ($notifications->count() > 0)
        <div class="list-group">
            @foreach ($notifications as $n)
                @php
                    $msg  = is_object($n->message) ? $n->message : (object)[];
                    $type = $n->type ?? "GENERAL";
                    $isCredit = in_array($type, $creditTypes);
                    $dotClass = $isCredit ? "credit" : ($type === "BONUS" ? "bonus" : "debit");
                @endphp
                <a href="{{ route('user.notifications.show', $n->id) }}"
                   class="list-group-item list-group-item-action d-flex align-items-center gap-3"
                   style="background:rgba(30,41,59,0.5);border:1px solid rgba(148,163,184,0.1);border-radius:12px;margin-bottom:8px;padding:14px 18px;text-decoration:none;{{ !$n->is_read ? 'border-left:3px solid #3B82F6;' : '' }}">
                    <span class="notif-dot {{ $dotClass }}" style="flex-shrink:0;width:10px;height:10px;border-radius:50%;display:inline-block;
                        @if($dotClass == 'credit') background:#3B82F6;
                        @elseif($dotClass == 'bonus') background:#A855F7;
                        @else background:#EF4444; @endif
                    "></span>
                    <div class="flex-grow-1 min-width-0">
                        <div style="font-size:14px;font-weight:600;color:#F1F5F9;">
                            {{ $msg->title ?? ($typeLabels[$type] ?? 'Notification') }}
                            @if (!$n->is_read)
                                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#3B82F6;margin-left:6px;vertical-align:middle;"></span>
                            @endif
                        </div>
                        <div style="font-size:12px;color:#64748B;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $msg->message ?? ($msg->title ?? '') }}
                        </div>
                        <div style="font-size:11px;color:#475569;margin-top:4px;">
                            {{ $n->created_at ? $n->created_at->format('M d, Y h:i A') : '' }}
                        </div>
                    </div>
                    @if (isset($msg->amount) && floatval($msg->amount) > 0)
                        <span style="font-size:14px;font-weight:700;flex-shrink:0;{{ $isCredit ? 'color:#3B82F6;' : 'color:#EF4444;' }}">
                            {{ $isCredit ? '+' : '-' }}${{ number_format(floatval($msg->amount), 2) }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <div style="text-align:center;padding:60px 20px;color:#64748B;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:16px;">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <p style="font-size:15px;">{{ __("No notifications yet") }}</p>
        </div>
    @endif
</div>
@endsection

@push('style')
<style>
    .list-group-item:hover { background:rgba(59,130,246,0.06) !important; }
    .pagination { justify-content:center; }
    .pagination .page-link { background:rgba(30,41,59,0.5);border-color:rgba(148,163,184,0.15);color:#94A3B8; }
    .pagination .page-item.active .page-link { background:#3B82F6;border-color:#3B82F6;color:#fff; }
</style>
@endpush
