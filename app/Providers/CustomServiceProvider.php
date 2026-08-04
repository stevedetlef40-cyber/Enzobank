<?php

namespace App\Providers;

use App\Constants\GlobalConst;
use App\Models\Admin\AppSettings;
use App\Models\Admin\BasicSettings;
use App\Models\Admin\Currency;
use App\Models\Admin\Extension;
use App\Models\Admin\Language;
use App\Models\Admin\SetupPage;
use App\Models\Admin\SiteSections;
use App\Models\Admin\SystemMaintenance;
use App\Models\Admin\UsefulLink;
use App\Models\User;
use App\Models\UserSupportTicket;
use App\Providers\Admin\BasicSettingsProvider;
use App\Providers\Admin\CurrencyProvider;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class CustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->startingPoint();

        // Register custom PostgreSQL connection for proper boolean handling
        DB::extend('pgsql', function ($config, $name) {
            $connector = new \Illuminate\Database\Connectors\PostgresConnector;
            $pdo = $connector->connect($config);
            $config['name'] = $name;

            return new \App\Database\PostgresConnection($pdo, $config['database'] ?? '', $config['prefix'] ?? '', $config);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $view_share = [];
            $view_share['basic_settings'] = BasicSettings::first() ?? BasicSettingsProvider::fallbackSettings();
            $view_share['default_currency'] = Currency::default() ?? new \stdClass;
            $view_share['default_currency_code'] = Currency::default()->code ?? 'USD';
            $view_share['default_currency_symbol'] = Currency::default()->symbol ?? '$';
            $view_share['default_currency_rate'] = Currency::default()->rate ?? 1;
            $view_share['__languages'] = Language::get();
            $view_share['all_user_count'] = User::count();
            $view_share['email_verified_user_count'] = User::where('email_verified', '=', \DB::raw('true'))->count();
            $view_share['kyc_verified_user_count'] = User::whereRaw('kyc_verified::int = ?', [GlobalConst::VERIFIED])->count();
            $view_share['__extensions'] = Extension::get();
            $view_share['pending_ticket_count'] = UserSupportTicket::pending()->get()->count();
            $view_share['__website_sections'] = SiteSections::get();
            $view_share['__app_settings'] = AppSettings::first();
            $view_share['__website_useful_link'] = UsefulLink::where('status', \DB::raw('true'))->get();
            $view_share['__website_privacy_policy'] = UsefulLink::where('status', \DB::raw('true'))->where('type', GlobalConst::USEFUL_LINK_PRIVACY_POLICY)->first();
            $view_share['__setup_pages'] = SetupPage::where('status', \DB::raw('true'))->get();
            $view_share['system_maintenance'] = SystemMaintenance::first();
            $this->app['view']->share($view_share);

            $this->app->bind(BasicSettingsProvider::class, function () use ($view_share) {
                return new BasicSettingsProvider($view_share['basic_settings']);
            });
            $this->app->bind(CurrencyProvider::class, function () use ($view_share) {
                return new CurrencyProvider($view_share['default_currency']);
            });
        } catch (Exception $e) {
            // handle error
        }
    }

    public function startingPoint()
    {
        if (env('PURCHASE_CODE', '') == null) {
            Config::set('starting-point.status', true);
            Config::set('starting-point.point', '/project/install/welcome');
        }
    }
}
