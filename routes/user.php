<?php

use App\Http\Controllers\GlobalController;
use App\Http\Controllers\User\AddMoneyController;
use App\Http\Controllers\User\AuthorizationController;
use App\Http\Controllers\User\BeneficiaryController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\FundTransferController;
use App\Http\Controllers\User\InvestController;
use App\Http\Controllers\User\InvestmentOfferController;
use App\Http\Controllers\User\KycController;
use App\Http\Controllers\User\LoanController;
use App\Http\Controllers\User\LoanPaymentController;
use App\Http\Controllers\User\MoneyOutController;
use App\Http\Controllers\User\PortfolioController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SecurityController;
use App\Http\Controllers\User\SetupPinController;
use App\Http\Controllers\User\StatementController;
use App\Http\Controllers\User\StrowalletVirtualCardController;
use App\Http\Controllers\User\SupportTicketController;
use App\Http\Controllers\User\TransactionController;
use App\Providers\Admin\BasicSettingsProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Pusher\PushNotifications\PushNotifications;

Route::prefix('user')->name('user.')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
        Route::post('logout', 'logout')->name('logout');
        Route::delete('delete/account', 'deleteAccount')->name('delete.account')->middleware('app.mode');
        Route::post('check/pin', 'checkPin')->middleware('auth')->name('check.pin');
    });

    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('password/update', 'passwordUpdate')->name('password.update')->withoutMiddleware('app.mode');
        Route::put('update', 'update')->name('update')->withoutMiddleware('app.mode');
    });

    Route::controller(SupportTicketController::class)->prefix('prefix')->name('support.ticket.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('conversation/{encrypt_id}', 'conversation')->name('conversation');
        Route::post('message/send', 'messageSend')->name('messaage.send');
    });

    Route::controller(AddMoneyController::class)->prefix('add-money')->middleware(['kyc.verification.guard', 'pin.setup.guard'])->name('add.money.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('submit', 'submit')->name('submit');

        Route::get('preview/{token}', 'preview')->name('preview');
        Route::post('preview/submit', 'previewSubmit')->name('preview.submit');

        Route::get('success/response/{gateway}', 'success')->name('payment.success');
        Route::get('cancel/response/{gateway}', 'cancel')->name('payment.cancel');
        Route::post('callback/response/{gateway}', 'callback')->name('payment.callback')->withoutMiddleware(['web', 'auth', 'verification.guard', 'user.google.two.factor']);

        // POST Route For Unauthenticated Request
        Route::post('success/response/{gateway}', 'postSuccess')->name('payment.success.post')->withoutMiddleware(['auth', 'verification.guard', 'user.google.two.factor', 'kyc.verification.guard', 'pin.setup.guard']);
        Route::post('cancel/response/{gateway}', 'postCancel')->name('payment.cancel.post')->withoutMiddleware(['auth', 'verification.guard', 'user.google.two.factor']);

        // redirect with HTML form route
        Route::get('redirect/form/{gateway}', 'redirectUsingHTMLForm')->name('payment.redirect.form')->withoutMiddleware(['auth', 'verification.guard', 'user.google.two.factor', 'kyc.verification.guard', 'pin.setup.guard']);

        // redirect with Btn Pay
        Route::get('redirect/btn/checkout/{gateway}', 'redirectBtnPay')->name('payment.btn.pay')->withoutMiddleware(['auth', 'verification.guard', 'user.google.two.factor', 'kyc.verification.guard', 'pin.setup.guard']);

        Route::post('authorize/payment/submit', 'authorizePaymentSubmit')->name('authorize.payment.submit')->withoutMiddleware(['auth', 'verification.guard', 'user.google.two.factor']);

        Route::get('manual/{token}', 'showManualForm')->name('manual.form');
        Route::post('manual/submit/{token}', 'manualSubmit')->name('manual.submit');

        Route::get('manual/preview/{token}', 'manualPreview')->name('manual.preview');
        Route::post('manual/preview/submit', 'manualPreviewSubmit')->name('manual.preview.submit');

        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('crypto/address/{trx_id}', 'cryptoPaymentAddress')->name('crypto.address');
            Route::post('crypto/confirm/{trx_id}', 'cryptoPaymentConfirm')->name('crypto.confirm');
        });
    });

    // Crypto Deposit Routes
    Route::controller(\App\Http\Controllers\User\CryptoDepositController::class)->middleware(['kyc.verification.guard', 'pin.setup.guard', 'deposit.gate:crypto'])->prefix('crypto-deposit')->name('crypto.deposit.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('address', 'address')->name('address');
        Route::get('confirm', 'confirm')->name('confirm');
        Route::post('submit', 'submit')->name('submit');
        Route::get('success/{id}', 'success')->name('success');
    });

    Route::controller(BeneficiaryController::class)->middleware(['kyc.verification.guard', 'pin.setup.guard'])->prefix('beneficiary')->name('beneficiary.')->group(function () {
        Route::get('list', 'index')->name('index');
        Route::get('add/{type?}', 'create')->name('create');
        Route::post('submit/{type?}', 'submit')->name('submit');
        Route::get('preview/{temp_token}/{type?}', 'preview')->name('preview');
        Route::post('confirm/{type?}', 'confirm')->name('confirm');
        Route::delete('delete', 'delete')->name('delete');
        Route::post('search/{type?}', 'search')->name('search');
        Route::post('get/create-input', 'getTrxTypeInputs')->name('create.get.input');
        Route::post('get/create-input/sub', 'getTrxTypeSubInputs')->name('create.get.sub.input');
        Route::post('get/bank-branch', 'getBankBranch')->name('get.bank.branch');
    });

    Route::controller(FundTransferController::class)->prefix('fund-transfer')->middleware(['kyc.verification.guard', 'pin.setup.guard'])->name('fund-transfer.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/beneficiary/select', 'beneficiarySelect')->name('beneficiary.select');
        Route::get('/create/{token}', 'create')->name('create');
        Route::post('/submit', 'submit')->name('submit');
        Route::get('/preview/{token}', 'preview')->name('preview');
        Route::post('preview/submit', 'previewSubmit')->name('preview.submit');
        Route::get('transaction/success/{trx_id}', 'transactionSuccess')->name('transaction.success');
        Route::get('pdf/download/{trx_id}', 'pdfDownload')->name('pdf.download');
    });

    // strowallet virtual card
    // Deposit-locked card page
    Route::get('cards/locked', function () {
        return view('user.sections.virtual-card-strowallet.locked');
    })->name('strowallet.virtual.card.locked');

    Route::controller(StrowalletVirtualCardController::class)->middleware(['kyc.verification.guard', 'pin.setup.guard'])->prefix('strowallet-virtual-card')->name('strowallet.virtual.card.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'createPage')->name('create')->middleware('kyc.verification.guard');
        Route::post('create/customer', 'createCustomer')->name('create.customer')->middleware('kyc.verification.guard');
        Route::get('edit/customer', 'editCustomer')->name('edit.customer')->middleware('kyc.verification.guard');
        Route::put('update/customer', 'updateCustomer')->name('update.customer')->middleware('kyc.verification.guard');

        Route::post('create', 'cardBuy')->name('store')->middleware(['kyc.verification.guard', 'deposit.gate:card']);
        Route::post('fund', 'cardFundConfirm')->name('fund')->middleware('kyc.verification.guard');
        Route::get('details/{card_id}', 'cardDetails')->name('details');
        Route::get('transaction/{card_id}', 'cardTransaction')->name('transaction');
        Route::put('change/status', 'cardBlockUnBlock')->name('change.status');
        Route::post('cancel', 'cardCancel')->name('cancel');
        Route::post('cvv', 'cardCvv')->name('cvv');
        Route::post('make/default/remove/default', 'makeDefaultOrRemove')->name('make.default.or.remove');
    });

    // Loans
    Route::controller(LoanController::class)->middleware(['kyc.verification.guard', 'pin.setup.guard'])->prefix('loans')->name('loans.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::get('schedule/{id}', 'schedule')->name('schedule');
        Route::post('withdraw-earnings/{id}', 'withdrawEarnings')->name('withdraw.earnings');
        Route::get('stats/json', 'stats')->name('stats');
    });
    Route::controller(LoanPaymentController::class)->middleware(['kyc.verification.guard', 'pin.setup.guard'])->prefix('loans')->name('loans.')->group(function () {
        Route::post('pay-next', 'payNext')->name('pay.next');
    });

    // Investment Portfolios
    Route::controller(PortfolioController::class)->middleware(['kyc.verification.guard', 'pin.setup.guard'])->prefix('portfolios')->name('portfolios.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::delete('delete/{id}', 'destroy')->name('delete');
        Route::post('{id}/holding/add', 'addHolding')->name('holding.add');
        Route::delete('{id}/holding/remove', 'removeHolding')->name('holding.remove');
        Route::get('{id}/performance/json', 'performance')->name('performance');
    });

    Route::controller(InvestmentOfferController::class)->middleware(['kyc.verification.guard', 'pin.setup.guard'])->prefix('investments')->name('investments.')->group(function () {
        Route::get('offers', 'index')->name('offers');
    });

    // Crypto Investment Plans
    Route::controller(InvestController::class)->middleware(['kyc.verification.guard', 'pin.setup.guard'])->prefix('invest')->name('invest.')->group(function () {
        Route::get('new', 'newPlan')->name('new');
        Route::post('deposit', 'deposit')->name('deposit');
        Route::post('submit-proof', 'submitProof')->name('submit.proof');
        Route::get('confirmation/{id}', 'confirmation')->name('confirmation');
        Route::get('portfolio', 'portfolio')->name('portfolio');
        Route::get('earnings', 'earnings')->name('earnings');
    });

    // Transaction
    Route::controller(TransactionController::class)->prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/{slug?}', 'index')->name('index')->whereIn('slug', ['add-money', 'money-out', 'transfer-money', 'money-exchange']);
        Route::post('search', 'search')->name('search');
        Route::get('download/{sd_id}', 'download')->name('download');
    });

    Route::controller(StatementController::class)->prefix('statements')->name('statements.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/filter', 'filterStatement')->name('filter');
        Route::get('/export', 'export')->name('export');
    });

    // setup pin
    Route::controller(SetupPinController::class)->prefix('setup-pin')->name('setup.pin.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update', 'update')->name('update');
    });

    Route::controller(SecurityController::class)->prefix('security')->name('security.')->group(function () {
        Route::get('google/2fa', 'google2FA')->name('google.2fa');
        Route::post('google/2fa/status/update', 'google2FAStatusUpdate')->name('google.2fa.status.update');
    });

    Route::controller(KycController::class)->prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('submit', 'store')->name('submit');
    });

    // Deposit-locked withdrawal page
    Route::get('withdrawal/locked', function () {
        $user = auth()->user();
        $total_deposits = $user
            ? \App\Models\Transaction::where('user_id', $user->id)
                ->where('type', \App\Constants\PaymentGatewayConst::TYPEADDMONEY)
                ->where('status', \App\Constants\PaymentGatewayConst::STATUSSUCCESS)
                ->sum('request_amount')
            : 0;

        return view('user.sections.money-out.locked', compact('user', 'total_deposits'));
    })->name('money-out.locked');

    Route::controller(MoneyOutController::class)->middleware(['kyc.verification.guard', 'pin.setup.guard'])->prefix('money-out')->name('money-out.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('submit', 'submit')->name('submit');
        Route::post('international/submit', 'internationalSubmit')->name('international.submit');
        Route::post('crypto/submit', 'cryptoSubmit')->name('crypto.submit');
        Route::get('instruction/{token}', 'instruction')->name('instruction');
        Route::post('instruction/submit/{token}', 'instructionSubmit')->name('instruction.submit');
        Route::get('transaction/success/{trx_id}', 'transactionSuccess')->name('transaction.success');
    });
    // OTP Verification Mail/SMS Send
    Route::controller(AuthorizationController::class)->prefix('verification-code')->name('verification-code.')->group(function () {
        Route::get('send', 'verificationCodeSend')->name('send');
        Route::get('resend', 'verificationCodeResend')->name('resend');
        Route::post('check', 'verificationCodeCheck')->name('check');
    });

    Route::post('info/account', [GlobalController::class, 'userInfoAccount'])->name('info.account');

    // Rise Dashboard Routes
    Route::controller(\App\Http\Controllers\User\RiseController::class)->prefix('rise')->name('rise.')->group(function () {
        Route::get('home', 'home')->name('home');
        Route::get('invest', 'invest')->name('invest');
        Route::get('wallet', 'wallet')->name('wallet');
        Route::get('feed', 'feed')->name('feed');
        Route::get('feed/data', 'feedData')->name('feed.data');
        Route::get('feed/{slug}', 'articleDetail')->name('feed.detail');
        Route::get('account', 'account')->name('account');
        Route::get('refer', 'refer')->name('refer');
        Route::get('send', 'send')->name('send');
        Route::post('send/submit', 'sendSubmit')->name('send.submit');
        Route::get('withdraw/crypto', 'cryptoWithdraw')->name('withdraw.crypto')->middleware('deposit.gate:withdrawal');
    });

    Route::controller(\App\Http\Controllers\User\BankDetailsController::class)->prefix('bank-details')->name('bank.details.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::put('update/{id}', 'update')->name('update');
        Route::delete('destroy/{id}', 'destroy')->name('destroy');
        Route::put('toggle/{id}', 'toggleStatus')->name('toggle');
    });

    // User Notifications
    Route::prefix('notifications')->name('notifications.')->controller(\App\Http\Controllers\User\NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('read/{notification}', 'read')->name('read');
        Route::post('read-all', 'readAll')->name('readAll');
        Route::get('show/{notification}', 'show')->name('show');
    });

});

// Route For Pusher Beams Auth
Route::get('user/pusher/beams-auth', function (Request $request) {
    if (Auth::check() == false) {
        return response(['Inconsistent request'], 401);
    }
    $userID = Auth::user()->id;

    $basic_settings = BasicSettingsProvider::get();
    if (! $basic_settings) {
        return response('Basic setting not found!', 404);
    }

    $notification_config = $basic_settings->push_notification_config;

    if (! $notification_config) {
        return response('Notification configuration not found!', 404);
    }

    $instance_id = $notification_config->instance_id ?? null;
    $primary_key = $notification_config->primary_key ?? null;
    if ($instance_id == null || $primary_key == null) {
        return response('Sorry! You have to configure first to send push notification.', 404);
    }
    $beamsClient = new PushNotifications(
        [
            'instanceId' => $notification_config->instance_id,
            'secretKey' => $notification_config->primary_key,
        ]
    );

    $get_full_host_path = remove_special_char(get_full_url_host(), '-');

    $publisherUserId = $get_full_host_path.'-user-'.$userID;
    try {
        $beamsToken = $beamsClient->generateToken($publisherUserId);
    } catch (Exception $e) {
        return response(['Server Error. Failed to generate beams token.'], 500);
    }

    return response()->json($beamsToken);
})->name('user.pusher.beams.auth');
