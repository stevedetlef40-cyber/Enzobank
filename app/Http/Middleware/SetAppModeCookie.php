<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetAppModeCookie
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if (method_exists($response, 'cookie')) {
            $response->cookie('app_mode', '1', 43800);
        }

        return $response;
    }
}
