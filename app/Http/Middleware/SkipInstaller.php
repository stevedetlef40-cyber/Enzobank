<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SkipInstaller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('project/install*') && filter_var(config('app.installed', false), FILTER_VALIDATE_BOOLEAN)) {
            return abort(404);
        }

        return $next($request);
    }
}
