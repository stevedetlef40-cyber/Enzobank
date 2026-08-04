<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\GlobalConst;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\User\LoggedInUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    protected $request_data;

    protected $lockoutTime = 1;

    use AuthenticatesUsers, LoggedInUsers;

    public function showLoginForm()
    {
        $page_title = 'User Login';

        return view('user.auth.login', compact(
            'page_title',
        ));
    }

    /**
     * Validate the user login request.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $this->request_data = $request;
        $request->validate([
            'credentials' => 'required|string',
            'password' => 'required|string',
        ]);

        // if user exists with banner
        if (User::where($this->username(), $request->credentials)->where('status', GlobalConst::BANNED)->exists()) {
            throw ValidationException::withMessages([
                'credentials' => 'Your account has been suspended!',
            ]);
        }

    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @return array
     */
    protected function credentials(Request $request)
    {
        $request->merge(['status' => true]);
        $request->merge([$this->username() => $request->credentials]);

        return $request->only($this->username(), 'password', 'status');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        $request = $this->request_data->all();
        $credentials = $request['credentials'];
        if (filter_var($credentials, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        return 'username';
    }

    /**
     * Get the failed login response instance.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'credentials' => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('web');
    }

    /**
     * The user has been authenticated.
     *
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {

        $user->update([
            'two_factor_verified' => false,
        ]);

        $this->refreshUserWallets($user);
        $this->createLoginLog($user);

        if ($request->wantsJson()) {
            session(['auth_source' => 'app']);

            return response()->json([
                'success' => true,
                'redirect' => route('app.pin'),
                'user' => $user->only(['id', 'fullname', 'email', 'username', 'image']),
            ]);
        }

        session(['auth_source' => 'web']);

        return redirect()->intended(route('user.dashboard'));
    }
}
