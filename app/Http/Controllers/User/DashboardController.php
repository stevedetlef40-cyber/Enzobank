<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return redirect()->route('user.rise.home');
    }

    public function logout(Request $request)
    {
        $authSource = session('auth_source', 'web');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        if ($authSource === 'app') {
            return redirect()->route('app.login');
        }

        return redirect()->route('user.login');
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user();
        try {
            $user->status = 0;
            $user->save();
            Auth::logout();

            return redirect()->route('index')->with(['success' => ['Your account deleted successfully!']]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something Went Wrong! Please Try Again.']]);
        }
    }

    public function checkPin(Request $request)
    {
        $pin = $request->pin;
        $user = auth()->user();

        // No PIN set yet — tell client to show setup mode
        if (is_null($user->pin_code)) {
            return response('2');
        }

        if ($pin != $user->pin_code) {
            return response('0');
        }

        return response('1');
    }
}
