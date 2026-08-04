<?php

namespace App\Models;

use App\Constants\GlobalConst;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\Admin;
use App\Models\Admin\PaymentGateway;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'type' => 'string',
        'trx_id' => 'string',
        'salary_disbursement_id' => 'string',
        'user_type' => 'string',
        'user_id' => 'integer',
        'wallet_id' => 'integer',
        'admin_id' => 'integer',
        'payment_gateway_currency_id' => 'integer',
        'request_amount' => 'decimal:8',
        'request_currency' => 'string',
        'exchange_rate' => 'decimal:8',
        'percent_charge' => 'decimal:8',
        'fixed_charge' => 'decimal:8',
        'total_charge' => 'decimal:8',
        'total_payable' => 'decimal:8',
        'receive_amount' => 'decimal:8',
        'receiver_type' => 'string',
        'receiver_id' => 'integer',
        'available_balance' => 'decimal:8',
        'payment_currency' => 'string',
        'remark' => 'string',
        'status' => 'integer',
    ];

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function getRouteKeyName()
    {
        return 'trx_id';
    }

    public function user_wallet()
    {
        return $this->belongsTo(UserWallet::class, 'wallet_id');
    }

    public function receiver_wallet()
    {
        return $this->belongsTo(UserWallet::class, 'receiver_id');
    }

    public function payment_gateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function getCreatorAttribute()
    {
        if ($this->user_type == GlobalConst::USER) {
            return $this->user;
        } elseif ($this->user_type == GlobalConst::ADMIN) {
            return $this->admin;
        }
    }

    public function receiver_info()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function getReceiverAttribute()
    {
        if ($this->receiver_type == GlobalConst::USER) {
            return $this->receiver_info;
        }
    }

    public function getCreatorWalletAttribute()
    {
        if ($this->user_type == GlobalConst::USER) {
            return $this->user_wallet;
        } elseif ($this->user_type == GlobalConst::ADMIN) { //  if user type ADMIN wallet_id is user wallet id. Because admin has no wallet.
            return $this->user_wallet;
        }
    }

    public function getDetailsAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, false);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, false);
            }

            return is_object($decoded) ? $decoded : (is_array($decoded) ? (object) $decoded : (object) []);
        }
        if (is_array($value)) {
            return (object) $value;
        }

        return is_object($value) ? $value : (object) [];
    }

    public function getStringStatusAttribute()
    {
        $status = $this->status;
        $data = [
            'class' => '',
            'value' => '',
        ];
        if ($status == PaymentGatewayConst::STATUSSUCCESS) {
            $data = [
                'class' => 'badge badge--success',
                'value' => 'Success',
            ];
        } elseif ($status == PaymentGatewayConst::STATUSPENDING) {
            $data = [
                'class' => 'badge badge--warning',
                'value' => 'Pending',
            ];
        } elseif ($status == PaymentGatewayConst::STATUSHOLD) {
            $data = [
                'class' => 'badge badge--warning',
                'value' => 'Hold',
            ];
        } elseif ($status == PaymentGatewayConst::STATUSREJECTED) {
            $data = [
                'class' => 'badge badge--danger',
                'value' => 'Rejected',
            ];
        } elseif ($status == PaymentGatewayConst::STATUSWAITING) {
            $data = [
                'class' => 'badge badge--danger',
                'value' => 'Waiting',
            ];
        }

        return (object) $data;
    }

    public function scopeMoneyOut($query)
    {
        return $query->where('type', PaymentGatewayConst::TYPEMONEYOUT);
    }

    public function gateway_currency()
    {
        return $this->belongsTo(PaymentGatewayCurrency::class, 'payment_gateway_currency_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', PaymentGatewayConst::STATUSPENDING);
    }

    public function scopeComplete($query)
    {
        return $query->where('status', PaymentGatewayConst::STATUSSUCCESS);
    }

    public function scopeReject($query)
    {
        return $query->where('status', PaymentGatewayConst::STATUSREJECTED);
    }

    public function scopeAddMoney($query)
    {
        return $query->where('type', PaymentGatewayConst::TYPEADDMONEY);
    }

    public function scopeFundTransfer($query)
    {
        return $query->whereIn('type', [PaymentGatewayConst::TYPE_MOBILE_WALLET_TRANSFER,
            PaymentGatewayConst::TYPE_OTHER_BANK_TRANSFER,
            PaymentGatewayConst::TYPE_OWN_BANK_TRANSFER,
        ]);
    }

    public function scopeChartData($query)
    {
        return $query->select([
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
        ])
            ->groupBy('date')
            ->pluck('total');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
    }

    public function scopeThisYear($query)
    {
        return $query->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()]);
    }

    public function scopeYearChartData($query)
    {
        return $query->select([
            DB::raw('sum(total_charge) as total, YEAR(created_at) as year, MONTH(created_at) as month'),
        ])->groupBy('year', 'month')->pluck('total', 'month');
    }

    public function scopeAuth($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function scopeTrxAuth($query)
    {
        return $query->where('user_id', auth()->user()->id)->orWhere('receiver_id', auth()->user()->id);
    }

    public function scopeOwnBankTransfer($query)
    {
        return $query->where('type', PaymentGatewayConst::TYPE_OWN_BANK_TRANSFER);
    }

    public function scopeOtherBankTransfer($query)
    {
        return $query->where('type', PaymentGatewayConst::TYPE_OTHER_BANK_TRANSFER);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now()->today());
    }

    public function scopeActive($query)
    {
        return $query->where('status', PaymentGatewayConst::ACTIVE);
    }

    public function scopeSearch($query, $data)
    {
        return $query->where('trx_id', 'like', '%'.$data.'%');
    }

    public function getFundReceiverInfoAttribute()
    {
        $details = $this->details;
        $receiverName = $details->receiver_name ?? $details->account_holder_name ?? $details->description ?? $details->sender ?? 'N/A';
        $receiverNumber = $details->receiver_account ?? $details->account_number ?? $details->card_number ?? 'N/A';

        $data = [
            'receiver_number_title' => 'Account Holder Number',
            'receiver_number_value' => $receiverNumber,
            'receiver_holder_title' => 'Account Holder Name',
            'receiver_holder_value' => $receiverName,
        ];

        return (object) $data;
    }

    public function getOtherBankNameAttribute()
    {
        $details = $this->details;

        return $details->receiver_bank ?? $details->bank_name ?? 'N/A';
    }

    public function getUserTrxTypeAttribute()
    {
        // Use the attribute field directly (SEND/RECEIVED)
        $attr = $this->attribute ?? '';
        if (strtoupper($attr) === 'RECEIVED') {
            return GlobalConst::RECEIVED;
        }

        return GlobalConst::SEND;
    }
}
