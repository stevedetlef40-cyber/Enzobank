<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StartingPoint
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (filter_var(config('app.installed', false), FILTER_VALIDATE_BOOLEAN)) {
            Config::set('starting-point.status', false);
            Config::set('starting-point.point', '/');

            return $next($request);
        }

        if (env('PRODUCT_KEY', null)) {
            Config::set('starting-point.status', false);
        }

        $client_host = request()->getHttpHost();
        $filter_host = preg_replace('/^www\./', '', $client_host);

        try {
            if (Schema::hasTable('script') && DB::table('script')->exists()) {
                $script = DB::table('script')->first();

                if ($script && $filter_host != $script->client) {
                    Config::set('starting-point.status', true);
                    Config::set('starting-point.point', '/project/install/welcome');
                }
            }
        } catch (Exception $e) {
            Config::set('starting-point.status', true);
            Config::set('starting-point.point', '/project/install/welcome');
        }

        if (Config::get('starting-point.status') === true) {
            return redirect(Config::get('starting-point.point'));
        }

        return $next($request);
    }
}
