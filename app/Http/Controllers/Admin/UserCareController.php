<?php

namespace App\Http\Controllers\Admin;

use App\Constants\GlobalConst;
use App\Constants\PaymentGatewayConst;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Models\CryptoWallet;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLoginLog;
use App\Models\UserMailLog;
use App\Models\UserSupportTicket;
use App\Models\UserWallet;
use App\Notifications\User\MessageNotification;
use App\Notifications\User\SendMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class UserCareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'All Users';
        $users = User::with(['wallet.currency'])->orderBy('id', 'desc')->paginate(12);

        return view('admin.sections.user-care.index', compact(
            'page_title',
            'users'
        ));
    }

    /**
     * Display Active Users
     *
     * @return view
     */
    public function active()
    {
        $page_title = 'Active Users';
        $users = User::active()->with(['wallet.currency'])->orderBy('id', 'desc')->paginate(12);

        return view('admin.sections.user-care.index', compact(
            'page_title',
            'users'
        ));
    }

    /**
     * Display Banned Users
     *
     * @return view
     */
    public function banned()
    {
        $page_title = 'Banned Users';
        $users = User::banned()->with(['wallet.currency'])->orderBy('id', 'desc')->paginate(12);

        return view('admin.sections.user-care.index', compact(
            'page_title',
            'users',
        ));
    }

    /**
     * Display Email Unverified Users
     *
     * @return view
     */
    public function emailUnverified()
    {
        $page_title = 'Email Unverified Users';
        $users = User::active()->with(['wallet.currency'])->orderBy('id', 'desc')->emailUnverified()->paginate(12);

        return view('admin.sections.user-care.index', compact(
            'page_title',
            'users'
        ));
    }

    /**
     * Display SMS Unverified Users
     *
     * @return view
     */
    public function SmsUnverified()
    {
        $page_title = 'SMS Unverified Users';
        $users = User::with(['wallet.currency'])->orderBy('id', 'desc')->paginate(12);

        return view('admin.sections.user-care.index', compact(
            'page_title',
            'users',
        ));
    }

    /**
     * Display KYC Unverified Users
     *
     * @return view
     */
    public function KycUnverified()
    {
        $page_title = 'KYC Unverified Users';
        $users = User::kycUnverified()->with(['wallet.currency'])->orderBy('id', 'desc')->paginate(8);

        return view('admin.sections.user-care.index', compact(
            'page_title',
            'users'
        ));
    }

    /**
     * Display Send Email to All Users View
     *
     * @return view
     */
    public function emailAllUsers()
    {
        $page_title = 'Email To Users';

        return view('admin.sections.user-care.email-to-users', compact(
            'page_title',
        ));
    }

    /**
     * Display Specific User Information
     *
     * @return view
     */
    public function userDetails($username)
    {
        $page_title = 'User Details';
        $user = User::with(['wallet', 'wallets'])->where('username', $username)->first();
        if (! $user) {
            return back()->with(['error' => ['Opps! User not exists']]);
        }
        $total_transactions = Transaction::where('user_id', $user->id)->count();
        $success_transactions = Transaction::where('user_id', $user->id)
            ->where('status', PaymentGatewayConst::STATUSSUCCESS)
            ->count();
        $pending_transactions = Transaction::where('user_id', $user->id)
            ->where('status', PaymentGatewayConst::STATUSPENDING)
            ->count();

        $total_tickets = UserSupportTicket::where('user_id', $user->id)->count();
        $active_tickets = UserSupportTicket::where('user_id', $user->id)->where('status', 2)->count();
        $pending_tickets = UserSupportTicket::where('user_id', $user->id)->where('status', 3)->count();
        $solved_tickets = UserSupportTicket::where('user_id', $user->id)->where('status', 1)->count();

        return view('admin.sections.user-care.details', compact(
            'page_title',
            'user',
            'total_transactions',
            'success_transactions',
            'pending_transactions',
            'total_tickets',
            'active_tickets',
            'solved_tickets',
            'pending_tickets'
        ));
    }

    public function sendMailUsers(Request $request)
    {
        $request->validate([
            'user_type' => 'required|string|max:30',
            'subject' => 'required|string|max:250',
            'message' => 'required|string|max:2000',
        ]);

        $users = [];
        switch ($request->user_type) {
            case 'active':
                $users = User::active()->get();
                break;
            case 'all':
                $users = User::get();
                break;
            case 'email_verified':
                $users = User::emailVerified()->get();
                break;
            case 'kyc_verified':
                $users = User::kycVerified()->get();
                break;
            case 'banned':
                $users = User::banned()->get();
                break;
        }

        try {
            Notification::send($users, new SendMail((object) $request->all()));
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Email successfully sended']]);

    }

    public function sendMail(Request $request, $username)
    {
        $request->merge(['username' => $username]);
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
            'username' => 'required|string|exists:users,username',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal', 'email-send');
        }
        $validated = $validator->validate();
        $user = User::where('username', $username)->first();
        $validated['user_id'] = $user->id;
        $validated = Arr::except($validated, ['username']);
        $validated['method'] = 'SMTP';
        try {
            UserMailLog::create($validated);
            $user->notify(new SendMail((object) $validated));
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Mail successfully sended']]);
    }

    public function userDetailsUpdate(Request $request, $username)
    {
        $request->merge(['username' => $username]);
        $request->merge([
            'virtual_card_limit' => $request->input('virtual_card_limit') === '' ? null : $request->input('virtual_card_limit'),
            'crypto_limit' => $request->input('crypto_limit') === '' ? null : $request->input('crypto_limit'),
            'vc_fee_override' => $request->input('vc_fee_override') === '' ? null : $request->input('vc_fee_override'),
        ]);
        $validator = Validator::make($request->all(), [
            'username' => 'required|exists:users,username',
            'firstname' => 'nullable|string|max:60',
            'lastname' => 'nullable|string|max:60',
            'mobile_code' => 'nullable|string|max:10',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:250',
            'country' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
            'zip_code' => 'nullable|numeric|max_digits:8',
            'email_verified' => 'nullable|boolean',
            'two_factor_verified' => 'nullable|boolean',
            'kyc_verified' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'virtual_card_status' => 'nullable|boolean',
            'crypto_status' => 'nullable|boolean',
            'card_required' => 'nullable|boolean',
            'add_money_status' => 'nullable|boolean',
            'fund_transfer_status' => 'nullable|boolean',
            'money_out_status' => 'nullable|boolean',
            'own_bank_transfer_blocked' => 'nullable|boolean',
            'virtual_card_limit' => 'nullable|numeric|min:0',
            'crypto_limit' => 'nullable|numeric|min:0',
            'vc_fee_override' => 'nullable|numeric|min:0',
            'support_whatsapp' => 'nullable|string|max:30',
        ]);
        $validated = $validator->validate();

        // Postgres boolean columns reject raw integer 0/1 from HTML form inputs,
        // so cast every boolean-typed field to a real bool before persisting.
        foreach ([
            'email_verified', 'two_factor_verified', 'status',
            'virtual_card_status', 'card_required', 'crypto_status',
            'add_money_status', 'fund_transfer_status', 'money_out_status',
            'own_bank_transfer_blocked',
        ] as $booleanField) {
            if (array_key_exists($booleanField, $validated)) {
                $validated[$booleanField] = (bool) $validated[$booleanField];
            }
        }

        $validated['address'] = [
            'country' => $validated['country'] ?? '',
            'state' => $validated['state'] ?? '',
            'city' => $validated['city'] ?? '',
            'zip' => $validated['zip_code'] ?? '',
            'address' => $validated['address'] ?? '',
        ];
        unset($validated['country'], $validated['state'], $validated['city'], $validated['zip_code']);
        $validated['mobile_code'] = remove_speacial_char($validated['mobile_code']);
        $validated['mobile'] = remove_speacial_char($validated['mobile']);
        if ($validated['mobile_code'] !== '' || $validated['mobile'] !== '') {
            $validated['full_mobile'] = $validated['mobile_code'].$validated['mobile'];
        } else {
            $validated['full_mobile'] = null;
        }
        if (! empty($validated['support_whatsapp'])) {
            $validated['support_whatsapp'] = preg_replace('/[^0-9]/', '', $validated['support_whatsapp']);
        } else {
            $validated['support_whatsapp'] = null;
        }

        $user = User::where('username', $username)->first();
        if (! $user) {
            return back()->with(['error' => ['Opps! User not exists']]);
        }

        try {
            $user->update($validated);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Profile Information Updated Successfully!']]);
    }

    /**
     * Toggle a user's active/suspended status directly from the user list.
     */
    public function userStatusToggle(Request $request, $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $user->status = ! $user->status;
        $user->save();

        $message = $user->status
            ? ['User activated successfully!']
            : ['User suspended successfully!'];

        return back()->with(['success' => $message]);
    }

    /**
     * Toggle own bank transfer block for a user via AJAX.
     */
    public function ownBankTransferToggle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
            'data_target' => 'required|string',
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return Response::error(['error' => $validator->errors()], null, 400);
        }
        $validated = $validator->safe()->all();

        $user = User::where('username', $validated['data_target'])->first();
        if (! $user) {
            return Response::error(['error' => ['User not found!']], null, 404);
        }

        try {
            $user->update([
                'own_bank_transfer_blocked' => ($validated['status'] == true) ? false : true,
            ]);
        } catch (Exception $e) {
            return Response::error(['error' => ['Something went wrong!']], null, 500);
        }

        return Response::success(['Own bank transfer status updated!'], null, 200);
    }

    public function loginLogs($username)
    {
        $page_title = 'Login Logs';
        $user = User::where('username', $username)->first();
        if (! $user) {
            return back()->with(['error' => ['Opps! User doesn\'t exists']]);
        }
        $logs = UserLoginLog::where('user_id', $user->id)->paginate(12);

        return view('admin.sections.user-care.login-logs', compact(
            'logs',
            'page_title',
        ));
    }

    public function mailLogs($username)
    {
        $page_title = 'User Email Logs';
        $user = User::where('username', $username)->first();
        if (! $user) {
            return back()->with(['error' => ['Opps! User doesn\'t exists']]);
        }
        $logs = UserMailLog::where('user_id', $user->id)->paginate(12);

        return view('admin.sections.user-care.mail-logs', compact(
            'page_title',
            'logs',
        ));
    }

    public function loginAsMember(Request $request, $username)
    {
        $request->merge(['username' => $username]);
        $request->validate([
            'target' => 'required|string|exists:users,username',
            'username' => 'required_without:target|string|exists:users',
        ]);

        try {
            $user = User::where('username', $request->username)->first();
            Auth::guard('web')->login($user);
        } catch (Exception $e) {
            return back()->with(['error' => [$e->getMessage()]]);
        }

        return redirect()->intended(route('user.dashboard'));
    }

    public function kycDetails($username)
    {
        $user = User::where('username', $username)->first();
        if (! $user) {
            return back()->with(['error' => ['Opps! User doesn\'t exists']]);
        }

        $page_title = 'Kyc Profile';

        return view('admin.sections.user-care.kyc-details', compact('page_title', 'user'));
    }

    public function kycApprove(Request $request, $username)
    {
        $request->merge(['username' => $username]);
        $request->validate([
            'target' => 'required|exists:users,username',
            'username' => 'required_without:target|exists:users,username',
        ]);
        $user = User::where('username', $request->target)->orWhere('username', $request->username)->first();
        if ($user->kyc_verified == GlobalConst::VERIFIED) {
            return back()->with(['warning' => ['User already KYC verified']]);
        }
        if ($user->kyc == null) {
            return back()->with(['error' => ['User KYC information not found']]);
        }

        try {
            $user->update([
                'kyc_verified' => GlobalConst::APPROVED,
            ]);
        } catch (Exception $e) {
            $user->update([
                'kyc_verified' => GlobalConst::PENDING,
            ]);

            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['User KYC successfully approved']]);
    }

    public function kycReject(Request $request, $username)
    {
        $request->validate([
            'target' => 'required|exists:users,username',
            'reason' => 'required|string|max:500',
        ]);
        $user = User::where('username', $request->target)->first();
        if (! $user) {
            return back()->with(['error' => ['User doesn\'t exists']]);
        }
        if ($user->kyc == null) {
            return back()->with(['error' => ['User KYC information not found']]);
        }

        try {
            $user->update([
                'kyc_verified' => GlobalConst::REJECTED,
            ]);
            $user->kyc->update([
                'reject_reason' => $request->reason,
            ]);
        } catch (Exception $e) {
            $user->update([
                'kyc_verified' => GlobalConst::PENDING,
            ]);
            $user->kyc->update([
                'reject_reason' => null,
            ]);

            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['User KYC information is rejected']]);
    }

    public function userCryptoAddresses($username)
    {
        $page_title = 'User Crypto Addresses';
        $user = User::where('username', $username)->first();
        if (! $user) {
            return back()->with(['error' => ['Opps! User doesnt exists']]);
        }
        $addresses = CryptoWallet::where(function ($q) use ($user) {
            $q->whereNull('user_id')->orWhere('user_id', $user->id);
        })->latest()->get();

        return view('admin.sections.user-care.crypto-addresses', compact(
            'page_title',
            'user',
            'addresses',
        ));
    }

    public function userCryptoAddressesStore(Request $request, $username)
    {
        $user = User::where('username', $username)->first();
        if (! $user) {
            return back()->with(['error' => ['Opps! User doesnt exists']]);
        }

        $validator = Validator::make($request->all(), [
            'coin_name' => 'required|string|max:100',
            'symbol' => 'required|string|max:20',
            'network' => 'nullable|string|max:100',
            'wallet_address' => 'required|string|max:255',
            'purpose' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validate();
        $data['user_id'] = $user->id;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            try {
                $image = get_files_from_fileholder($request, 'logo');
                $uploadLogo = upload_files_from_path_dynamic($image, 'crypto-logos');
                $data['logo'] = $uploadLogo;
            } catch (Exception $e) {
                return back()->withErrors($validator)->withInput()->with(['error' => ['Logo upload failed! Please try again.']]);
            }
        }

        try {
            CryptoWallet::create($data);

            return back()->with(['success' => ['Crypto address saved successfully!']]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']])->withInput();
        }
    }

    public function userCryptoAddressesUpdate(Request $request, $username)
    {
        $user = User::where('username', $username)->first();
        if (! $user) {
            return back()->with(['error' => ['Opps! User doesnt exists']]);
        }

        $validator = Validator::make($request->all(), [
            'target' => 'required|integer|exists:crypto_wallets,id',
            'coin_name' => 'required|string|max:100',
            'symbol' => 'required|string|max:20',
            'network' => 'nullable|string|max:100',
            'wallet_address' => 'required|string|max:255',
            'purpose' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal', 'crypto-address-edit');
        }

        $validated = $validator->validate();
        $wallet = CryptoWallet::findOrFail($validated['target']);

        $updateData = [
            'coin_name' => $validated['coin_name'],
            'symbol' => $validated['symbol'],
            'network' => $validated['network'],
            'wallet_address' => $validated['wallet_address'],
            'purpose' => $validated['purpose'],
            'color' => $validated['color'] ?? null,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            try {
                $image = get_files_from_fileholder($request, 'logo');
                $uploadLogo = upload_files_from_path_dynamic($image, 'crypto-logos');
                $updateData['logo'] = $uploadLogo;
            } catch (Exception $e) {
                return back()->withErrors($validator)->withInput()->with(['error' => ['Logo upload failed! Please try again.']]);
            }
        }

        // Handle old logo removal if new one uploaded
        if ($request->hasFile('logo') && $wallet->logo && $request->filled('remove_old_logo')) {
            try {
                $oldPath = get_files_path('crypto-logos').'/'.$wallet->logo;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            } catch (Exception $e) {
                // fail silently
            }
        }

        try {
            $wallet->update($updateData);

            return back()->with(['success' => ['Crypto address updated successfully!']]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']])->withInput();
        }
    }

    public function userCryptoAddressesDelete($username, $id)
    {
        $user = User::where('username', $username)->first();
        if (! $user) {
            return back()->with(['error' => ['Opps! User doesnt exists']]);
        }

        $wallet = CryptoWallet::where('user_id', $user->id)->findOrFail($id);
        $wallet->delete();

        return back()->with(['success' => ['Address deleted successfully!']]);
    }

    public function userCryptoAddressesStatus($username, $id)
    {
        $user = User::where('username', $username)->first();
        if (! $user) {
            return back()->with(['error' => ['Opps! User doesnt exists']]);
        }

        $wallet = CryptoWallet::findOrFail($id);
        $wallet->is_active = ! $wallet->is_active;
        $wallet->save();

        $msg = $wallet->is_active ? 'activated' : 'deactivated';

        return back()->with(['success' => ["Address $msg successfully!"]]);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            $error = ['error' => $validator->errors()];

            return Response::error($error, null, 400);
        }

        $validated = $validator->validate();
        $users = User::search($validated['text'])->limit(10)->get();

        return view('admin.components.search.user-search', compact(
            'users',
        ));
    }

    /**
     * Method for update user wallet balance
     */
    public function walletBalanceUpdate(Request $request, $username)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:add,subtract',
            'wallet' => 'required|numeric|exists:user_wallets,id',
            'amount' => 'required|numeric',
            'remark' => 'nullable|string|max:200',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal', 'wallet-balance-update-modal');
        }

        $validated = $validator->validate();
        $user_wallet = UserWallet::whereHas('user', function ($q) use ($username) {
            $q->where('username', $username);
        })->find($validated['wallet']);
        if (! $user_wallet) {
            return back()->with(['error' => ['User wallet not found!']]);
        }

        DB::beginTransaction();
        try {

            $user_wallet_balance = 0;
            $action_type = '';

            switch ($validated['type']) {
                case 'add':
                    $action_type = 'Added';
                    $user_wallet_balance = $user_wallet->balance + $validated['amount'];
                    DB::table($user_wallet->getTable())->where('id', $user_wallet->id)->increment('balance', $validated['amount']);
                    break;
                case 'subtract':
                    $action_type = 'Subtract';
                    if ($user_wallet->balance >= $validated['amount']) {
                        $user_wallet_balance = $user_wallet->balance - $validated['amount'];
                        DB::table($user_wallet->getTable())->where('id', $user_wallet->id)->decrement('balance', $validated['amount']);
                    } else {
                        return back()->with(['error' => ['User do not have sufficient balance']]);
                    }
                    break;
            }
            $trx_id = generateTrxString('transactions', 'trx_id', 'BAS-', 14);

            DB::table('transactions')->insertGetId([
                'type' => PaymentGatewayConst::TYPEADDSUBTRACTBALANCE,
                'trx_id' => $trx_id,
                'user_type' => GlobalConst::ADMIN,
                'user_id' => $user_wallet->user->id,
                'wallet_id' => $user_wallet->id,
                'admin_id' => auth()->user()->id,
                'request_amount' => $validated['amount'],
                'request_currency' => $user_wallet->currency->code,
                'percent_charge' => 0,
                'fixed_charge' => 0,
                'total_charge' => 0,
                'total_payable' => $validated['amount'],
                'receiver_type' => GlobalConst::USER,
                'receiver_id' => $user_wallet->user->id,
                'available_balance' => $user_wallet_balance,
                'remark' => $validated['remark'] ?? '',
                'details' => json_encode($action_type),
                'status' => PaymentGatewayConst::STATUSSUCCESS,
                'attribute' => GlobalConst::RECEIVED,
                'created_at' => now(),
            ]);

            // Send Mail to User
            $from_or_to = ($action_type == 'Added') ? 'to' : 'from';
            $data['message'] = 'Your wallet balance updated by '.auth()->user()->getRolesString().'. '.$action_type.' ('.$validated['amount'].$user_wallet->currency->code.')  '.$from_or_to.' '.$user_wallet->currency->code.' Wallet Balance';
            $user_wallet->user->notify(new MessageNotification($data));

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();

            return back()->with(['error' => ['Transaction failed! '.$e->getMessage()]]);
        }

        return back()->with(['success' => ['Transaction success']]);
    }
}
