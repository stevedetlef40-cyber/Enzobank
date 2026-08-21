<?php

namespace App\Http\Controllers\User;

use App\Constants\GlobalConst;
use App\Constants\NotificationConst;
use App\Constants\PaymentGatewayConst;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Models\Admin\AdminNotification;
use App\Models\Admin\BasicSettings;
use App\Models\Admin\Currency;
use App\Models\Admin\TransactionSetting;
use App\Models\Admin\VirtualCardApi;
use App\Models\StrowalletCustomerKyc;
use App\Models\StrowalletVirtualCard;
use App\Models\Transaction;
use App\Models\UserWallet;
use App\Notifications\User\VirtualCard\CardBuyNotification;
use App\Notifications\User\VirtualCard\CardFundNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class StrowalletVirtualCardController extends Controller
{
    protected $api;

    protected $card_limit;

    protected $basic_settings;

    public function __construct()
    {
        $card_api = VirtualCardApi::first();
        $this->api = $card_api;
        $this->card_limit = $card_api->card_limit ?? 0;
        $this->basic_settings = BasicSettings::first();
    }

    /**
     * Method for show virtual card page
     *
     * @return view
     */
    public function index()
    {
        $user = auth()->user();
        if (! $user->virtual_card_status) {
            return back()->with(['error' => [__('Virtual card service is currently disabled for your account.')]]);
        }
        if ($user->card_required) {
            StrowalletVirtualCard::auth()->where('is_active', true)->update(['is_active' => false]);

            return redirect()->route('user.strowallet.virtual.card.locked');
        }

        $page_title = 'Virtual Card';
        // Cards are generated locally, so there is no external provider to sync with.
        $myCards = StrowalletVirtualCard::auth()->latest()->limit($this->card_limit)->get();
        $customer = $user->strowallet_customer;
        $customer_email = ($customer && isset($customer->customerEmail))
            ? $customer->customerEmail
            : '';

        if ($customer_email === false) {
            $customer_card = 0;
        } else {
            $customer_card = StrowalletVirtualCard::auth()->where('customer_email', $customer_email)->count();
        }
        $cardCharge = TransactionSetting::where('slug', GlobalConst::TRX_VIRTUAL_CARD)->where('status', true)->first();
        $cardReloadCharge = TransactionSetting::where('slug', GlobalConst::TRX_RELOAD_CARD)->where('status', true)->first();
        $cardApi = $this->api;
        $card_limit = $this->card_limit;
        $transactions = Transaction::auth()->where('type', PaymentGatewayConst::TYPEVIRTUALCARD)->orderBy('id', 'desc')->latest()->take(3)->get();
        $showCardGate = $myCards->count() > 0;

        return view('user.sections.virtual-card-strowallet.index', compact(
            'page_title',
            'cardApi',
            'myCards',
            'card_limit',
            'cardCharge',
            'transactions',
            'customer_card',
            'cardReloadCharge',
            'showCardGate',
        ));
    }

    /**
     * Method for strowallet card buy page
     */
    public function createPage()
    {
        $page_title = __('Create Virtual Card');
        $user = userGuard()['user'];
        $cardCharge = TransactionSetting::where('slug', 'virtual_card')->where('status', true)->first();
        // Customer is created locally (no provider), so ensure the local record
        // always carries a customer id and an approved KYC status.
        if ($user->strowallet_customer != null && empty($user->strowallet_customer->customerId)) {
            $customer = (array) $user->strowallet_customer;
            $customer['customerId'] = 'local-cust-'.$user->id;
            $customer['status'] = GlobalConst::CARD_HIGH_KYC_STATUS;
            $user->strowallet_customer = (object) $customer;
            $user->save();
        }

        return view('user.sections.virtual-card-strowallet.create', compact('page_title', 'user', 'cardCharge'));
    }

    /**
     * Method for strowallet create customer
     */
    public function createCustomer(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'regex:/^[^0-9\W]+$/'],
            'last_name' => ['required', 'string', 'regex:/^[^0-9\W]+$/'],
            'customer_email' => 'required|email',
            'date_of_birth' => 'required|string',
            'house_number' => 'required|string',
            'address' => 'required|string',
            'zip_code' => 'required|string',
            'id_image_font' => 'required|image|mimes:jpg,png,svg,webp',
            'user_image' => 'required|image|mimes:jpg,png,svg,webp',
        ], [
            'first_name.regex' => __('The First Name field should only contain letters and cannot start with a number or special character.'),
            'last_name.regex' => __('The Last Name field should only contain letters and cannot start with a number or special character.'),
        ])->validate();
        $user = userGuard()['user'];
        $validated['phone'] = $user->full_mobile;

        try {
            if ($user->strowallet_customer == null) {
                if ($request->hasFile('id_image_font')) {
                    $image = upload_file($validated['id_image_font'], 'card-kyc-images');
                    $upload_image = upload_files_from_path_dynamic([$image['dev_path']], 'card-kyc-images');
                    $validated['id_image_font'] = $upload_image;
                }
                if ($request->hasFile('user_image')) {
                    $image = upload_file($validated['user_image'], 'card-kyc-images');
                    $upload_image = upload_files_from_path_dynamic([$image['dev_path']], 'card-kyc-images');
                    $validated['user_image'] = $upload_image;
                }
                $exist_kyc = StrowalletCustomerKyc::where('user_id', $user->id)->first();
                if ($exist_kyc) {
                    $exist_kyc->update([
                        'user_id' => $user->id,
                        'face_image' => $validated['user_image'],
                        'id_image' => $validated['id_image_font'],
                    ]);
                    $kyc_info = StrowalletCustomerKyc::where('user_id', $user->id)->first();
                } else {
                    $kyc_info = StrowalletCustomerKyc::create([
                        'user_id' => $user->id,
                        'face_image' => $validated['user_image'],
                        'id_image' => $validated['id_image_font'],
                    ]);
                }
                $validated = Arr::except($validated, ['id_image_font', 'user_image']);

                // No external provider: store the customer details locally and mark the
                // KYC as approved so the card purchase form is unlocked immediately.
                $user->strowallet_customer = (object) [
                    'customerId' => 'local-cust-'.$user->id,
                    'customerEmail' => $validated['customer_email'] ?? $user->email,
                    'firstName' => $validated['first_name'] ?? '',
                    'lastName' => $validated['last_name'] ?? '',
                    'phoneNumber' => $user->full_mobile ?? '',
                    'line1' => $validated['address'] ?? '',
                    'houseNumber' => $validated['house_number'] ?? '',
                    'zipCode' => $validated['zip_code'] ?? '',
                    'city' => 'Accra',
                    'state' => 'Accra',
                    'country' => 'Ghana',
                    'status' => GlobalConst::CARD_HIGH_KYC_STATUS,
                ];
                $user->save();
            }

            return redirect()->route('user.strowallet.virtual.card.create')->with(['success' => [__('Customer has been created successfully.')]]);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Strowallet createCustomer exception: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
    }

    /**
     * Method for strowallet edit customer
     */
    public function editCustomer()
    {
        $user = userGuard()['user'];
        if ($user->strowallet_customer == null) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
        $page_title = __('Update Customer Kyc');
        $customer_kyc = StrowalletCustomerKyc::where('user_id', $user->id)->first();

        return view('user.sections.virtual-card-strowallet.edit', compact('page_title', 'user', 'customer_kyc'));
    }

    /**
     * Method for strowallet update customer
     */
    public function updateCustomer(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'regex:/^[^0-9\W]+$/'],
            'last_name' => ['required', 'string', 'regex:/^[^0-9\W]+$/'],
            'id_image_font' => 'nullable|image|mimes:jpg,png,svg,webp',
            'user_image' => 'nullable|image|mimes:jpg,png,svg,webp',
        ], [
            'first_name.regex' => __('The First Name field should only contain letters and cannot start with a number or special character.'),
            'last_name.regex' => __('The Last Name field should only contain letters and cannot start with a number or special character.'),
        ])->validate();
        $user = userGuard()['user'];

        try {
            if ($user->strowallet_customer != null) {
                $customer_kyc = StrowalletCustomerKyc::where('user_id', $user->id)->first();
                if ($request->hasFile('id_image_font')) {
                    $id_image = upload_file($validated['id_image_font'], 'card-kyc-images');
                    $upload_image = upload_files_from_path_dynamic([$id_image['dev_path']], 'card-kyc-images', $customer_kyc->id_image ?? null);
                    // delete_file($id_image['dev_path']);
                    $validated['id_image_font'] = $upload_image;
                }

                // user image
                if ($request->hasFile('user_image')) {
                    $user_image = upload_file($validated['user_image'], 'card-kyc-images', $customer_kyc->face_image ?? null);
                    $upload_image = upload_files_from_path_dynamic([$user_image['dev_path']], 'card-kyc-images');
                    // delete_file($user_image['dev_path']);
                    $validated['user_image'] = $upload_image;
                }
                // store kyc images
                $customer_kyc->update([
                    'user_id' => $user->id,
                    'id_image' => $validated['id_image_font'] ?? $customer_kyc->id_image,
                    'face_image' => $validated['user_image'] ?? $customer_kyc->face_image,
                ]);

                // Locally managed customer: no provider sync needed. Keep the local
                // KYC record as the source of truth and refresh the stored names.
                $validated = Arr::except($validated, ['id_image_font', 'user_image']);
                $customer = (array) $user->strowallet_customer;
                if (! empty($validated['first_name'])) {
                    $customer['firstName'] = $validated['first_name'];
                }
                if (! empty($validated['last_name'])) {
                    $customer['lastName'] = $validated['last_name'];
                }
                $customer['status'] = GlobalConst::CARD_HIGH_KYC_STATUS;
                $user->strowallet_customer = (object) $customer;
                $user->save();

            } else {
                return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
            }

            return redirect()->back()->with(['success' => [__('Customer has been updated successfully.')]]);

        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

    }

    /**
     * Method for buy card
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function cardBuy(Request $request)
    {
        $user = auth()->user();
        if (! $user->virtual_card_status) {
            return back()->with(['error' => [__('Virtual card service is currently disabled for your account.')]]);
        }
        $request->validate([
            'card_amount' => 'required|numeric|gt:0',
            'name_on_card' => 'required|string|min:4|max:50',
        ]);
        $formData = $request->all();

        $amount = $request->card_amount;
        if ($user->virtual_card_limit !== null && $amount > $user->virtual_card_limit) {
            return back()->with(['error' => [__('You can pay a maximum of :amount for a virtual card.', ['amount' => get_amount($user->virtual_card_limit)])]]);
        }
        $basic_setting = $this->basic_settings;
        $wallet = UserWallet::auth()->first();
        if (! $wallet) {
            return back()->with(['error' => [__('User wallet not found')]]);
        }
        $cardCharge = TransactionSetting::where('slug', GlobalConst::TRX_VIRTUAL_CARD)->where('status', true)->first();
        $baseCurrency = Currency::default();
        $rate = $baseCurrency->rate;
        if (! $baseCurrency) {
            return back()->with(['error' => [__('Default currency not found.')]]);
        }
        $minLimit = $cardCharge->min_limit * $rate;
        $maxLimit = $cardCharge->max_limit * $rate;
        if ($amount < $minLimit || $amount > $maxLimit) {
            return back()->with(['error' => [__('Please follow the transaction limit.')]]);
        }
        // charge calculations
        $fixedCharge = $cardCharge->fixed_charge * $rate;
        $percent_charge = ($amount / 100) * $cardCharge->percent_charge;
        $total_charge = $fixedCharge + $percent_charge;
        $payable = $total_charge + $amount;
        if ($payable > $wallet->balance) {
            return back()->with(['error' => [__('Sorry, insufficient balance.')]]);
        }
        $customer = $user->strowallet_customer;
        if (! $customer) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
        $customer_email = $user->strowallet_customer->customerEmail ?? false;
        if ($customer_email === false) {
            $customer_card = 0;
        } else {
            $customer_card = StrowalletVirtualCard::where('customer_email', $customer_email)->count();
        }

        if ($customer_card >= $this->card_limit) {
            return back()->with(['error' => [__('Sorry! You can not create more than').' '.$this->card_limit.' '.__('card using the same email address.')]]);
        }
        // Generate the card locally (no external provider).
        $created_card = generate_local_virtual_card($user, $formData, $request->card_amount, $customer);

        if ($created_card['status'] == false) {
            return back()->with(['error' => [$created_card['message']]]);
        }

        $strowallet_card = new StrowalletVirtualCard;
        $strowallet_card->user_id = $user->id;
        $strowallet_card->name_on_card = $created_card['data']['name_on_card'];
        $strowallet_card->card_id = $created_card['data']['card_id'];
        $strowallet_card->card_created_date = $created_card['data']['card_created_date'] ?? date('Y-m-d');
        $strowallet_card->card_type = $created_card['data']['card_type'];
        $strowallet_card->card_brand = 'visa';
        $strowallet_card->card_user_id = $created_card['data']['card_user_id'] ?? $user->id;
        $strowallet_card->reference = $created_card['data']['reference'];
        $strowallet_card->card_status = $created_card['data']['card_status'];
        $strowallet_card->customer_id = $created_card['data']['customer_id'];
        $strowallet_card->customer_email = $request->customer_email ?? $customer->customerEmail;
        $strowallet_card->balance = $amount;

        // Persist full card details returned by the provider so the "My Card"
        // screen can render the masked number and CVV without a second API call.
        // The create response may nest details directly or under "card_detail".
        $created_resp = $created_card['data'] ?? [];
        $created_detail = $created_resp['card_detail'] ?? $created_resp;
        $strowallet_card->card_number = $created_resp['card_number'] ?? ($created_detail['card_number'] ?? null);
        $strowallet_card->last4 = $created_resp['last4'] ?? ($created_detail['last4'] ?? null);
        // The CVV is generated server-side, unique per card, and stored encrypted at
        // rest. It is never taken from the provider response or derived from any other
        // card attribute, and is only revealed via the dedicated CVV API.
        $strowallet_card->cvv = encryptCvv(generateCVV());
        $strowallet_card->expiry = $created_resp['expiry'] ?? ($created_detail['expiry'] ?? null);

        $strowallet_card->save();

        $trx_id = generateTrxString('transactions', 'trx_id', 'CB-', 14);
        try {
            $transaction_id = $this->insertCardBuyInformation($trx_id, $user, $wallet, $amount, $strowallet_card, $payable, $fixedCharge, $percent_charge, $total_charge);

            $this->createTransactionDeviceRecord($transaction_id);
            if ($basic_setting->email_notification == true) {
                $data = [
                    'trx_id' => $trx_id,
                    'title' => 'Virtual Card (Buy Card)',
                    'request_amount' => $amount,
                    'total_charge' => $total_charge,
                    'payable' => $payable,
                    'request_currency' => get_default_currency_code(),
                    'status' => 'Success',
                ];
                try {
                    Notification::route('mail', $user->email)->notify(new CardBuyNotification($user, (object) $data));
                } catch (Exception $e) {
                }

            }
            user_notification_data_save($user->id, $type = PaymentGatewayConst::TYPEVIRTUALCARD, $title = 'Virtual Card (Card Buy)', $transaction_id, $amount, $gateway = null, $currency = get_default_currency_code(), $message = 'Card Buy Successful.');
            $this->notification($payable, $user, $type = 'Card Buy');

        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return redirect()->route('user.strowallet.virtual.card.index')->with(['success' => [__('Virtual Card Buy Successfully.')]]);
    }

    // function for save card buy information
    public function insertCardBuyInformation($trx_id, $user, $wallet, $amount, $strowallet_card, $payable, $fixedCharge, $percent_charge, $total_charge)
    {
        $available_balance = $wallet->balance - $payable;
        $details = [
            'card_info' => $strowallet_card ?? '',
        ];
        DB::beginTransaction();
        try {
            $id = DB::table('transactions')->insertGetId([
                'type' => PaymentGatewayConst::TYPEVIRTUALCARD,
                'trx_id' => $trx_id,
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'request_amount' => $amount,
                'request_currency' => get_default_currency_code(),
                'fixed_charge' => $fixedCharge,
                'percent_charge' => $percent_charge,
                'total_charge' => $total_charge,
                'total_payable' => $payable,
                'available_balance' => $available_balance,
                'remark' => PaymentGatewayConst::CARDBUY,
                'details' => json_encode($details),
                'status' => PaymentGatewayConst::STATUSSUCCESS,
                'attribute' => GlobalConst::RECEIVED,
                'created_at' => now(),
            ]);

            $this->updateWalletBalance($wallet, $available_balance);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(__('Data is not saved properly.'));
        }

        return $id;
    }

    // update user wallet balance
    public function updateWalletBalance($wallet, $available_balance)
    {
        $wallet->update([
            'balance' => $available_balance,
        ]);
    }

    // save transaction device information
    public function createTransactionDeviceRecord($transaction_id)
    {
        $client_ip = request()->ip() ?? false;
        $location = geoip()->getLocation($client_ip);
        $agent = new Agent;
        DB::beginTransaction();
        try {
            DB::table('transaction_devices')->insert([
                'transaction_id' => $transaction_id,
                'ip' => $client_ip,
                'city' => $location['city'] ?? '',
                'country' => $location['country'] ?? '',
                'longitude' => $location['lon'] ?? '',
                'latitude' => $location['lat'] ?? '',
                'timezone' => $location['timezone'] ?? '',
                'browser' => $agent->browser() ?? '',
                'os' => $agent->platform() ?? '',
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    // notification information
    public function notification($payable, $user, $type)
    {

        $notification_content_admin = [
            'title' => "Virtual Card($type)",
            'message' => "$type ".get_amount($payable).' '.get_default_currency_code().'by '.$user->fullname,
            'time' => Carbon::now()->diffForHumans(),
            'image' => auth()->user()->userImage,
        ];
        AdminNotification::create([
            'type' => NotificationConst::SIDE_NAV,
            'admin_id' => 1,
            'message' => $notification_content_admin,
        ]);
    }

    /**
     * Method for card details information
     *
     * @param  Illuminate\Http\Request  $request,  $card_id
     */
    public function cardDetails(Request $request, $card_id)
    {
        $page_title = __('Card Details');
        $myCard = StrowalletVirtualCard::where('card_id', $card_id)->first();
        if (! $myCard) {
            return back()->with(['error' => [__('Something is wrong in your card')]]);
        }

        // Cards are generated locally with all details, so there is nothing to
        // fetch from a provider. Backfill any legacy pending rows for safety.
        if ($myCard->card_status == 'pending') {
            $myCard->card_status = 'active';
            $myCard->cvv = $myCard->cvv ?: encryptCvv(generateCVV());
            $myCard->save();
        }
        $cardApi = $this->api;

        return view('user.sections.virtual-card-strowallet.details', compact(
            'page_title',
            'myCard',
            'cardApi'
        ));
    }

    /**
     * Reveal the decrypted CVV for a card the authenticated user owns.
     * The CVV is stored encrypted at rest; it is only returned here, on an
     * explicit request, and is never included in the general card-details payload.
     */
    public function cardCvv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data_target' => 'required|string',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return Response::error(['Something went wrong! Please try again.']);
        }

        $card = StrowalletVirtualCard::auth()->where('id', $request->data_target)->first();
        if (! $card) {
            return Response::error(['Card not found.']);
        }
        if (($card->card_status ?? '') === 'canceled') {
            return Response::error(['Canceled cards cannot be viewed.']);
        }

        $cvv = decryptCvv($card->cvv);
        if ($cvv === null) {
            return Response::error(['CVV is not available for this card.']);
        }

        return Response::success(['CVV retrieved successfully.'], ['cvv' => $cvv]);
    }

    /**
     * card freeze unfreeze
     */
    public function cardBlockUnBlock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
            'data_target' => 'required|string',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];

            return Response::error($error, null, 400);
        }
        $validated = $validator->safe()->all();
        if ($request->status == 1) {
            $card = StrowalletVirtualCard::where('id', $request->data_target)->where('is_active', true)->first();
            if (! $card) {
                $error = ['error' => [__('Card not found or already frozen.')]];

                return Response::error($error, null, 404);
            }
            if (($card->card_status ?? '') === 'canceled') {
                $error = ['error' => [__('Canceled cards cannot be unfrozen.')]];

                return Response::error($error, null, 400);
            }
            // Locally managed card: flip the status directly, no provider call.
            $card->is_active = false;
            $card->save();
            $success = ['success' => [__('Card Freeze successfully!')]];

            return Response::success($success, null, 200);
        } else {
            $card = StrowalletVirtualCard::where('id', $request->data_target)->where('is_active', false)->first();
            if (! $card) {
                $error = ['error' => [__('Card not found or already unfrozen.')]];

                return Response::error($error, null, 404);
            }
            if (($card->card_status ?? '') === 'canceled') {
                $error = ['error' => [__('Canceled cards cannot be unfrozen.')]];

                return Response::error($error, null, 400);
            }

            // Locally managed card: flip the status directly, no provider call.
            $card->is_active = true;
            $card->save();
            $success = ['success' => [__('Card UnFreeze successfully!')]];

            return Response::success($success, null, 200);
        }

    }

    /**
     * card cancel (permanent / destructive)
     */
    public function cardCancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data_target' => 'required|string',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];

            return Response::error($error, null, 400);
        }

        $card = StrowalletVirtualCard::where('id', $request->data_target)
            ->where('user_id', auth()->id())
            ->first();

        if (! $card) {
            return Response::error(['error' => [__('Card not found')]], null, 404);
        }
        if (($card->card_status ?? '') === 'canceled') {
            return Response::error(['error' => [__('Card already canceled')]], null, 400);
        }

        // Locally managed card: cancel directly, no provider call.
        $card->card_status = 'canceled';
        $card->is_active = false;
        $card->save();
        $success = ['success' => [__('Card canceled successfully!')]];

        return Response::success($success, null, 200);
    }

    public function makeDefaultOrRemove(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'target' => 'required|numeric',
        ])->validate();
        $user = auth()->user();
        $targetCard = StrowalletVirtualCard::where('id', $validated['target'])->where('user_id', $user->id)->first();
        $withOutTargetCards = StrowalletVirtualCard::where('id', '!=', $validated['target'])->where('user_id', $user->id)->get();

        try {
            $targetCard->update([
                'is_default' => $targetCard->is_default ? 0 : 1,
            ]);
            if (isset($withOutTargetCards)) {
                foreach ($withOutTargetCards as $card) {
                    $card->is_default = false;
                    $card->save();
                }
            }

        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__('Status Updated Successfully!')]]);
    }

    /**
     * Card Fund
     */
    public function cardFundConfirm(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'fund_amount' => 'required|numeric|gt:0',
        ]);
        $basic_setting = BasicSettings::first();
        $user = auth()->user();
        if (! $user->virtual_card_status) {
            return back()->with(['error' => [__('Virtual card service is currently disabled for your account.')]]);
        }

        $myCard = StrowalletVirtualCard::where('user_id', $user->id)->where('id', $request->id)->first();
        if (! $myCard) {
            return back()->with(['error' => [__('Something is wrong in your card.')]]);
        }

        $amount = $request->fund_amount;
        if ($user->virtual_card_limit !== null && $amount > $user->virtual_card_limit) {
            return back()->with(['error' => [__('You can fund a virtual card with a maximum of :amount.', ['amount' => get_amount($user->virtual_card_limit)])]]);
        }
        $wallet = UserWallet::where('user_id', $user->id)->first();
        if (! $wallet) {
            return back()->with(['error' => [__('User wallet not found!')]]);
        }
        $cardCharge = TransactionSetting::where('slug', 'reload_card')->where('status', true)->first();
        $baseCurrency = Currency::default();
        $rate = $baseCurrency->rate;
        if (! $baseCurrency) {
            return back()->with(['error' => [__('Default currency not found.')]]);
        }
        $fixedCharge = $cardCharge->fixed_charge * $rate;
        $percent_charge = ($amount / 100) * $cardCharge->percent_charge;
        $total_charge = $fixedCharge + $percent_charge;
        $payable = $total_charge + $amount;
        if ($payable > $wallet->balance) {
            return back()->with(['error' => [__('Sorry, insufficient balance.')]]);
        }

        // Locally managed card: credit the balance directly, no provider call.
        $myCard->balance += $amount;
        $myCard->save();
        $trx_id = generateTrxString('transactions', 'trx_id', 'CF-', 14);
        $transaction_id = $this->insertCardFund($trx_id, $user, $wallet, $amount, $myCard, $payable, $fixedCharge, $percent_charge, $total_charge);
        $this->createTransactionDeviceRecord($transaction_id);
        if ($basic_setting->email_notification == true) {
            $data = [
                'trx_id' => $trx_id,
                'title' => 'Virtual Card (Fund Amount)',
                'request_amount' => $amount,
                'total_charge' => $total_charge,
                'payable' => $payable,
                'request_currency' => get_default_currency_code(),
                'card_amount' => $myCard->balance,
                'card_pan' => $myCard->card_number ?? '---- ----- ---- ----',
                'charges' => $total_charge,
                'status' => 'Success',
            ];
            try {
                Notification::route('mail', $user->email)->notify(new CardFundNotification($user, (object) $data));
            } catch (Exception $e) {
            }

        }
        user_notification_data_save($user->id, $type = PaymentGatewayConst::TYPEVIRTUALCARD, $title = 'Virtual Card (Card Fund)', $transaction_id, $amount, $gateway = null, $currency = get_default_currency_code(), $message = 'Card Fund Successful.');

        $this->notification($payable, $user, $type = 'Card Fund');

        return redirect()->back()->with(['success' => [__('Card Funded Successfully.')]]);
    }

    // card fund helper
    public function insertCardFund($trx_id, $user, $wallet, $amount, $myCard, $payable, $fixedCharge, $percent_charge, $total_charge)
    {
        $available_balance = ($wallet->balance - $payable);
        $details = [
            'card_info' => $myCard ?? '',
        ];
        DB::beginTransaction();
        try {
            $id = DB::table('transactions')->insertGetId([
                'type' => PaymentGatewayConst::TYPEVIRTUALCARD,
                'trx_id' => $trx_id,
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'request_amount' => $amount,
                'request_currency' => get_default_currency_code(),
                'fixed_charge' => $fixedCharge,
                'percent_charge' => $percent_charge,
                'total_charge' => $total_charge,
                'total_payable' => $payable,
                'available_balance' => $available_balance,
                'remark' => ucwords(PaymentGatewayConst::CARDFUND),
                'details' => json_encode($details),
                'status' => PaymentGatewayConst::STATUSSUCCESS,
                'attribute' => GlobalConst::SEND,
                'created_at' => now(),
            ]);
            $this->updateWalletBalance($wallet, $available_balance);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(__('Something went wrong! Please try again.'));
        }

        return $id;
    }

    /**
     * Transactions
     */
    public function cardTransaction($card_id)
    {
        $user = auth()->user();
        $card = StrowalletVirtualCard::where('user_id', $user->id)->where('card_id', $card_id)->first();
        if (! $card) {
            return back()->with(['error' => [__('Card not found')]]);
        }
        $page_title = __('Virtual Card Transaction');
        $id = $card->card_id;
        $emptyMessage = 'No Transaction Found!';

        // Locally managed cards have no external transaction feed: show the wallet
        // transactions that were created against this card (buy/fund).
        $transactions = Transaction::where('user_id', $user->id)
            ->where('type', PaymentGatewayConst::TYPEVIRTUALCARD)
            ->orderBy('id', 'desc')
            ->get()
            ->filter(function ($trx) use ($card) {
                $details = (array) $trx->details;
                $cardInfo = $details['card_info'] ?? null;
                if (is_object($cardInfo)) {
                    $cardInfo = (array) $cardInfo;
                } elseif (is_string($cardInfo)) {
                    $cardInfo = json_decode($cardInfo, true) ?: [];
                }
                $cardInfo = is_array($cardInfo) ? $cardInfo : [];

                return isset($cardInfo['card_id']) && $cardInfo['card_id'] == $card->card_id;
            })
            ->map(function ($trx) {
                return [
                    'id' => $trx->trx_id,
                    'narrative' => ucwords(str_replace('_', ' ', $trx->remark ?? 'Virtual Card')),
                    'status' => 'success',
                    'amount' => getAmount($trx->request_amount, 2),
                    'currency' => $trx->request_currency,
                    'createdAt' => $trx->created_at->format('Y-m-d'),
                    'method' => 'Virtual Card',
                    'reference' => $trx->trx_id,
                ];
            })
            ->values();

        $data = [
            'status' => true,
            'message' => 'Card Details Retrieved Successfully.',
            'data' => [
                'card_transactions' => $transactions,
            ],
        ];

        return view('user.sections.virtual-card-strowallet.trx', compact('page_title', 'card', 'data'));
    }

    public function apiErrorHandle($apiErrors)
    {
        $error = ['error' => []];
        if (isset($apiErrors)) {
            if (is_array($apiErrors)) {
                foreach ($apiErrors as $field => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $message) {
                            $error['error'][] = $message;
                        }
                    } else {
                        $error['error'][] = $messages;
                    }
                }
            } else {
                $error['error'][] = $apiErrors;
            }
        }
        $errorMessages = array_map(function ($message) {
            return rtrim($message, '.');
        }, $error['error']);

        $errorString = implode(', ', $errorMessages);
        $errorString .= '.';

        return back()->with(['error' => [$errorString ?? __('Something went wrong! Please try again.')]]);
    }
}
