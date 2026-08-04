@extends('user.layouts.rise-master')

@push('css')
<style>
.loan-detail-header {
    padding: 24px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    margin-bottom: 24px;
}
.loan-detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}
.detail-item {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 16px;
}
.detail-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.detail-value {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
    margin-top: 4px;
}
.section-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    margin-bottom: 24px;
    overflow: hidden;
}
.section-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color);
}
.section-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
}
.section-body {
    padding: 24px;
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}
.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 600;
}
.badge-pending_review { background: rgba(245,158,11,0.12); color: var(--warning); }
.badge-approved { background: rgba(59,130,246,0.12); color: var(--accent); }
.badge-funded { background: rgba(34,197,94,0.12); color: var(--success); }
.badge-rejected { background: rgba(239,68,68,0.12); color: var(--danger); }
.badge-active { background: rgba(34,197,94,0.12); color: var(--success); }
.badge-pending { background: rgba(245,158,11,0.12); color: var(--warning); }
.badge-closed { background: rgba(148,163,184,0.12); color: var(--text-secondary); }
.badge-defaulted { background: rgba(239,68,68,0.12); color: var(--danger); }
.badge-due { background: rgba(245,158,11,0.12); color: var(--warning); }
.badge-paid { background: rgba(34,197,94,0.12); color: var(--success); }
.badge-late { background: rgba(239,68,68,0.12); color: var(--danger); }
.payments-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 6px;
}
.payments-table th {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 12px;
    text-align: left;
}
.payments-table td {
    padding: 12px;
    background: var(--bg-primary);
    font-size: 13px;
    color: var(--text-secondary);
}
.payments-table tr td:first-child { border-radius: 10px 0 0 10px; }
.payments-table tr td:last-child { border-radius: 0 10px 10px 0; }
.btn {
    display: inline-flex;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.15s;
    text-decoration: none;
    border: none;
    cursor: pointer;
}
.btn.primary { background: var(--accent); color: #fff; }
.btn.success { background: var(--success); color: #fff; }
.btn.danger { background: var(--danger); color: #fff; }
.btn.outline { background: transparent; border: 1.5px solid var(--border-color); color: var(--text-primary); }
.btn:hover { opacity: 0.85; }
.withdraw-box {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    margin-top: 20px;
}
.fee-note {
    font-size: 12px;
    color: var(--text-secondary);
    margin-top: 8px;
    line-height: 1.5;
}
.status-pill {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 600;
}
</style>
@endpush

@section('content')
<div class="am-header">
    <h1 class="am-header-title">{{ $page_title }}</h1>
    <a href="{{ route('user.loans.index') }}" class="rw-section-link-pill">← {{ __('Back to Loans') }}</a>
</div>

<div class="am-body" style="padding-bottom: 120px;">

    {{-- Header --}}
    <div class="loan-detail-header">
        <div class="loan-detail-grid">
            <div class="detail-item">
                <div class="detail-label">{{ __('Product') }}</div>
                <div class="detail-value">{{ $loan->product->name ?? 'Custom' }}</div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">{{ $loan->loan_type }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Principal') }}</div>
                <div class="detail-value">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->principal, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Funded') }}</div>
                <div class="detail-value">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->funded_amount, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Outstanding') }}</div>
                <div class="detail-value">{{ $loan->currency ?? 'USD' }} {{ number_format($loan->balance_principal, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Term') }}</div>
                <div class="detail-value">{{ $loan->term_months }} months @ {{ $loan->interest_rate }}%</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Approval') }}</div>
                <div class="detail-value"><span class="status-pill badge-{{ $loan->approval_status }}">{{ ucfirst(str_replace('_', ' ', $loan->approval_status)) }}</span></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Loan Status') }}</div>
                <div class="detail-value"><span class="status-pill badge-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">{{ __('Investment Plan') }}</div>
                <div class="detail-value">{{ $loan->investmentPlan->name ?? 'Not assigned' }}</div>
            </div>
        </div>
    </div>

    @if ($loan->approval_status === \App\Models\Loan::APPROVAL_REJECTED)
    <div style="background: rgba(239,68,68,0.1); border: 1px solid var(--danger); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
        <strong style="color: var(--danger);">{{ __('Loan Application Rejected') }}</strong>
        @if ($loan->rejection_reason)
            <p style="margin-top: 8px; font-size: 13px; color: var(--text-secondary);">{{ $loan->rejection_reason }}</p>
        @endif
    </div>
    @endif

    @if ($loan->approval_status === \App\Models\Loan::APPROVAL_PENDING_REVIEW)
    <div style="background: rgba(245,158,11,0.1); border: 1px solid var(--warning); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
        <strong style="color: var(--warning);">{{ __('Loan Application Under Review') }}</strong>
        <p style="margin-top: 8px; font-size: 13px; color: var(--text-secondary);">{{ __('Your loan application is being reviewed by our team. You will be notified once a decision is made.') }}</p>
    </div>
    @endif

    {{-- Loan Wallet --}}
    @if ($loan->wallet && $loan->wallet->count() > 0)
    @php $loanWallet = $loan->wallet->first(); @endphp
    <div class="section-card">
        <div class="section-header">
            <h3 class="section-title">{{ __('Loan Investment Wallet') }}</h3>
        </div>
        <div class="section-body">
            <div class="info-grid">
                <div class="detail-item">
                    <div class="detail-label">{{ __('Available to Invest') }}</div>
                    <div class="detail-value" style="color: var(--accent);">{{ $loan->currency ?? 'USD' }} {{ number_format($loanWallet->balance, 2) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Invested') }}</div>
                    <div class="detail-value">{{ $loan->currency ?? 'USD' }} {{ number_format($loanWallet->invested_amount, 2) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Available Earnings') }}</div>
                    <div class="detail-value" style="color: var(--success);">{{ $loan->currency ?? 'USD' }} {{ number_format($loanWallet->earnings_balance, 2) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('Total Withdrawn Earnings') }}</div>
                    <div class="detail-value">{{ $loan->currency ?? 'USD' }} {{ number_format($loanWallet->withdrawn_earnings, 2) }}</div>
                </div>
            </div>

            {{-- Withdrawal Box --}}
            @if ($loanWallet->earnings_balance > 0)
            <div class="withdraw-box" id="withdraw">
                <h4 style="font-weight: 700; color: var(--text-primary); margin-bottom: 16px;">{{ __('Withdraw Earnings') }}</h4>

                @if (!$loan->canWithdrawEarnings())
                    <div style="background: rgba(245,158,11,0.1); border: 1px solid var(--warning); border-radius: 10px; padding: 14px; margin-bottom: 16px;">
                        <strong style="color: var(--warning); font-size: 13px;">{{ __('Deposit Required') }}</strong>
                        <p style="font-size: 12.5px; color: var(--text-secondary); margin-top: 6px;">{{ __('You must make a qualifying deposit to your EnzoBank wallet before you can withdraw investment earnings. This is a security requirement of our Investment Loan Program.') }}</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('user.loans.withdraw.earnings', $loan->id) }}">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="am-field-group">
                                <label class="am-label">{{ __('Amount') }}</label>
                                <div class="am-input-wrap">
                                    <input type="number" step="0.01" min="1" max="{{ $loanWallet->earnings_balance }}" name="amount" required placeholder="0.00">
                                </div>
                                <div class="fee-note">
                                    {{ __('Available:') }} {{ $loan->currency ?? 'USD' }} {{ number_format($loanWallet->earnings_balance, 2) }}<br>
                                    {{ __('Withdrawal fee:') }} {{ $loan->withdrawal_fee_percent }}%<br>
                                    {{ __('Loan principal cannot be withdrawn - only earnings.') }}
                                </div>
                            </div>
                            <div class="am-field-group">
                                <label class="am-label">{{ __('Receive Into Wallet') }}</label>
                                <div class="am-input-wrap">
                                    <select name="wallet_id" required>
                                        @foreach(\App\Models\UserWallet::with('currency')->where('user_id', auth()->id())->get() as $userWallet)
                                            <option value="{{ $userWallet->id }}">{{ $userWallet->currency->code ?? 'USD' }} - {{ number_format($userWallet->balance, 2) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fee-note">{{ __('Earnings will be credited after the fee is deducted.') }}</div>
                            </div>
                        </div>
                        <button type="submit" class="btn success" style="margin-top: 16px;">{{ __('Withdraw Earnings') }}</button>
                    </form>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Funding History --}}
    @if ($loan->fundings && $loan->fundings->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <h3 class="section-title">{{ __('Funding History') }}</h3>
        </div>
        <div class="section-body">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Fee') }}</th>
                        <th>{{ __('Net Credited') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loan->fundings as $funding)
                    <tr>
                        <td>{{ $funding->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ $loan->currency ?? 'USD' }} {{ number_format($funding->amount, 2) }}</td>
                        <td>{{ $loan->currency ?? 'USD' }} {{ number_format($funding->fee_deducted, 2) }}</td>
                        <td>{{ $loan->currency ?? 'USD' }} {{ number_format($funding->net_amount, 2) }}</td>
                        <td><span class="badge badge-{{ $funding->status }}">{{ ucfirst($funding->status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Payment Schedule --}}
    @if ($loan->payments && $loan->payments->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <h3 class="section-title">{{ __('Repayment Schedule') }}</h3>
        </div>
        <div class="section-body">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>{{ __('Period') }}</th>
                        <th>{{ __('Due Date') }}</th>
                        <th>{{ __('Amount Due') }}</th>
                        <th>{{ __('Principal') }}</th>
                        <th>{{ __('Interest') }}</th>
                        <th>{{ __('Fee') }}</th>
                        <th>{{ __('Amount Paid') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loan->payments->sortBy('period_number') as $payment)
                    <tr>
                        <td>{{ $payment->period_number }}</td>
                        <td>{{ $payment->due_date->format('M d, Y') }}</td>
                        <td>{{ $loan->currency ?? 'USD' }} {{ number_format($payment->amount_due, 2) }}</td>
                        <td>{{ $loan->currency ?? 'USD' }} {{ number_format($payment->principal_due, 2) }}</td>
                        <td>{{ $loan->currency ?? 'USD' }} {{ number_format($payment->interest_due, 2) }}</td>
                        <td>{{ $loan->currency ?? 'USD' }} {{ number_format($payment->fee_due, 2) }}</td>
                        <td>{{ $loan->currency ?? 'USD' }} {{ number_format($payment->amount_paid, 2) }}</td>
                        <td><span class="badge badge-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="loan-actions">
        <a href="{{ route('user.loans.index') }}" class="btn outline">← {{ __('Back to Loans') }}</a>
    </div>

</div>
@endsection