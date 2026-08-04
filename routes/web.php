<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (request()->cookie('app_mode')) {
        return redirect()->route('app.login');
    }

    return view('frontend.index');
})->name('index');

// Standalone app pages (for Android APK WebView)
Route::prefix('app')->name('app.')->middleware('set.app.mode')->group(function () {
    Route::get('login', function () {
        if (auth()->check()) {
            return redirect()->route('app.pin');
        }

        return view('app.login');
    })->name('login');
    Route::get('register', function () {
        return redirect()->route('user.register');
    })->name('register');
    Route::get('pin', function () {
        return view('app.pin');
    })->name('pin')->middleware('auth');
    Route::get('pin/status', function () {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['has_pin' => false, 'authenticated' => false], 401);
        }

        return response()->json([
            'has_pin' => ! is_null($user->pin_code),
            'authenticated' => true,
            'user' => [
                'name' => $user->fullname ?? 'User',
                'initials' => strtoupper(substr($user->fullname ?? 'U', 0, 1)),
            ],
        ]);
    })->name('pin.status')->middleware('auth');
    Route::post('pin/set', function (\Illuminate\Http\Request $request) {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'pin_code' => 'required|digits:4',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'PIN must be 4 digits.'], 422);
        }
        $user = auth()->user();
        $user->update(['pin_code' => $request->pin_code, 'pin_status' => true]);

        return response()->json(['success' => true, 'message' => 'PIN set successfully.']);
    })->name('pin.set')->middleware('auth');
    Route::view('biometric', 'app.biometric')->name('biometric')->middleware('auth');
});
