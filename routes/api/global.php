<?php

use App\Http\Controllers\Api\V1\User\SettingController;
use Illuminate\Support\Facades\Route;

// Settings
Route::controller(SettingController::class)->prefix('settings')->group(function () {
    Route::get('basic-settings', 'basicSettings');
    Route::get('splash-screen', 'splashScreen');
    Route::get('onboard-screens', 'onboardScreens');
    Route::get('languages', 'getLanguages')->withoutMiddleware('system.maintenance.api');
    Route::get('country-list', 'countryList');

});
