@extends('admin.layouts.master')

@push('css')
@endpush

@section('page-title')
    @include('admin.components.page-title', ['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => setRoute('admin.dashboard'),
            ],
        ],
        'active' => __('User Care'),
    ])
@endsection

@section('content')
    <div class="table-area">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">{{ __("All Users") }}</h5>
                <div class="table-btn-area">
                    @include('admin.components.search-input',[
                        'name'  => 'user_search',
                    ])
                </div>
            </div>
            <div class="table-responsive">
                @include('admin.components.data-table.user-table',compact('users'))
            </div>
        </div>
        {{ get_paginate($users) }}
    </div>
@endsection

{{-- User Detail Modal --}}
<div id="user-detail-modal" class="mfp-hide large">
    <div class="modal-header-custom">
        <h5 class="modal-title-custom">{{ __('User Details') }}</h5>
        <button type="button" class="modal-close-btn" id="modalCloseBtn">&times;</button>
    </div>
    <div class="modal-body-custom" id="userModalBody">
        <div class="text-center py-4">{{ __('Loading...') }}</div>
    </div>
</div>

<style>
/* Mobile User Cards */
.user-cards-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 8px 0;
}
.user-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #e5e7eb;
    gap: 12px;
}
.user-card-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    flex: 1;
}
.user-card-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.user-card-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
}
.user-card-name {
    font-size: 15px;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.user-card-email {
    font-size: 12px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.user-card-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}
.user-card-right .btn--base {
    padding: 6px 12px;
    font-size: 13px;
    min-height: unset;
    min-width: unset;
}
.user-card-right .btn--base i {
    font-size: 16px;
}

/* User Detail Modal Styles */
#user-detail-modal {
    max-width: 520px;
    padding: 0;
    border-radius: 16px;
    background: #fff;
    overflow: hidden;
}
.modal-header-custom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid #e5e7eb;
    background: #f8fafc;
}
.modal-title-custom {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.modal-close-btn {
    background: none;
    border: none;
    font-size: 24px;
    color: #94a3b8;
    cursor: pointer;
    padding: 0 4px;
    line-height: 1;
}
.modal-close-btn:hover {
    color: #475569;
}
.modal-body-custom {
    padding: 24px;
    max-height: 70vh;
    overflow-y: auto;
}

/* User Profile Section in Modal */
.user-modal-profile {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
}
.user-modal-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e2e8f0;
}
.user-modal-name {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 2px;
}
.user-modal-email {
    font-size: 13px;
    color: #64748b;
}

/* Detail Rows */
.user-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 20px;
}
.user-detail-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.user-detail-label {
    font-size: 11px;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.user-detail-value {
    font-size: 14px;
    font-weight: 500;
    color: #1e293b;
}
.user-detail-value.success { color: #3B82F6; }
.user-detail-value.danger { color: #dc2626; }
.user-detail-value.warning { color: #d97706; }

/* Wallet Balances */
.wallet-balances {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.wallet-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}

/* Action Buttons in Modal */
.user-modal-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
}
.user-modal-actions .btn {
    font-size: 13px;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.user-modal-actions .btn-sm-action {
    font-size: 12px;
    padding: 6px 14px;
}
.btn-amber { background: #f59e0b; color: #fff; }
.btn-amber:hover { background: #d97706; }
.btn-green { background: #3B82F6; color: #fff; }
.btn-green:hover { background: #1D4ED8; }
.btn-blue { background: #3b82f6; color: #fff; }
.btn-blue:hover { background: #2563eb; }
.btn-red { background: #dc2626; color: #fff; }
.btn-red:hover { background: #b91c1c; }
.btn-grey { background: #e5e7eb; color: #475569; }
.btn-grey:hover { background: #d1d5db; }

@media (max-width: 480px) {
    .user-detail-grid {
        grid-template-columns: 1fr;
    }
    .user-modal-actions .btn {
        flex: 1;
        justify-content: center;
        min-width: 0;
    }
}
</style>

@push('script')
    <script>
        itemSearch($("input[name=user_search]"),$(".user-search-table"),"{{ setRoute('admin.users.search') }}");

        // Open user detail modal via Magnific Popup
        function openUserModal(userData, wallets) {
            var isActive = userData.status == 1;
            var isBanned = userData.status == 0;
            var statusBadge = isActive
                ? '<span style="display:inline-block;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;background:#dbeafe;color:#3B82F6">Active</span>'
                : '<span style="display:inline-block;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;background:#fee2e2;color:#dc2626">Suspended</span>';

            var walletHtml = '';
            if (wallets && Object.keys(wallets).length > 0) {
                walletHtml = '<div class="wallet-balances">';
                for (var k in wallets) {
                    var w = wallets[k];
                    walletHtml += '<span class="wallet-badge">' + w.currency_code + ' ' + parseFloat(w.balance).toFixed(2) + '</span>';
                }
                walletHtml += '</div>';
            } else {
                walletHtml = '<div class="wallet-balances"><span class="wallet-badge">No wallets</span></div>';
            }

            var depositStatus = userData.has_qualifying_deposit
                ? '<span class="user-detail-value success">Yes</span>'
                : '<span class="user-detail-value danger">No</span>';
            var cardStatus = userData.card_unlocked
                ? '<span class="user-detail-value success">Yes</span>'
                : '<span class="user-detail-value danger">No</span>';
            var withdrawStatus = userData.withdrawal_unlocked
                ? '<span class="user-detail-value success">Yes</span>'
                : '<span class="user-detail-value danger">No</span>';

            var html = '';
            html += '<div class="user-modal-profile">';
            html += '  <img src="' + userData.avatar + '" alt="" class="user-modal-avatar">';
            html += '  <div>';
            html += '    <div class="user-modal-name">' + userData.firstname + ' ' + userData.lastname + ' (' + userData.username + ')</div>';
            html += '    <div class="user-modal-email">' + userData.email + '</div>';
            html += '  </div>';
            html += '</div>';

            html += '<div class="user-detail-grid">';
            html += '  <div class="user-detail-item"><span class="user-detail-label">Account No</span><span class="user-detail-value">' + userData.account_no + '</span></div>';
            html += '  <div class="user-detail-item"><span class="user-detail-label">Phone</span><span class="user-detail-value">' + userData.full_mobile + '</span></div>';
            html += '  <div class="user-detail-item"><span class="user-detail-label">Status</span><span class="user-detail-value">' + statusBadge + '</span></div>';
            html += '  <div class="user-detail-item"><span class="user-detail-label">Registered</span><span class="user-detail-value">' + userData.created_at + '</span></div>';
            html += '  <div class="user-detail-item"><span class="user-detail-label">Qualifying Deposit</span>' + depositStatus + '</div>';
            html += '  <div class="user-detail-item"><span class="user-detail-label">Card Unlocked</span>' + cardStatus + '</div>';
            html += '  <div class="user-detail-item"><span class="user-detail-label">Withdrawal Unlocked</span>' + withdrawStatus + '</div>';
            html += '</div>';

            html += walletHtml;

            var username = encodeURIComponent(userData.username);
            var detailsUrl = "{{ route('admin.users.details', 'PLACEHOLDER') }}".replace('PLACEHOLDER', username);
            var loginUrl = "{{ route('admin.users.login.as.member', 'PLACEHOLDER') }}".replace('PLACEHOLDER', username);
            html += '<div class="user-modal-actions">';
            if (!isActive && !isBanned) {
                html += '  <a href="{{ route('admin.users.details.update', '') }}/' + username + '?action=active" class="btn btn-green btn-sm-action"><i class="las la-check-circle"></i> Activate</a>';
            }
            if (isActive) {
                html += '  <a href="{{ route('admin.users.details.update', '') }}/' + username + '?action=suspend" class="btn btn-amber btn-sm-action"><i class="las la-ban"></i> Suspend</a>';
            }
            html += '  <button type="button" class="btn btn-blue btn-sm-action" onclick="alert(\'Unlock card for ' + userData.username + '\')"><i class="las la-credit-card"></i> Unlock Card</button>';
            html += '  <button type="button" class="btn btn-blue btn-sm-action" onclick="alert(\'Unlock withdrawal for ' + userData.username + '\')"><i class="las la-unlock"></i> Unlock Withdrawal</button>';
            html += '  <a href="{{ route('admin.users.wallet.balance.update', '') }}/' + username + '" class="btn btn-blue btn-sm-action"><i class="las la-plus-circle"></i> Credit</a>';
            html += '  <a href="{{ route('admin.users.wallet.balance.update', '') }}/' + username + '" class="btn btn-red btn-sm-action"><i class="las la-minus-circle"></i> Debit</a>';
            html += '  <a href="{{ route('admin.users.details.update', '') }}/' + username + '?action=delete" class="btn btn-red btn-sm-action" onclick="return confirm(\'Delete this user?\')"><i class="las la-trash"></i> Delete</a>';
            html += '  <a href="' + detailsUrl + '" class="btn btn-grey btn-sm-action"><i class="las la-eye"></i> View Details</a>';
            html += '  <form method="POST" action="' + loginUrl + '" style="display:inline-block;margin:0 2px;">@csrf<button type="submit" class="btn btn-blue btn-sm-action"><i class="las la-sign-in-alt"></i> Login as User</button></form>';
            html += '  <button type="button" class="btn btn-grey btn-sm-action" id="modalCloseBtn2"><i class="las la-times"></i> Close</button>';
            html += '</div>';

            document.getElementById('userModalBody').innerHTML = html;
        }

        // Attach click handlers to all user-detail-btn
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.user-detail-btn');
            if (btn) {
                e.preventDefault();
                try {
                    var userData = JSON.parse(btn.getAttribute('data-user'));
                    var wallets = JSON.parse(btn.getAttribute('data-wallets') || '{}');
                    openUserModal(userData, wallets);
                    $.magnificPopup.open({
                        items: { src: '#user-detail-modal' },
                        type: 'inline',
                        mainClass: 'mfp-fade'
                    });
                } catch(err) {
                    console.error('User detail error:', err);
                    alert('Could not load user details.');
                }
            }
        });

        // Close modal buttons (live handler for dynamically created buttons)
        $(document).on('click', '#modalCloseBtn, #modalCloseBtn2', function() {
            $.magnificPopup.close();
        });
    </script>
@endpush
