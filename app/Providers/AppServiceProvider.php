<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use URL;

ini_set('memory_limit', '-1');

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();
        Schema::defaultStringLength(191);
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        // Override pgsql connection to use custom PostgresConnection for boolean handling
        DB::extend('pgsql', function ($config, $name) {
            $connector = new \Illuminate\Database\Connectors\PostgresConnector;
            $pdo = $connector->connect($config);
            $config['name'] = $name;

            return new \App\Database\PostgresConnection($pdo, $config['database'] ?? '', $config['prefix'] ?? '', $config);
        });

        // Cache-bust static assets (append ?v=<mtime>) so edits reflect immediately.
        // Cloudflare caches static files for its default 4h Edge TTL when the origin
        // sends no Cache-Control, which made frontend asset edits appear stale.
        // Subclass UrlGenerator and rebind the 'url' singleton (URL::macro('asset')
        // cannot work because asset() is a real method on UrlGenerator and macros
        // only fire for non-existent methods).
        $this->app->singleton('url', function ($app) {
            $routes = $app['router']->getRoutes();
            $app->instance('routes', $routes);

            return new \App\Routing\VersionedUrlGenerator(
                $routes,
                $app->rebinding('request', function ($app, $request) {
                    $app['url']->setRequest($request);
                }),
                $app['config']['app.asset_url']
            );
        });
    }
}
