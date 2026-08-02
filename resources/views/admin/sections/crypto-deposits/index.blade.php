@extends("admin.layouts.master")

@push("css")
<style>
.cd-filter-form { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; margin-bottom: 20px; }
.cd-filter-group { display: flex; flex-direction: column; gap: 4px; }
.cd-filter-group label { font-size: 12px; font-weight: 600; color: #6B7280; }
.cd-filter-group select, .cd-filter-group input { padding: 8px 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 13px; }
.cd-filter-btn { padding: 8px 20px; background: #2563EB; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; }
.cd-badge { font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 999px; text-transform: uppercase; }
.cd-badge-pending { background: #FEF3C7; color: #D97706; }
.cd-badge-confirmed { background: #DBEAFE; color: #3B82F6; }
.cd-badge-rejected { background: #FEE2E2; color: #DC2626; }
</style>
@endpush

@section("content")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $page_title }}</h4>
                <div class="card-header-right">
                    <span class="badge badge-info">{{ $deposits->total() }} total</span>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" class="cd-filter-form">
                    <div class="cd-filter-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request("status") === "pending" ? "selected" : "" }}>Pending</option>
                            <option value="confirmed" {{ request("status") === "confirmed" ? "selected" : "" }}>Confirmed</option>
                            <option value="rejected" {{ request("status") === "rejected" ? "selected" : "" }}>Rejected</option>
                        </select>
                    </div>
                    <div class="cd-filter-group">
                        <label>Coin</label>
                        <select name="coin">
                            <option value="">All Coins</option>
                            @foreach($coins as $key => $coin)
                            <option value="{{ $coin["coin"] }}" {{ request("coin") === $coin["coin"] ? "selected" : "" }}>{{ $coin["coin"] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="cd-filter-group">
                        <label>From</label>
                        <input type="date" name="date_from" value="{{ request("date_from") }}">
                    </div>
                    <div class="cd-filter-group">
                        <label>To</label>
                        <input type="date" name="date_to" value="{{ request("date_to") }}">
                    </div>
                    <button type="submit" class="cd-filter-btn">Filter</button>
                    <a href="{{ setRoute("admin.crypto.deposits.index") }}" class="cd-filter-btn" style="background:#6B7280;">Reset</a>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Coin</th>
                                <th>Network</th>
                                <th>Amount (USD)</th>
                                <th>TX Hash</th>
                                <th>Proof</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($deposits as $deposit)
                            <tr>
                                <td>{{ $deposit->id }}</td>
                                <td>
                                    <a href="{{ setRoute("admin.user.care.edit", $deposit->user_id) }}">
                                        {{ $deposit->user->username ?? "N/A" }}
                                    </a>
                                </td>
                                <td><strong>{{ $deposit->coin_symbol }}</strong></td>
                                <td>{{ $deposit->network }}</td>
                                <td>${{ number_format($deposit->amount_usd, 2) }}</td>
                                <td style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    @if($deposit->tx_hash)
                                        <span title="{{ $deposit->tx_hash }}">{{ substr($deposit->tx_hash, 0, 16) }}...</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($deposit->proof)
                                        <a href="{{ asset("storage/" . $deposit->proof) }}" target="_blank" class="btn btn-sm btn-info">View</a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($deposit->status === "pending")
                                        <span class="cd-badge cd-badge-pending">Pending</span>
                                    @elseif($deposit->status === "confirmed")
                                        <span class="cd-badge cd-badge-confirmed">Confirmed</span>
                                    @else
                                        <span class="cd-badge cd-badge-rejected">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $deposit->created_at->format("M d, Y H:i") }}</td>
                                <td>
                                    <a href="{{ setRoute("admin.crypto.deposits.show", $deposit->id) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">No crypto deposits found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $deposits->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
