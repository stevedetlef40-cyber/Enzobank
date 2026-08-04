<?php

namespace App\Http\Middleware\Api\V1\User;

use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Providers\Admin\BasicSettingsProvider;
use Closure;
use Illuminate\Http\Request;

class KycVerificationGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $basic_settings = BasicSettingsProvider::get();
        if ($basic_settings->kyc_verification) {
            $user = auth()->user();
            if ($user->kyc_verified != GlobalConst::APPROVED) {
                $smg = 'Please verify your KYC information before any withdrawal action';
                if ($user->kyc_verified == GlobalConst::PENDING) {
                    $smg = 'Your KYC information is pending. Please wait for admin confirmation.';
                }

                return Response::error([$smg], null, 400);
            }
        }

        return $next($request);
    }
}
