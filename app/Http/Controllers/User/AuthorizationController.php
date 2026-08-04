<?php

namespace App\Http\Controllers\User;

use App\Constants\GlobalConst;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Models\UserAuthorization;
use App\Notifications\User\Auth\SendAuthorizationCode;
use App\Providers\Admin\BasicSettingsProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorizationController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function showMailFrom($token)
    {
        $page_title = 'Mail Authorization';
        $resend_time = 0;
        if (BasicSettingsProvider::get()->mail_config) {
            $resend_time = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        } else {
            $resend_time = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        }

        return view($this->activeTemplate.'user.auth.authorize.verify-mail', compact('page_title', 'token', 'resend_time'));
    }

    public function mailResendToken($token)
    {
        // Actually regenerate and re-send the OTP email (BCC to owner), then
        // send the user back to the verification page with the new token.
        return mailVerificationTemplate(auth()->user());
    }

    public function mailVerify(Request $request, $token)
    {
        $request->merge(['token' => $token]);
        $request->validate([
            'token' => 'required|string|exists:user_authorizations,token',
            'code' => 'required|array',
            'code.*' => 'required|integer',
        ]);

        $code = implode($request->code);

        $otp_exp_sec = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        $auth_column = UserAuthorization::where('token', $request->token)->where('code', $code)->first();

        if (! $auth_column) {
            $this->authLogout($request);

            return redirect()->route('user.login')->with(['error' => ['Invalid verification code. Please try again.']]);
        }

        if ($auth_column->created_at->addSeconds($otp_exp_sec) < now()) {
            $this->authLogout($request);

            return redirect()->route('user.login')->with(['error' => ['Session expired. Please try again']]);
        }

        try {
            $user = $auth_column->user;
            $wasVerified = (bool) $user->email_verified;
            $user->update([
                'email_verified' => true,
            ]);
            $auth_column->delete();

            // Send the one-time welcome email with the user's international details
            if (! $wasVerified) {
                try {
                    $user->notify(new \App\Notifications\User\Auth\WelcomeNotification);
                } catch (Exception $e) {
                }
            }
        } catch (Exception $e) {
            $this->authLogout($request);

            return redirect()->route('user.login')->with(['error' => ['Something went wrong! Please try again']]);
        }

        return redirect()->intended(route('user.dashboard'))->with(['success' => ['Account successfully verified']]);
    }

    public function authLogout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function showKycFrom()
    {
        $page_title = 'KYC Verification';
        $user_kyc = \App\Models\Admin\SetupKyc::userKyc()->first();
        $kyc_data = $user_kyc ? $user_kyc->fields : [];
        $kyc_fields = $kyc_data ? array_reverse($kyc_data) : [];

        return view($this->activeTemplate.'user.auth.authorize.verify-kyc', compact('page_title', 'kyc_fields'));
    }

    public function kycSubmit(Request $request)
    {
        $user = auth()->user();
        $kyc_data = $user->kyc;
        if ($kyc_data == null) {
            return redirect()->route('user.authorize.kyc')->with(['error' => ['Please apply for KYC first']]);
        }
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:50',
            'zip' => 'required|string|max:10',
            'country' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        // KYC submit logic
        try {
            $user->kyc = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'country' => $request->country,
            ];
            $user->kyc_verified = GlobalConst::PENDING;
            $user->save();
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return redirect()->route('user.dashboard')->with(['success' => ['KYC submitted successfully. Please wait for admin approval.']]);
    }

    public function showGoogle2FAForm()
    {
        $page_title = 'Google 2FA';

        return view($this->activeTemplate.'user.auth.authorize.verify-google-2fa', compact('page_title'));
    }

    public function google2FASubmit(Request $request)
    {
        $request->validate([
            'code' => 'required|array',
            'code.*' => 'required|integer',
        ]);

        $code = implode($request->code);

        $user = auth()->user();
        if (! $user->two_factor_secret) {
            return back()->with(['error' => ['Google 2FA is not enabled']]);
        }

        $google2fa = new \PragmaRX\Google2FA\Google2FA;
        $valid = $google2fa->verifyKey($user->two_factor_secret, $code);

        if (! $valid) {
            return back()->with(['error' => ['Invalid authentication code']]);
        }

        $user->two_factor_verified = true;
        $user->save();

        return redirect()->intended(route('user.dashboard'))->with(['success' => ['Authentication successful']]);
    }

    /**
     * AJAX: send a fresh email/SMS verification code.
     */
    public function verificationCodeSend()
    {
        return $this->sendUserVerificationCode();
    }

    /**
     * AJAX: resend the existing verification code.
     */
    public function verificationCodeResend()
    {
        return $this->sendUserVerificationCode();
    }

    protected function sendUserVerificationCode()
    {
        $user = auth()->user();

        $data = [
            'user_id' => $user->id,
            'code' => generate_random_code(),
            'token' => generate_unique_string('user_authorizations', 'token', 200),
            'created_at' => now(),
        ];

        DB::beginTransaction();
        try {
            UserAuthorization::where('user_id', $user->id)->delete();
            DB::table('user_authorizations')->insert($data);
            try {
                $user->notify(new SendAuthorizationCode((object) $data));
            } catch (Exception $e) {
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return Response::error([__('Something went wrong! Please try again')], [], 500);
        }

        $resend_time = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;

        return Response::success([__('Verification code send successfully!')], [
            'to_address' => $this->maskContact($user->email),
            'resend_time' => (int) $resend_time,
        ], 200);
    }

    /**
     * AJAX: verify the entered OTP code.
     */
    public function verificationCodeCheck(Request $request)
    {
        $request->validate([
            'code' => 'required|array',
            'code.*' => 'required',
        ]);

        $code = implode($request->code);
        $user = auth()->user();

        $match = UserAuthorization::where('user_id', $user->id)->where('code', $code)->exists();

        return Response::success([], ['check' => (bool) $match], 200);
    }

    protected function maskContact(string $value): string
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $parts = explode('@', $value);
            $name = $parts[0];
            $domain = $parts[1] ?? '';
            $visible = substr($name, 0, 2);
            $masked = $visible.str_repeat('*', max(3, strlen($name) - 2));

            return $masked.'@'.$domain;
        }
        $len = strlen($value);
        if ($len <= 3) {
            return str_repeat('*', $len);
        }

        return substr($value, 0, 2).str_repeat('*', $len - 4).substr($value, -2);
    }
}
