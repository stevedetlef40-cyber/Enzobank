<?php

namespace App\Http\Middleware\User;

use App\Http\Helpers\Response;
use Closure;
use Illuminate\Http\Request;

class PinSetupGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user->pin_status == false) {
            if (auth()->guard('api')->check()) {
                return Response::error(['Please setup your pin first.']);
            } else {
                return redirect()->route('user.setup.pin.index')->with(['error' => ['Please setup your pin first.']]);
            }
        }

        return $next($request);
    }
}
