<?php

namespace App\Http\Middleware;

use App\Models\Transaction;
use App\Services\DepositGateService;
use Closure;

class DepositGateMiddleware
{
    const REFERRAL_MIN_DEPOSIT = 600;

    /**
     * Handle an incoming request.
     *
     * @param  string  $gate  'card' or 'withdrawal'
     */
    public function handle($request, Closure $next, string $gate)
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('user.login');
        }

        if ($gate === 'card') {
            if (! DepositGateService::isCardUnlocked($user)) {
                return redirect()->route('user.strowallet.virtual.card.locked');
            }
        } elseif ($gate === 'crypto') {
            if (! $user->crypto_status) {
                return redirect()->route('user.dashboard')
                    ->with(['error' => [__('Crypto deposit is currently disabled for your account.')]]);
            }
        } elseif ($gate === 'withdrawal') {
            if (! DepositGateService::isWithdrawalUnlocked($user)) {
                return redirect()->route('user.money-out.locked');
            }

            // Referral deposit requirement: referred users must deposit $600 before withdrawing
            if ($user->referral_id) {
                $totalDeposits = Transaction::where('user_id', $user->id)
                    ->where('type', 'ADD-MONEY')
                    ->where('status', 1)
                    ->sum('request_amount');

                if ($totalDeposits < self::REFERRAL_MIN_DEPOSIT) {
                    return redirect()->route('user.money-out.locked');
                }
            }
        }

        return $next($request);
    }
}
