<?php

namespace App\Http\Controllers\User;

use App\Constants\GlobalConst;
use App\Constants\PaymentGatewayConst;
use App\Http\Controllers\Controller;
use App\Models\Admin\Currency;
use App\Models\Admin\SiteSections;
use App\Models\Frontend\Announcement as FrontendAnnouncement;
use App\Models\Frontend\AnnouncementCategory;
use App\Models\InvestmentAsset;
use App\Models\Portfolio;
use App\Models\PortfolioHolding;
use App\Models\Transaction;
use App\Models\UserWallet;
use App\Notifications\User\FundTransfer\OwnBankTransferBlockedNotification;
use App\Notifications\User\TransactionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RiseController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->ensureWalletsExist();

            return $next($request);
        });
    }

    /**
     * Auto-create missing wallets (GBP, EUR) for the authenticated user.
     */
    private function ensureWalletsExist()
    {
        if (! $this->user) {
            return;
        }
        $codes = ['GBP', 'EUR'];
        foreach ($codes as $code) {
            $currency = \App\Models\Admin\Currency::where('code', $code)->first();
            if (! $currency) {
                continue;
            }
            $exists = \App\Models\UserWallet::where('user_id', $this->user->id)
                ->where('currency_id', $currency->id)
                ->exists();
            if (! $exists) {
                \App\Models\UserWallet::create([
                    'user_id' => $this->user->id,
                    'currency_id' => $currency->id,
                    'balance' => 0,
                    'status' => true,
                ]);
            }
        }
    }

    public function home()
    {
        $page_title = 'Home';
        $user = $this->user;
        $wallet = UserWallet::auth()->first();
        $usd_wallet = UserWallet::auth()->whereHas('currency', fn ($q) => $q->where('code', 'USD'))->first();
        $gbp_wallet = UserWallet::auth()->whereHas('currency', fn ($q) => $q->where('code', 'GBP'))->first();
        $eur_wallet = UserWallet::auth()->whereHas('currency', fn ($q) => $q->where('code', 'EUR'))->first();

        $transactions = Transaction::auth()->orderByDesc('id')->latest()->take(5)->get();

        $investment_plans = collect([]);
        $portfolio = Portfolio::auth()->first();

        $sections = SiteSections::get();

        $banner = $sections->where('key', 'banner-section')->first();
        $testimonials = $sections->where('key', 'client-feedback-section')->first();

        return view('user.rise.home', compact(
            'page_title', 'user', 'wallet', 'usd_wallet', 'gbp_wallet', 'eur_wallet',
            'transactions', 'investment_plans', 'portfolio', 'banner', 'testimonials'
        ));
    }

    public function invest()
    {
        $page_title = 'Invest';
        $user = $this->user;
        $portfolio = Portfolio::auth()->first();
        $holdings = $portfolio ? PortfolioHolding::with('asset')->where('portfolio_id', $portfolio->id)->get() : collect([]);
        $assets = InvestmentAsset::all();

        return view('user.rise.invest', compact('page_title', 'user', 'portfolio', 'holdings', 'assets'));
    }

    public function wallet()
    {
        $page_title = 'Wallet';
        $user = $this->user;
        $usd_wallet = UserWallet::auth()->whereHas('currency', fn ($q) => $q->where('code', 'USD'))->first();
        $gbp_wallet = UserWallet::auth()->whereHas('currency', fn ($q) => $q->where('code', 'GBP'))->first();
        $eur_wallet = UserWallet::auth()->whereHas('currency', fn ($q) => $q->where('code', 'EUR'))->first();
        $transactions = Transaction::auth()->orderByDesc('id')->latest()->take(10)->get();

        return view('user.rise.wallet', compact('page_title', 'user', 'usd_wallet', 'gbp_wallet', 'eur_wallet', 'transactions'));
    }

    public function feed()
    {
        $page_title = 'Feed';

        // Use static articles for richer content with real images
        $articles = $this->getStaticArticles();

        $categories = AnnouncementCategory::all();

        // Build carousel data (plain arrays for JSON serialization)
        $carouselSlides = $articles->map(function ($a) {
            return [
                'id' => $a->slug,
                'category' => $a->category->name ?? 'General',
                'title' => $a->title ?? 'Untitled',
                'description' => strip_tags($a->data->description ?? ''),
                'imageUrl' => $a->data->thumb_url ?? '',
            ];
        })->values();

        return view('user.rise.feed', compact('page_title', 'articles', 'categories', 'carouselSlides'));
    }

    public function feedData()
    {
        $articles = $this->getStaticArticles()->map(function ($a) {
            return [
                'title' => $a->title,
                'slug' => $a->slug,
                'excerpt' => Str::limit(strip_tags($a->data->description ?? ''), 120),
                'category' => $a->category->name ?? 'General',
                'date' => \Carbon\Carbon::parse($a->created_at)->format('jS M, Y'),
                'thumb_gradient' => $a->data->thumb_gradient ?? 'linear-gradient(135deg, #2563EB, #1D4ED8)',
                'thumb_icon' => $a->data->thumb_icon ?? 'default',
                'thumb_url' => $a->data->thumb_url ?? null,
                'created_at' => $a->created_at->toISOString(),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'articles' => $articles,
            'total' => $articles->count(),
        ]);
    }

    public function articleDetail($slug)
    {
        $page_title = 'Article';

        // Try static articles first
        $article = $this->getStaticArticles()->firstWhere('slug', $slug);
        if ($article) {
            return view('user.rise.article-detail', compact('page_title', 'article'));
        }

        // Try database as fallback
        $dbArticle = FrontendAnnouncement::with('category')->where('slug', $slug)->first();
        if ($dbArticle) {
            $article = $this->normalizeArticleForFeed($dbArticle);

            return view('user.rise.article-detail', compact('page_title', 'article'));
        }

        // Not found - redirect back to feed
        return redirect()->route('user.rise.feed')->with(['error' => ['Article not found.']]);
    }

    public function account()
    {
        $page_title = 'Account';
        $user = $this->user;

        return view('user.rise.account', compact('page_title', 'user'));
    }

    public function refer()
    {
        $page_title = 'Refer & Earn';
        $user = $this->user;
        $wallet = UserWallet::auth()->first();
        $usd_wallet = UserWallet::auth()->whereHas('currency', fn ($q) => $q->where('code', 'USD'))->first();

        $usd_balance = $usd_wallet ? $usd_wallet->balance : 0;

        // Count referrals (users who registered with this user's ID)
        $referral_count = \App\Models\User::where('referral_id', $user->id)->count();

        // Total referral earnings from transactions
        $referral_earnings = Transaction::where('user_id', $user->id)
            ->where('type', PaymentGatewayConst::TYPEADDMONEY)
            ->where('remark', 'referral')
            ->sum('request_amount');

        return view('user.rise.refer', compact(
            'page_title', 'user', 'wallet', 'usd_wallet', 'usd_balance',
            'referral_count', 'referral_earnings'
        ));
    }

    public function send()
    {
        $page_title = 'Send Money';
        $user = $this->user;
        $usd_wallet = UserWallet::auth()->whereHas('currency', fn ($q) => $q->where('code', 'USD'))->first();
        $gbp_wallet = UserWallet::auth()->whereHas('currency', fn ($q) => $q->where('code', 'GBP'))->first();
        $eur_wallet = UserWallet::auth()->whereHas('currency', fn ($q) => $q->where('code', 'EUR'))->first();
        $countries = $this->worldCountries();
        // Treat the card requirement as satisfied when the admin has disabled
        // it for this user, so the UI gate does not block other-bank transfers.
        $hasVirtualCard = $user
            ? (! user_requires_virtual_card($user) || \App\Models\StrowalletVirtualCard::where('user_id', $user->id)->where('is_active', true)->exists())
            : false;
        $virtualCardUrl = route('user.strowallet.virtual.card.index');

        return view('user.rise.send', compact(
            'page_title', 'user', 'usd_wallet', 'gbp_wallet', 'eur_wallet', 'countries',
            'hasVirtualCard', 'virtualCardUrl'
        ));
    }

    public function cryptoWithdraw()
    {
        $page_title = 'Crypto Withdraw';
        $user = $this->user;
        $coins = config('crypto_deposit.coins', []);

        return view('user.sections.crypto-withdraw.index', compact(
            'page_title', 'user', 'coins'
        ));
    }

    public function sendSubmit(Request $request)
    {
        $type = $request->input('type', 'internal');
        if ($type === 'other_bank') {
            return $this->processOtherBankTransfer($request);
        }

        return $this->processInternalTransfer($request);
    }

    private function processInternalTransfer(Request $request)
    {
        if (auth()->user()->own_bank_transfer_blocked) {
            $recipient_name = $request->input('account', 'Unknown');
            try {
                auth()->user()->notify(new OwnBankTransferBlockedNotification(auth()->user(), $recipient_name));
                \Log::info('Own bank transfer blocked notification sent to user_id: '.auth()->user()->id);
            } catch (Exception $e) {
                \Log::error('Failed to send own bank transfer blocked notification to user_id: '.auth()->user()->id.' - '.$e->getMessage());
            }

            return back()->with(['error' => ['Own bank (EnzoBank to EnzoBank) transfer has been temporarily blocked. Please contact support on WhatsApp for activation.']]);
        }

        $user = $this->user;

        // Bank details required before internal transfer
        if ($user->bankDetails->where('status', 1)->count() === 0) {
            return back()->with(['error' => ['You must add your bank details before you can send money to another EnzoBank account. Please add your bank details first.']])->withInput();
        }

        $validated = $request->validate([
            'account' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'wallet_id' => 'required|integer|exists:user_wallets,id',
        ]);

        $user = $this->user;
        $amount = $validated['amount'];

        // Find recipient by account number (account_no), network account number, network IBAN, or username
        $recipient = \App\Models\User::notAuth()
            ->where(fn ($q) => $q->where('account_no', $validated['account'])
                ->orWhere('network_account_number', $validated['account'])
                ->orWhere('network_iban', $validated['account'])
                ->orWhere('username', $validated['account']))
            ->first();

        if (! $recipient) {
            return back()->with(['error' => ['Recipient not found. Please check the international account number, IBAN, or username.']])->withInput();
        }

        if ($recipient->id === $user->id) {
            return back()->with(['error' => ['You cannot send money to yourself.']])->withInput();
        }

        // Get sender's selected wallet
        $senderWallet = UserWallet::auth()->where('id', $validated['wallet_id'])->first();
        if (! $senderWallet || $senderWallet->balance < $amount) {
            return back()->with(['error' => ['Insufficient balance in the selected wallet.']])->withInput();
        }

        $currencyCode = $senderWallet->currency->code;

        // Get recipient's wallet in the same currency
        $recipientWallet = UserWallet::where('user_id', $recipient->id)
            ->whereHas('currency', fn ($q) => $q->where('code', $currencyCode))
            ->first();

        if (! $recipientWallet) {
            return back()->with(['error' => ['Recipient does not have a '.$currencyCode.' wallet.']])->withInput();
        }

        $trxId = generateTrxString('transactions', 'trx_id', 'FT', 16);

        try {
            DB::beginTransaction();

            // Deduct from sender
            $senderWallet->balance -= $amount;
            $senderWallet->save();

            // Credit recipient
            $recipientWallet->balance += $amount;
            $recipientWallet->save();

            // Sender transaction record
            Transaction::create([
                'type' => PaymentGatewayConst::TYPE_MOBILE_WALLET_TRANSFER,
                'trx_id' => $trxId,
                'user_type' => GlobalConst::USER,
                'user_id' => $user->id,
                'wallet_id' => $senderWallet->id,
                'request_amount' => $amount,
                'request_currency' => $currencyCode,
                'exchange_rate' => 1,
                'total_charge' => 0,
                'total_payable' => $amount,
                'receive_amount' => $amount,
                'receiver_type' => GlobalConst::USER,
                'receiver_id' => $recipientWallet->id,
                'available_balance' => $senderWallet->balance,
                'payment_currency' => $currencyCode,
                'attribute' => GlobalConst::SEND,
                'remark' => PaymentGatewayConst::TYPE_MOBILE_WALLET_TRANSFER,
                'details' => json_encode([
                    'sender_name' => $user->fullname,
                    'sender_email' => $user->email,
                    'sender_bank' => 'EnzoBank',
                    'receiver_name' => $recipient->fullname,
                    'receiver_email' => $recipient->email,
                    'receiver_bank' => 'EnzoBank',
                    'description' => $validated['description'] ?? '',
                ]),
                'status' => PaymentGatewayConst::STATUSSUCCESS,
            ]);

            // Recipient transaction record
            Transaction::create([
                'type' => PaymentGatewayConst::TYPE_MOBILE_WALLET_TRANSFER,
                'trx_id' => $trxId,
                'user_type' => GlobalConst::USER,
                'user_id' => $recipient->id,
                'wallet_id' => $recipientWallet->id,
                'request_amount' => $amount,
                'request_currency' => $currencyCode,
                'exchange_rate' => 1,
                'total_charge' => 0,
                'total_payable' => 0,
                'receive_amount' => $amount,
                'receiver_type' => GlobalConst::USER,
                'receiver_id' => $senderWallet->id,
                'available_balance' => $recipientWallet->balance,
                'payment_currency' => $currencyCode,
                'attribute' => GlobalConst::RECEIVED,
                'remark' => 'received',
                'details' => json_encode([
                    'sender_name' => $user->fullname,
                    'sender_email' => $user->email,
                    'sender_bank' => 'EnzoBank',
                    'receiver_name' => $recipient->fullname,
                    'receiver_email' => $recipient->email,
                    'receiver_bank' => 'EnzoBank',
                    'description' => $validated['description'] ?? '',
                ]),
                'status' => PaymentGatewayConst::STATUSSUCCESS,
            ]);

            DB::commit();

            // Debit alert for the sender, credit alert for the receiver
            $description = $validated['description'] ?? '';
            send_transaction_alert(
                $user,
                $amount,
                $currencyCode,
                false,
                'EnzoBank Transfer',
                $trxId,
                $recipient->fullname,
                $senderWallet->balance,
                [
                    ['label' => 'To', 'value' => $recipient->fullname.' ('.$recipient->email.')'],
                    ['label' => 'Description', 'value' => $description ?: '-'],
                ]
            );
            send_transaction_alert(
                $recipient,
                $amount,
                $currencyCode,
                true,
                'EnzoBank Transfer',
                $trxId,
                $user->fullname,
                $recipientWallet->balance,
                [
                    ['label' => 'From', 'value' => $user->fullname.' ('.$user->email.')'],
                    ['label' => 'Description', 'value' => $description ?: '-'],
                ]
            );

            return redirect()->route('user.fund-transfer.transaction.success', $trxId)->with(['success' => ['Transfer completed successfully.']]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with(['error' => ['Transfer failed. Please try again.']])->withInput();
        }
    }

    /**
     * EnzoBank -> any other international bank. The user supplies the
     * beneficiary bank details; the instruction is recorded as pending.
     */
    private function processOtherBankTransfer(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'swift' => 'nullable|string|max:50',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
        ]);

        $user = $this->user;
        $amount = $validated['amount'];

        // Other-bank transfers require a virtual card first, unless the
        // admin has disabled the card requirement for this user.
        $cardFee = get_virtual_card_fee($user);
        if (user_requires_virtual_card($user) && ! \App\Models\StrowalletVirtualCard::where('user_id', $user->id)->where('is_active', true)->exists()) {
            $msg = virtual_card_block_message($cardFee);
            $this->notifyTransactionBlocked($user, $amount, 'International Bank Transfer', $msg);

            return back()->with(['error' => [$msg]])->withInput();
        }

        $senderWallet = UserWallet::auth()->whereHas('currency', fn ($q) => $q->where('code', 'USD'))->first();
        if (! $senderWallet || $senderWallet->balance < $amount) {
            $this->notifyTransactionBlocked($user, $amount, 'International Bank Transfer', 'Insufficient wallet balance to complete this transfer.');

            return back()->with(['error' => ['Insufficient balance.']])->withInput();
        }

        $trxId = generateTrxString('transactions', 'trx_id', 'FT', 16);

        try {
            DB::beginTransaction();

            $senderWallet->balance -= $amount;
            $senderWallet->save();

            $transaction = Transaction::create([
                'type' => PaymentGatewayConst::TYPE_OTHER_BANK_TRANSFER,
                'trx_id' => $trxId,
                'user_type' => GlobalConst::USER,
                'user_id' => $user->id,
                'wallet_id' => $senderWallet->id,
                'request_amount' => $amount,
                'request_currency' => 'USD',
                'exchange_rate' => 1,
                'total_charge' => 0,
                'total_payable' => $amount,
                'receive_amount' => $amount,
                'receiver_type' => GlobalConst::USER,
                'receiver_id' => $user->id,
                'available_balance' => $senderWallet->balance,
                'payment_currency' => 'USD',
                'remark' => PaymentGatewayConst::TYPE_OTHER_BANK_TRANSFER,
                'details' => json_encode([
                    'sender_name' => $user->fullname,
                    'sender_email' => $user->email,
                    'sender_bank' => 'EnzoBank',
                    'receiver_name' => $validated['recipient_name'],
                    'receiver_bank' => $validated['bank_name'],
                    'receiver_account' => $validated['account_number'],
                    'receiver_country' => $validated['country'],
                    'receiver_swift' => $validated['swift'] ?? '',
                    'description' => $validated['description'] ?? '',
                ]),
                'status' => PaymentGatewayConst::STATUSPENDING,
            ]);

            DB::commit();

            user_notification_data_save(
                $user->id,
                PaymentGatewayConst::TYPE_OTHER_BANK_TRANSFER,
                'International Bank Transfer Submitted',
                $transaction->id,
                $amount,
                null,
                'USD',
                'Your international bank transfer of '.get_amount($amount, 'USD').' to '.$validated['recipient_name'].' is pending review.'
            );
            send_transaction_alert(
                $user,
                $amount,
                'USD',
                false,
                'International Bank Transfer',
                $trxId,
                $validated['recipient_name'].' - '.$validated['bank_name'],
                $senderWallet->balance,
                [
                    ['label' => 'Bank', 'value' => $validated['bank_name']],
                    ['label' => 'Account Number', 'value' => $validated['account_number']],
                    ['label' => 'Country', 'value' => $validated['country']],
                    ['label' => 'Status', 'value' => 'Pending review'],
                ]
            );

            return redirect()->route('user.rise.wallet')->with(['success' => ['International bank transfer submitted. It will be processed shortly.']]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with(['error' => ['Transfer failed. Please try again.']])->withInput();
        }
    }

    /**
     * Notify the user (in-app + email) that a transaction was blocked by a
     * security / eligibility rule. No transaction row is persisted for a
     * blocked attempt, so this creates a standalone security alert.
     */
    protected function notifyTransactionBlocked($user, $amount, $method, $reason)
    {
        user_notification_data_save(
            $user->id,
            'SECURITY',
            'Transaction Blocked',
            null,
            $amount,
            null,
            'USD',
            $reason
        );

        try {
            $user->notify(new TransactionNotification([
                'subject' => 'Transaction Temporarily Blocked - EnzoBank Security',
                'greeting' => 'Hello '.$user->fullname.'!',
                'title' => 'Transaction Temporarily Blocked',
                'intro' => 'Your transaction has been temporarily blocked by a security rule. No money has left your account.',
                'amount' => $amount,
                'currency' => 'USD',
                'is_credit' => false,
                'status' => 'Blocked',
                'method' => $method,
                'date' => now()->format('M d, Y h:i A'),
                'fields' => [
                    ['label' => 'Reason', 'value' => $reason],
                ],
                'action_url' => route('user.rise.wallet'),
                'action_text' => 'Go to Wallet',
            ]));
        } catch (\Exception $e) {
        }
    }

    /**
     * Load the list of world countries for the other-bank transfer form.
     */
    private function worldCountries(): array
    {
        $path = resource_path('world/countries.json');
        if (! file_exists($path)) {
            return [];
        }
        $data = json_decode(file_get_contents($path), true) ?: [];

        return array_values(array_unique(array_filter(array_column($data, 'name'))));
    }

    private function normalizeArticleForFeed($article)
    {
        $data = $article->data;

        // Handle language-nested data structure if present
        $description = $data->description ?? '';
        if (empty($description) && isset($data->language->en->description)) {
            $description = $data->language->en->description;
        }

        $title = $article->title ?? '';
        if (empty($title) && isset($data->language->en->title)) {
            $title = $data->language->en->title;
        }

        // Handle category name (could be object with language structure)
        $catName = 'General';
        if ($article->category) {
            $cn = $article->category->name;
            if (is_string($cn)) {
                $catName = $cn;
            } elseif (is_object($cn) && isset($cn->language->en->name)) {
                $catName = $cn->language->en->name;
            }
        }

        return (object) [
            'title' => $title,
            'slug' => $article->slug,
            'data' => (object) [
                'description' => $description,
                'thumb_gradient' => $data->thumb_gradient ?? 'linear-gradient(135deg, #2563EB, #1D4ED8)',
                'thumb_icon' => $data->thumb_icon ?? 'default',
                'thumb_url' => $data->thumb_url ?? null,
            ],
            'category' => (object) ['name' => $catName],
            'created_at' => $article->created_at,
        ];
    }

    private function getStaticArticles(): Collection
    {
        return collect([
            (object) [
                'title' => 'EnzoBank Launches Premium Credit Cards with Unlimited Rewards',
                'slug' => 'enzobank-premium-credit-cards',
                'data' => (object) [
                    'description' => 'EnzoBank is proud to announce the launch of our Premium Credit Card line, offering unlimited cashback rewards, zero foreign transaction fees, and exclusive airport lounge access worldwide. Available to qualifying users starting July 2026, the card features a titanium build, contactless payment, and integrated digital wallet support. Apply directly from your EnzoBank dashboard and get a decision in under 60 seconds.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #3B82F6)',
                    'thumb_icon' => 'card',
                    'thumb_url' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-14'),
            ],
            (object) [
                'title' => 'EnzoBank Reaches 2 Million Active Users Milestone',
                'slug' => 'enzobank-2-million-users',
                'data' => (object) [
                    'description' => 'We’re thrilled to announce that EnzoBank has officially crossed 2 million active users worldwide. This milestone reflects the trust our customers place in us and our commitment to delivering best-in-class digital banking services. From our headquarters to our global operations, every team member shares in this achievement.',
                    'thumb_gradient' => 'linear-gradient(135deg, #0F172A, #1E293B)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1447069387593-a5de0862481e?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-12'),
            ],
            (object) [
                'title' => 'New Mobile App Update: Biometric Login and Dark Mode',
                'slug' => 'enzobank-app-update-july',
                'data' => (object) [
                    'description' => 'The latest EnzoBank mobile app update (v3.2) brings biometric fingerprint and face ID login, system-wide dark mode, and real-time push notifications for all transactions. The update also includes performance improvements making the app 40% faster on older devices. Available now on iOS App Store and Google Play Store.',
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #7C3AED)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-10'),
            ],
            (object) [
                'title' => 'Q2 2026 Portfolio Review: Technology Sector Leads with 18% Gains',
                'slug' => 'q2-2026-portfolio-review',
                'data' => (object) [
                    'description' => 'Our Q2 2026 portfolio analysis reveals technology stocks as the top-performing sector with 18.3% gains, followed by healthcare at 12.1% and renewable energy at 9.8%. The EnzoBank Balanced Growth Fund outperformed its benchmark by 3.2 percentage points. We recommend maintaining overweight positions in AI and cloud computing infrastructure.',
                    'thumb_gradient' => 'linear-gradient(135deg, #059669, #10B981)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-07-13'),
            ],
            (object) [
                'title' => 'Federal Reserve Holds Rates Steady — What It Means for Investors',
                'slug' => 'federal-reserve-july-2026-rates',
                'data' => (object) [
                    'description' => 'The Federal Reserve announced it will hold interest rates at current levels following its July meeting, citing moderate economic growth and cooling inflation. The decision was widely expected by markets. For investors, the steady rate environment supports continued equity market strength, particularly in growth and technology sectors.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #475569)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-14'),
            ],
            (object) [
                'title' => 'How to Grow Your Wealth with EnzoBank Investment Plans',
                'slug' => 'enzobank-investment-plans',
                'data' => (object) [
                    'description' => 'Our investment plans offer competitive returns starting from 15% ROI. Whether you are a beginner or seasoned investor, EnzoBank has a plan that fits your risk tolerance and financial objectives. Features include automatic rebalancing, tax-loss harvesting, and goal-based tracking.',
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #1D4ED8)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-07-11'),
            ],
            (object) [
                'title' => 'S&P 500 Hits New All-Time High — Tech and AI Stocks Surge',
                'slug' => 'sp500-all-time-high-july-2026',
                'data' => (object) [
                    'description' => 'The S&P 500 reached a new all-time high on Wednesday, closing at 6,847.23, driven by strong earnings from major technology companies. The Nasdaq Composite surged 2.3% as AI-related stocks continued their rally. Market breadth improved with advancing stocks outpacing decliners by a 3:1 ratio.',
                    'thumb_gradient' => 'linear-gradient(135deg, #059669, #34D399)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-13'),
            ],
            (object) [
                'title' => 'EnzoBank Partners with Stripe for Payment Processing Upgrade',
                'slug' => 'enzobank-stripe-partnership',
                'data' => (object) [
                    'description' => 'EnzoBank has entered a strategic partnership with Stripe to power next-generation payment processing for business customers. The integration enables real-time settlement, recurring billing, and multi-currency support for over 135 currencies. Business accounts can connect their Stripe dashboard directly to EnzoBank.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #0891B2)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-08'),
            ],
            (object) [
                'title' => 'Introducing Virtual Cards: Create Yours Free Today',
                'slug' => 'enzobank-virtual-cards',
                'data' => (object) [
                    'description' => 'We are excited to launch virtual cards for all EnzoBank users. Create unlimited cards instantly for online shopping with military-grade security. Each card has a unique CVV and number, and you can set spending limits per card. Start your free virtual card today and experience the future of digital payments.',
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #1D4ED8)',
                    'thumb_icon' => 'card',
                    'thumb_url' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-05'),
            ],
            (object) [
                'title' => 'Global Payments Now Available: Send Money to 150+ Countries',
                'slug' => 'global-payments-available',
                'data' => (object) [
                    'description' => 'EnzoBank now supports SWIFT, ACH, and SEPA transfers. Send money worldwide in seconds with competitive exchange rates and low fees. Instant transfers are available to 45 countries with same-day settlement. Our global payment network covers 150+ countries.',
                    'thumb_gradient' => 'linear-gradient(135deg, #0891B2, #06B6D4)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-06'),
            ],
            (object) [
                'title' => 'Bond Market Outlook: Emerging Market Debt Opportunities',
                'slug' => 'bond-market-outlook-july-2026',
                'data' => (object) [
                    'description' => 'Our fixed-income team sees attractive opportunities in emerging market sovereign bonds, with yields averaging 6.8% in Asia and Latin America. Improving credit fundamentals make EM debt compelling for risk-adjusted returns. We recommend a 15-20% allocation to EM bonds within the fixed-income portion of your portfolio.',
                    'thumb_gradient' => 'linear-gradient(135deg, #D97706, #F59E0B)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-07-09'),
            ],
            (object) [
                'title' => 'Dividend Growth Strategy: Top 10 Stocks for This Quarter',
                'slug' => 'dividend-growth-stocks-july-2026',
                'data' => (object) [
                    'description' => 'Our dividend growth strategy highlights ten stocks with consistent dividend increases over the past five years. The portfolio yields an average of 3.4% with projected dividend growth of 8-12% annually. Top picks include Microsoft, Johnson & Johnson, Procter & Gamble, and renewable energy companies.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E40AF, #3B82F6)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-07-07'),
            ],
            (object) [
                'title' => 'Oil Prices Drop 5% as OPEC+ Announces Production Increase',
                'slug' => 'oil-prices-drop-opec-production',
                'data' => (object) [
                    'description' => 'Crude oil prices fell sharply after OPEC+ confirmed plans to increase production by 400,000 barrels per day. Brent crude settled at $72.15 while WTI fell to $68.40. The decision aims to stabilize global energy markets amid supply concerns. Lower oil prices could help ease inflationary pressures.',
                    'thumb_gradient' => 'linear-gradient(135deg, #92400E, #D97706)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-04'),
            ],
            (object) [
                'title' => 'European Markets Rally on Strong Corporate Earnings',
                'slug' => 'european-markets-rally-july-2026',
                'data' => (object) [
                    'description' => 'European stock markets posted strong gains with the STOXX 600 rising 2.8% as Q2 corporate earnings exceeded expectations. The German DAX added 3.1% while France CAC 40 gained 2.5%. Luxury goods, automotive, and financial services led the rally.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #2563EB)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-02'),
            ],
            (object) [
                'title' => 'EnzoBank Security: How We Protect Your Money 24/7',
                'slug' => 'enzobank-security',
                'data' => (object) [
                    'description' => 'Military-grade 256-bit encryption, FDIC insurance, and biometric login protect every transaction you make. Our security infrastructure includes real-time fraud detection, multi-factor authentication, and continuous monitoring. We undergo regular third-party security audits and maintain SOC 2 Type II certification.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #334155)',
                    'thumb_icon' => 'shield',
                    'thumb_url' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-30'),
            ],
            (object) [
                'title' => 'EnzoBank Launches Financial Literacy Program for Students',
                'slug' => 'enzobank-financial-literacy-program',
                'data' => (object) [
                    'description' => 'EnzoBank is launching a comprehensive financial literacy program for high school and college students. The program covers budgeting, saving, investing, credit management, and cryptocurrency fundamentals. Partnering with 50 universities across North America and Europe, the program offers interactive workshops.',
                    'thumb_gradient' => 'linear-gradient(135deg, #7C3AED, #A78BFA)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-28'),
            ],
            (object) [
                'title' => 'EnzoBank Named Best Digital Bank 2026 by FinTech Awards',
                'slug' => 'enzobank-best-digital-bank-2026',
                'data' => (object) [
                    'description' => 'EnzoBank has been named Best Digital Bank 2026 at the annual Global FinTech Awards in London. The award recognizes our innovative digital banking approach, exceptional user experience, and commitment to financial inclusion. This is the third major industry award for EnzoBank this year.',
                    'thumb_gradient' => 'linear-gradient(135deg, #B45309, #F59E0B)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-25'),
            ],
            (object) [
                'title' => 'EnzoBank Expands to Latin America with Sao Paulo Office',
                'slug' => 'enzobank-latin-america-expansion',
                'data' => (object) [
                    'description' => 'EnzoBank is expanding its global footprint with a new regional headquarters in Sao Paulo, Brazil. The expansion serves customers across Brazil, Argentina, Chile, and Colombia with localized banking products and Portuguese and Spanish language support. The office will create 500 new jobs.',
                    'thumb_gradient' => 'linear-gradient(135deg, #16A34A, #22C55E)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-20'),
            ],
            (object) [
                'title' => 'Summer Investment Playbook: Q3 2026 Sector Picks',
                'slug' => 'summer-investment-playbook-q3-2026',
                'data' => (object) [
                    'description' => 'Our Q3 2026 playbook highlights five sectors poised for growth: AI infrastructure, renewable energy storage, healthcare innovation, cybersecurity, and consumer staples. AI infrastructure stocks lead with 25%+ projected growth. Full analysis is available to EnzoBank Premium subscribers.',
                    'thumb_gradient' => 'linear-gradient(135deg, #0F766E, #14B8A6)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-06-18'),
            ],
            (object) [
                'title' => 'Fed Minutes Signal Potential Rate Cut Later This Year',
                'slug' => 'fed-minutes-rate-cut-signal',
                'data' => (object) [
                    'description' => 'The Federal Reserve June meeting minutes revealed growing support for a potential rate cut in late 2026, citing progress on inflation and a cooling labor market. Markets now price in 65% probability of a 25-basis-point cut at the September meeting. The 10-year Treasury yield fell to 4.12%.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #3B82F6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-15'),
            ],
            (object) [
                'title' => 'EnzoBank Launches AI-Powered Financial Advisor Eve',
                'slug' => 'enzobank-ai-financial-advisor',
                'data' => (object) [
                    'description' => 'EnzoBank AI-powered financial advisor, Eve, is now available to all users. Eve provides personalized investment recommendations, spending analysis, savings goals tracking, and retirement planning using advanced machine learning. Early users report 23% improvement in savings rates.',
                    'thumb_gradient' => 'linear-gradient(135deg, #7C3AED, #8B5CF6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-12'),
            ],
            (object) [
                'title' => 'Market Volatility Update: Navigating Summer Trading',
                'slug' => 'market-volatility-summer-2026',
                'data' => (object) [
                    'description' => 'Summer 2026 has brought increased volatility with the VIX climbing to 22.4, driven by geopolitical uncertainties and mixed economic data. We recommend diversified portfolios with increased allocations to defensive sectors and dividend-paying stocks. Hedging strategies using low-cost put options are also advised.',
                    'thumb_gradient' => 'linear-gradient(135deg, #DC2626, #EF4444)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-10'),
            ],
            (object) [
                'title' => 'EnzoBank Q1 2026 Earnings: Revenue Up 34% Year Over Year',
                'slug' => 'enzobank-q1-2026-earnings',
                'data' => (object) [
                    'description' => 'EnzoBank reported strong Q1 2026 results with total revenue of $847 million, a 34% increase year over year. Net income grew to $312 million driven by higher net interest income and record fee revenue. The bank added 280,000 new customer accounts during the quarter.',
                    'thumb_gradient' => 'linear-gradient(135deg, #047857, #10B981)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-05-28'),
            ],
            (object) [
                'title' => 'Bitcoin Breaks $120,000 for First Time in History',
                'slug' => 'bitcoin-120k-milestone',
                'data' => (object) [
                    'description' => 'Bitcoin surged past $120,000 for the first time, reaching an all-time high of $124,350 amid growing institutional adoption and positive regulatory developments. Ethereum crossed $8,000 for the first time. Total crypto market cap now exceeds $4.5 trillion.',
                    'thumb_gradient' => 'linear-gradient(135deg, #92400E, #F59E0B)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1558002038-1055907df827?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-05-20'),
            ],
            (object) [
                'title' => 'Retirement Planning: Maximizing 401(k) and IRA in 2026',
                'slug' => 'retirement-planning-guide-2026',
                'data' => (object) [
                    'description' => 'With 2026 contribution limits at their highest — $23,500 for 401(k) and $7,500 for IRAs — now is the time to optimize your retirement strategy. Our guide covers catch-up contributions, Roth vs. traditional considerations, and asset allocation strategies by age group.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #475569)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-05-15'),
            ],
            (object) [
                'title' => 'EnzoBank Wins Best Mobile Banking App at Global Awards',
                'slug' => 'best-mobile-banking-app-award',
                'data' => (object) [
                    'description' => 'EnzoBank mobile app has been named Best Mobile Banking App at the Global Finance Awards 2026. Judges highlighted the intuitive interface, robust security, and innovative financial management tools including AI-powered budgeting and seamless third-party integrations.',
                    'thumb_gradient' => 'linear-gradient(135deg, #B45309, #F59E0B)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-05-10'),
            ],
            (object) [
                'title' => 'REITs Guide: Real Estate Investment Trusts in 2026',
                'slug' => 'reit-investment-guide-2026',
                'data' => (object) [
                    'description' => 'REITs continue to offer attractive yields in 2026 with average dividend yields at 4.8%. Our guide covers equity, mortgage, and hybrid REITs with top picks based on fundamentals and dividend sustainability. Data center and industrial REITs are favored for growth.',
                    'thumb_gradient' => 'linear-gradient(135deg, #0F766E, #2DD4BF)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1567427017947-545c5f8d16ad?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-05-05'),
            ],
            (object) [
                'title' => 'EnzoBank Introduces Fractional Share Trading for Everyone',
                'slug' => 'fractional-share-trading-launch',
                'data' => (object) [
                    'description' => 'EnzoBank now offers fractional share trading, allowing investment in any stock or ETF with as little as $1. Build a diversified portfolio even with small amounts, invest in high-priced stocks like Berkshire Hathaway and Nvidia, and easily implement dollar-cost averaging.',
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #60A5FA)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-04-22'),
            ],
            (object) [
                'title' => 'Tax Season 2026: Key Deadlines and Deductions for Investors',
                'slug' => 'tax-season-2026-investor-guide',
                'data' => (object) [
                    'description' => 'With the April 15 deadline approaching, our tax experts cover tax-loss harvesting, qualified dividend treatment, capital gains holding periods, and crypto reporting requirements. Use EnzoBank tax document center to streamline your filing process and maximize deductions.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #4B5563)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-04-08'),
            ],
            (object) [
                'title' => 'Oil Market Analysis: Supply Constraints Drive Prices Higher',
                'slug' => 'oil-market-analysis-april-2026',
                'data' => (object) [
                    'description' => 'Crude oil prices climbed above $85 per barrel driven by OPEC+ production cuts, Middle East tensions, and strong global demand. Our analysts expect prices to average $82-88 in Q2 2026 and recommend energy sector exposure through diversified ETFs.',
                    'thumb_gradient' => 'linear-gradient(135deg, #92400E, #D97706)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-04-02'),
            ],
            (object) [
                'title' => 'EnzoBank Year in Review 2025: Record Growth and Innovation',
                'slug' => 'enzobank-year-in-review-2025',
                'data' => (object) [
                    'description' => '2025 was a landmark year — we grew to 1.8 million users, processed over $12 billion in transactions, launched 47 new features, and expanded to 12 new countries. Investment platform AUM grew 156%. We remain committed to making banking accessible for everyone.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #3B82F6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-12-30'),
            ],
            (object) [
                'title' => 'Holiday Spending Trends 2025: Consumer Behavior Analysis',
                'slug' => 'holiday-spending-trends-2025',
                'data' => (object) [
                    'description' => 'Holiday retail sales in 2025 exceeded expectations, growing 4.2% year over year to a record $1.2 trillion. E-commerce accounted for 22% of total sales. Consumer preferences shifted toward experiences over goods, with travel and dining spending up 18%.',
                    'thumb_gradient' => 'linear-gradient(135deg, #B91C1C, #EF4444)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2025-12-20'),
            ],
            (object) [
                'title' => 'EnzoBank Launches Green Bond Fund for Sustainable Investors',
                'slug' => 'enzobank-green-bond-fund',
                'data' => (object) [
                    'description' => 'Our new Green Bond Fund invests in green bonds financing environmentally sustainable projects including renewable energy, clean transportation, and sustainable water management. The fund targets 4-6% annual returns with lower volatility than traditional bond funds.',
                    'thumb_gradient' => 'linear-gradient(135deg, #065F46, #10B981)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1621761191319-c6fb62004040?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2025-12-05'),
            ],
            (object) [
                'title' => 'S&P 500 Year-End 2025: Targets and Top Stock Picks',
                'slug' => 'sp500-year-end-outlook-2025',
                'data' => (object) [
                    'description' => 'Our year-end S&P 500 target is 6,650 with 8% upside from current levels. We expect 12% earnings growth driven by margin expansion in technology and financials. Top sector picks are technology, healthcare, and financials for the coming year.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #3B82F6)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&q=80',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2025-11-20'),
            ],
            (object) [
                'title' => 'EnzoBank Partners with Mastercard for Premium Debit Cards',
                'slug' => 'enzobank-mastercard-partnership',
                'data' => (object) [
                    'description' => 'EnzoBank has partnered with Mastercard to launch premium debit cards with enhanced rewards and travel benefits. The World Elite Debit Card offers 3% cashback on dining, 2% on groceries, and 1% on all other purchases with no annual fee.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E1E2E, #3B82F6)',
                    'thumb_icon' => 'card',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0034/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-11-10'),
            ],
            (object) [
                'title' => 'US Election 2025: Market Implications and Investment Strategies',
                'slug' => 'us-election-2025-market-impact',
                'data' => (object) [
                    'description' => 'As the 2025 election cycle intensifies, our research team analyzes potential impacts on financial markets. Key areas include corporate tax rates, healthcare reform, energy regulation, and trade policy. Election years historically produce Q3 volatility followed by a year-end rally.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #4B5563)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0035/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2025-10-28'),
            ],
            (object) [
                'title' => 'EnzoBank Surpasses $10 Billion in Assets Under Management',
                'slug' => 'enzobank-10-billion-aum',
                'data' => (object) [
                    'description' => 'EnzoBank investment platform surpassed $10 billion in AUM, achieved 18 months after launching our wealth management division. The platform offers over 200 professionally managed portfolios, robo-advisory services, and self-directed trading with cutting-edge tools.',
                    'thumb_gradient' => 'linear-gradient(135deg, #047857, #34D399)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0036/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-10-05'),
            ],
            (object) [
                'title' => 'Bond Market Update: Yield Curve Normalization Strategies',
                'slug' => 'bond-market-yield-curve-october-2025',
                'data' => (object) [
                    'description' => 'The yield curve has continued normalizing with the 2s10s spread turning positive for the first time since 2022. We recommend extending portfolio duration to capture higher yields. Corporate bonds offer attractive spreads, especially in the BBB-rated segment.',
                    'thumb_gradient' => 'linear-gradient(135deg, #D97706, #FBBF24)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0037/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2025-09-25'),
            ],
            (object) [
                'title' => 'EnzoBank Launches Small Business Banking Platform',
                'slug' => 'enzobank-small-business-banking',
                'data' => (object) [
                    'description' => 'EnzoBank Business offers free checking with no minimum balance, integrated invoicing, payment processing, payroll management, and expense tracking. Business credit cards with tailored rewards are included. Over 10,000 businesses have joined the waitlist.',
                    'thumb_gradient' => 'linear-gradient(135deg, #0F766E, #2DD4BF)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0038/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-09-15'),
            ],
            (object) [
                'title' => 'AI in Finance: Transforming Investment Management',
                'slug' => 'ai-machine-learning-finance-2025',
                'data' => (object) [
                    'description' => 'Artificial intelligence is revolutionizing investment management. Our AI research team shares insights on ML algorithms for portfolio optimization, risk management, and trade execution. EnzoBank proprietary AI model has outperformed the S&P 500 by 3.2% annually.',
                    'thumb_gradient' => 'linear-gradient(135deg, #5B21B6, #8B5CF6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0039/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2025-08-28'),
            ],
            (object) [
                'title' => 'Fed Chair Testimony: Key Policy Takeaways for Investors',
                'slug' => 'fed-chair-testimony-august-2025',
                'data' => (object) [
                    'description' => 'Chair Powell semiannual testimony emphasized the Fed data-dependent approach, noting inflation progress. Markets interpreted the testimony as slightly dovish, with the S&P 500 gaining 1.2%. Interest rate futures now price in two rate cuts by year-end 2025.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #1D4ED8)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0040/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2025-08-15'),
            ],
            (object) [
                'title' => 'EnzoBank Launches Youth Banking Accounts for Teens',
                'slug' => 'enzobank-youth-banking-teens',
                'data' => (object) [
                    'description' => 'New youth banking accounts for ages 13-17 combine financial education with real-world banking. Features include a prepaid debit card with parental controls, savings goals with round-ups, educational modules on budgeting and investing.',
                    'thumb_gradient' => 'linear-gradient(135deg, #7C3AED, #A78BFA)',
                    'thumb_icon' => 'card',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0041/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-08-01'),
            ],
            (object) [
                'title' => 'Healthcare Sector: Biotech Investment Opportunities',
                'slug' => 'healthcare-biotech-investment-opportunities',
                'data' => (object) [
                    'description' => 'The healthcare sector presents compelling opportunities in biotech including gene therapy, precision oncology, and mRNA technology platforms. We provide detailed financial models and risk assessments for companies with strong clinical trial pipelines.',
                    'thumb_gradient' => 'linear-gradient(135deg, #BE185D, #EC4899)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0042/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2025-07-20'),
            ],
            (object) [
                'title' => 'Global Trade Update: Supply Chain and New Trade Corridors',
                'slug' => 'global-trade-supply-chain-2025',
                'data' => (object) [
                    'description' => 'Global trade patterns evolve as companies prioritize supply chain resilience. New trade corridors emerge between Southeast Asia, India, and Latin America. Our analysis identifies beneficiaries across shipping, logistics, and manufacturing sectors.',
                    'thumb_gradient' => 'linear-gradient(135deg, #0891B2, #22D3EE)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0043/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2025-07-08'),
            ],
            (object) [
                'title' => 'EnzoBank Reaches 1 Million Users: Our Journey So Far',
                'slug' => 'enzobank-1-million-users',
                'data' => (object) [
                    'description' => 'EnzoBank has reached 1 million active users, reflecting growing demand for innovative digital banking. From founding with a simple idea to over 2,000 employees serving customers in 30 countries, this milestone belongs to our users.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #60A5FA)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0044/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-06-15'),
            ],
            (object) [
                'title' => 'EnzoBank Spring 2025 Release: 12 New Features',
                'slug' => 'enzobank-spring-2025-release',
                'data' => (object) [
                    'description' => 'Our Spring 2025 release includes automated tax-loss harvesting, ESG portfolio screening, real-time portfolio stress testing, and expanded crypto trading with 50 new tokens. We also launched our long-awaited API platform for third-party developers.',
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #7C3AED)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0045/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-05-15'),
            ],
            (object) [
                'title' => 'Commodities Outlook: Gold, Silver, and Industrial Metals',
                'slug' => 'commodities-outlook-2025',
                'data' => (object) [
                    'description' => 'Our commodities team analyzes gold safe-haven demand amid geopolitical uncertainty, silver dual role as precious and industrial metal, and copper critical position in the energy transition. We provide price targets for each commodity.',
                    'thumb_gradient' => 'linear-gradient(135deg, #92400E, #D97706)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0046/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2025-04-28'),
            ],
            (object) [
                'title' => 'EnzoBank Launches Automated Savings Rules Engine',
                'slug' => 'enzobank-savings-rules-engine',
                'data' => (object) [
                    'description' => 'Our new Savings Rules Engine lets you create automated if-then savings rules: round up purchases, save 10% of every paycheck, or transfer $5 every time you skip coffee. Small automated actions build significant savings over time.',
                    'thumb_gradient' => 'linear-gradient(135deg, #0F766E, #2DD4BF)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0047/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-04-01'),
            ],
            (object) [
                'title' => 'Private Credit Markets: A Growing Alternative Asset',
                'slug' => 'private-credit-markets-2025',
                'data' => (object) [
                    'description' => 'Private credit has emerged as a compelling alternative asset class with yields of 8-12%. Our analysis covers direct lending, mezzanine debt, and distressed opportunities for accredited investors through EnzoBank alternative investments platform.',
                    'thumb_gradient' => 'linear-gradient(135deg, #5B21B6, #A78BFA)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0048/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2025-03-20'),
            ],
            (object) [
                'title' => 'EnzoBank Expands Crypto Offerings with New Tokens',
                'slug' => 'enzobank-crypto-expansion-2025',
                'data' => (object) [
                    'description' => 'EnzoBank crypto trading platform now supports 60+ cryptocurrencies including Solana, Cardano, Polkadot, and Chainlink. New features include staking rewards, crypto-backed loans, and automated portfolio rebalancing across digital assets.',
                    'thumb_gradient' => 'linear-gradient(135deg, #92400E, #F59E0B)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0049/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2025-03-01'),
            ],
            (object) [
                'title' => 'Annual Market Outlook 2025: Forecasts and Strategies',
                'slug' => 'annual-market-outlook-2025',
                'data' => (object) [
                    'description' => 'Our 2025 outlook provides comprehensive macroeconomic analysis and asset class expectations. We forecast S&P 500 earnings growth of 10-12% supported by margin expansion. The Fed is expected to begin cutting rates in mid-2025 supporting bond prices.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #3B82F6)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0050/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2024-12-30'),
            ],
            (object) [
                'title' => 'EnzoBank Year-End Bonuses and 2025 Hiring Plans',
                'slug' => 'enzobank-2025-employee-outlook',
                'data' => (object) [
                    'description' => 'CEO Marcus Chen announced year-end bonuses averaging 18% for all employees. The company plans to hire 500 additional employees in 2025 across engineering, product, and support, with new offices in Singapore, Dubai, and Berlin.',
                    'thumb_gradient' => 'linear-gradient(135deg, #047857, #34D399)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0051/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2024-12-18'),
            ],
            (object) [
                'title' => '12 Days of Financial Tips: Holiday Investment Guide',
                'slug' => 'holiday-investment-guide-2024',
                'data' => (object) [
                    'description' => 'Our 12 Days of Financial Tips series covers year-end tax planning, retirement contributions, charitable giving, portfolio rebalancing, and automated savings. Each tip includes actionable steps to improve your financial health this holiday season.',
                    'thumb_gradient' => 'linear-gradient(135deg, #B91C1C, #FCA5A5)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0052/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2024-12-10'),
            ],
            (object) [
                'title' => 'Thanksgiving Market History: Seasonal Trends and Patterns',
                'slug' => 'thanksgiving-market-history-2024',
                'data' => (object) [
                    'description' => 'Since 1950, the S&P 500 has posted positive returns in November 78% of the time. The Santa Claus Rally produces positive returns 79% of the time. We analyze seasonal patterns to inform your year-end investment strategy.',
                    'thumb_gradient' => 'linear-gradient(135deg, #92400E, #F59E0B)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0053/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2024-11-25'),
            ],
            (object) [
                'title' => 'EnzoBank Launches Zero-Fee International Money Transfers',
                'slug' => 'enzobank-zero-fee-international-transfers',
                'data' => (object) [
                    'description' => 'EnzoBank now offers zero-fee international transfers to over 100 countries with competitive exchange rates. Features include real-time tracking, scheduled transfers, and multi-currency wallets for global users saving an average of 4.5% versus traditional banks.',
                    'thumb_gradient' => 'linear-gradient(135deg, #0891B2, #67E8F9)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0054/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2024-11-05'),
            ],
            (object) [
                'title' => 'Post-Election Market Analysis: Historical Perspectives',
                'slug' => 'post-election-market-analysis-2024',
                'data' => (object) [
                    'description' => 'Since 1928, the S&P 500 has averaged a 7.2% gain in the 12 months following presidential elections. Healthcare, defense, and infrastructure tend to perform well post-election. We provide recommendations based on the new policy landscape.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #4B5563)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0055/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2024-11-06'),
            ],
            (object) [
                'title' => 'EnzoBank Launches High-Yield Savings at 5.2% APY',
                'slug' => 'enzobank-high-yield-savings-5-percent',
                'data' => (object) [
                    'description' => 'Our new High-Yield Savings account offers 5.2% APY with no minimum balance and no fees. Features include automatic savings round-ups, goal-based buckets, and instant transfers. FDIC insured up to $250,000 for your peace of mind.',
                    'thumb_gradient' => 'linear-gradient(135deg, #065F46, #6EE7B7)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0056/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2024-10-15'),
            ],
            (object) [
                'title' => 'Third Quarter 2024 Portfolio Performance Review',
                'slug' => 'q3-2024-portfolio-performance',
                'data' => (object) [
                    'description' => 'Q3 2024 delivered mixed results: S&P 500 gained 4.5% driven by technology. International developed markets returned 1.2%. Fixed income posted positive returns. Our balanced portfolio returned 3.8%. Updated asset allocation guidance is provided.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #60A5FA)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0057/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2024-10-05'),
            ],
            (object) [
                'title' => 'AI-Powered Fraud Detection: EnzoBank New Security System',
                'slug' => 'enzobank-ai-fraud-detection',
                'data' => (object) [
                    'description' => 'EnzoBank deployed an AI-powered fraud detection system that analyzes transaction patterns in real time. The system has prevented over $50 million in potential fraud with 99.7% accuracy and false positive rates below 0.1%.',
                    'thumb_gradient' => 'linear-gradient(135deg, #5B21B6, #A78BFA)',
                    'thumb_icon' => 'shield',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0058/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2024-09-20'),
            ],
            (object) [
                'title' => 'Retirement Planning in Your 30s and 40s: A Practical Guide',
                'slug' => 'retirement-planning-30s-40s',
                'data' => (object) [
                    'description' => 'Your 30s and 40s are critical for retirement planning. With competing priorities, you need a clear strategy. This guide covers asset allocation by age, the power of compounding, and how to catch up on retirement targets.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #64748B)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0059/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2024-08-25'),
            ],
            (object) [
                'title' => 'EnzoBank Summer 2024 Feature Release Highlights',
                'slug' => 'enzobank-summer-2024-features',
                'data' => (object) [
                    'description' => 'Our summer release includes group savings goals, AI-powered bill negotiation, subscription manager, and investment portfolio analyzer. Users can split savings goals with friends, automatically negotiate bills, and track all subscriptions in one place.',
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #60A5FA)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0060/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2024-08-01'),
            ],
            (object) [
                'title' => 'Real Estate Market Analysis 2024: Rates and Trends',
                'slug' => 'real-estate-market-2024-analysis',
                'data' => (object) [
                    'description' => 'Our real estate analysis covers the impact of mortgage rates on housing markets, commercial real estate trends, and REIT performance. We identify opportunity zones and property sectors positioned for growth in the current environment.',
                    'thumb_gradient' => 'linear-gradient(135deg, #B45309, #F59E0B)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0061/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2024-07-20'),
            ],
            (object) [
                'title' => 'EnzoBank Launches Premium Savings Challenge Feature',
                'slug' => 'enzobank-savings-challenge',
                'data' => (object) [
                    'description' => 'Our new Savings Challenges feature allows users to create customized savings goals with automated transfers and progress tracking. Popular challenges include the 52-week money challenge and emergency fund builder to make saving fun.',
                    'thumb_gradient' => 'linear-gradient(135deg, #0F766E, #5EEAD4)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0062/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2024-07-01'),
            ],
            (object) [
                'title' => 'Mid-Year 2024 Portfolio Checkup: Rebalancing Guide',
                'slug' => 'mid-year-portfolio-checkup-2024',
                'data' => (object) [
                    'description' => 'Mid-year is perfect for portfolio review. Our guide covers rebalancing strategies, performance benchmarking, tax-loss harvesting opportunities, and adjustments to your asset allocation based on changing market conditions.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #60A5FA)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0063/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2024-06-30'),
            ],
            (object) [
                'title' => 'EnzoBank Introduces Joint Accounts for Couples',
                'slug' => 'enzobank-joint-accounts-launch',
                'data' => (object) [
                    'description' => 'EnzoBank launches joint checking and savings accounts for couples and families. Features include shared budgeting tools, goal-based savings, individual privacy settings, and seamless transfers between joint and personal accounts.',
                    'thumb_gradient' => 'linear-gradient(135deg, #BE185D, #EC4899)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0064/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2024-06-15'),
            ],
            (object) [
                'title' => 'Student Loan Repayment Strategies for 2024',
                'slug' => 'student-loan-repayment-strategies-2024',
                'data' => (object) [
                    'description' => 'With federal student loan payments resuming, our guide covers repayment options, consolidation vs. refinancing, PSLF eligibility, and strategies for managing loans while continuing to save and invest for other financial goals.',
                    'thumb_gradient' => 'linear-gradient(135deg, #7C3AED, #A78BFA)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0065/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2024-06-01'),
            ],
            (object) [
                'title' => 'EnzoBank Wins Best Customer Service Award 2024',
                'slug' => 'enzobank-customer-service-award-2024',
                'data' => (object) [
                    'description' => 'EnzoBank was awarded Best Customer Service in Digital Banking with an average response time of 47 seconds and 94% satisfaction rating. Our support team handles over 50,000 inquiries daily across chat, phone, and email channels.',
                    'thumb_gradient' => 'linear-gradient(135deg, #B45309, #F59E0B)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0066/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2024-05-15'),
            ],
            (object) [
                'title' => 'Spring 2024 Market Outlook: Sector Rotation Insights',
                'slug' => 'spring-market-outlook-2024',
                'data' => (object) [
                    'description' => 'Our spring outlook identifies early signs of sector rotation from technology into financials, industrials, and energy. We analyze historical rotation patterns and provide actionable recommendations for positioning ahead of the next market phase.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #475569)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0067/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2024-04-15'),
            ],
            (object) [
                'title' => 'EnzoBank Year in Review 2023: Building the Foundation',
                'slug' => 'enzobank-year-in-review-2023',
                'data' => (object) [
                    'description' => '2023 was foundational — we launched our mobile app, introduced investment products, and grew to 250,000 users. Key milestones included our AI-powered budgeting tool, partnerships with payment networks, and $200 million in Series C funding.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #3B82F6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0068/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2023-12-28'),
            ],
            (object) [
                'title' => 'Holiday Budgeting: Enjoy Without Financial Stress',
                'slug' => 'holiday-budgeting-tips-2023',
                'data' => (object) [
                    'description' => 'Practical tips: create a spending plan, use cash envelopes for gifts, maximize cashback rewards, consider homemade gifts, and start a holiday savings fund in January. Small changes make a big difference in financial well-being.',
                    'thumb_gradient' => 'linear-gradient(135deg, #B91C1C, #FCA5A5)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0069/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2023-12-10'),
            ],
            (object) [
                'title' => 'Market Outlook 2024: Navigating the Rate Environment',
                'slug' => 'market-outlook-2024',
                'data' => (object) [
                    'description' => 'Our 2024 outlook covers equities, fixed income, commodities, and currencies. We expect moderate equity returns of 5-8% with technology and healthcare leading. Bond yields likely decline as the Fed begins its easing cycle in H2 2024.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #2563EB)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0070/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2023-12-01'),
            ],
            (object) [
                'title' => 'EnzoBank Raises $350 Million in Series D Funding',
                'slug' => 'enzobank-series-d-funding',
                'data' => (object) [
                    'description' => 'EnzoBank raised $350 million in Series D led by Sequoia Capital and Accel Partners, valuing the company at $4.8 billion. Funds will accelerate product development and expand into new markets across Asia and Latin America.',
                    'thumb_gradient' => 'linear-gradient(135deg, #047857, #34D399)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0071/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2023-11-08'),
            ],
            (object) [
                'title' => 'EnzoBank Partners with Plaid for Account Connectivity',
                'slug' => 'enzobank-plaid-partnership',
                'data' => (object) [
                    'description' => 'EnzoBank partnered with Plaid to enable secure account connectivity with thousands of financial apps. Users can now link their accounts to budgeting apps, investment trackers, and tax preparation software with bank-level security.',
                    'thumb_gradient' => 'linear-gradient(135deg, #0891B2, #22D3EE)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0072/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2023-11-20'),
            ],
            (object) [
                'title' => 'Year-End Tax Planning: Strategies for 2023',
                'slug' => 'year-end-tax-planning-2023',
                'data' => (object) [
                    'description' => 'Our tax experts share year-end strategies: harvest investment losses, maximize retirement contributions, consider Roth conversions, bunch charitable deductions, and review withholding to avoid surprises in April.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #64748B)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0073/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2023-11-15'),
            ],
            (object) [
                'title' => 'Dollar-Cost Averaging: Beginner Investment Guide',
                'slug' => 'dollar-cost-averaging-beginners',
                'data' => (object) [
                    'description' => 'Dollar-cost averaging is one of the most effective investment strategies. By investing a fixed amount regularly, you buy more when prices are low and fewer when high. Learn how to set up automated DCA with EnzoBank.',
                    'thumb_gradient' => 'linear-gradient(135deg, #0F766E, #2DD4BF)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0074/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2023-10-20'),
            ],
            (object) [
                'title' => 'Geopolitical Risk and Markets in 2023',
                'slug' => 'geopolitical-risk-markets-2023',
                'data' => (object) [
                    'description' => 'Geopolitical tensions influence global markets in 2023. From Eastern Europe to US-China trade dynamics, our risk team analyzes market impacts and provides frameworks for managing geopolitical risk through diversification and hedging.',
                    'thumb_gradient' => 'linear-gradient(135deg, #991B1B, #EF4444)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0075/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2023-10-05'),
            ],
            (object) [
                'title' => 'EnzoBank Launches BNPL Feature for Online Shopping',
                'slug' => 'enzobank-bnpl-launch',
                'data' => (object) [
                    'description' => 'EnzoBank now offers Buy Now Pay Later at over 5,000 online merchants. Users can split purchases into four interest-free payments with no hidden fees, integrated directly into the EnzoBank mobile app for seamless checkout.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E40AF, #3B82F6)',
                    'thumb_icon' => 'card',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0076/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2023-10-10'),
            ],
            (object) [
                'title' => 'EnzoBank Debuts Debit Card with 2% Unlimited Cashback',
                'slug' => 'enzobank-2-percent-cashback-debit-card',
                'data' => (object) [
                    'description' => 'Our new debit card offers 2% unlimited cashback on all purchases with no categories or caps. Made from recycled materials with contactless payment. Over 100,000 sign-ups in the first week alone.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #60A5FA)',
                    'thumb_icon' => 'card',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0077/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2023-09-10'),
            ],
            (object) [
                'title' => 'Small Business Spotlight: Merchant Services Growth',
                'slug' => 'enzobank-merchant-services-2023',
                'data' => (object) [
                    'description' => 'EnzoBank merchant services division serves 25,000 small businesses with payment processing, POS systems, and business lending. Our platform processed over $500 million in Q3 2023 with 99.99% uptime.',
                    'thumb_gradient' => 'linear-gradient(135deg, #065F46, #6EE7B7)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0078/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2023-09-25'),
            ],
            (object) [
                'title' => 'ESG Investing: Building a Sustainable Portfolio',
                'slug' => 'esg-investing-guide-2023',
                'data' => (object) [
                    'description' => 'Environmental, Social, and Governance investing continues to gain momentum. Our guide covers ESG rating methodologies, top sustainable funds, and how to align investments with your values without sacrificing returns.',
                    'thumb_gradient' => 'linear-gradient(135deg, #16A34A, #22C55E)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0079/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2023-09-01'),
            ],
            (object) [
                'title' => 'Back-to-School Financial Planning for Parents',
                'slug' => 'back-to-school-financial-planning-2023',
                'data' => (object) [
                    'description' => 'Average families spend over $800 on school supplies. Our team provides strategies for managing costs: education savings accounts, 529 contributions, budgeting, and teaching children about money management.',
                    'thumb_gradient' => 'linear-gradient(135deg, #7C3AED, #A78BFA)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0080/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2023-08-15'),
            ],
            (object) [
                'title' => 'EnzoBank Surpasses 500,000 Users in First Year',
                'slug' => 'enzobank-500k-users-first-year',
                'data' => (object) [
                    'description' => 'Just 12 months after launch, EnzoBank surpassed 500,000 users. Users have collectively saved over $200 million and earned $15 million in cashback rewards. We are just getting started on our mission.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #3B82F6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0081/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2023-07-20'),
            ],
            (object) [
                'title' => 'Summer 2023 Market Trends: What Is Driving the Rally',
                'slug' => 'summer-2023-market-rally',
                'data' => (object) [
                    'description' => 'Financial markets rallied with the S&P 500 up 15% year to date. The rally is driven by AI enthusiasm, resilient corporate earnings, and expectations that the Fed is nearing the end of its rate hiking cycle.',
                    'thumb_gradient' => 'linear-gradient(135deg, #059669, #6EE7B7)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0082/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2023-07-05'),
            ],
            (object) [
                'title' => 'EnzoBank Officially Launches Across North America and Europe',
                'slug' => 'enzobank-official-launch',
                'data' => (object) [
                    'description' => 'After a successful beta with 50,000 users, EnzoBank launches across North America and Europe. The platform offers checking, savings, investment management, international transfers, and AI-powered financial tools for the modern user.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #3B82F6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0083/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2022-09-15'),
            ],
            (object) [
                'title' => 'EnzoBank Raises $120 Million in Series A and B Funding',
                'slug' => 'enzobank-series-a-b-funding',
                'data' => (object) [
                    'description' => 'EnzoBank raised $120 million from Andreessen Horowitz, Index Ventures, and former Treasury Secretary Lawrence Summers. The funding supports product development, regulatory compliance, and market expansion.',
                    'thumb_gradient' => 'linear-gradient(135deg, #047857, #34D399)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0084/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2022-06-10'),
            ],
            (object) [
                'title' => 'Cryptocurrency Basics: Your Guide to Digital Assets',
                'slug' => 'cryptocurrency-basics-guide-2022',
                'data' => (object) [
                    'description' => 'New to crypto? Our beginner guide covers Bitcoin, Ethereum, blockchain technology, wallet setup, and security best practices. Learn how digital assets fit into a diversified portfolio and understand the risks and opportunities.',
                    'thumb_gradient' => 'linear-gradient(135deg, #92400E, #F59E0B)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0085/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2022-08-20'),
            ],
            (object) [
                'title' => 'How to Read Financial Statements Like a Pro',
                'slug' => 'financial-statements-guide-2022',
                'data' => (object) [
                    'description' => 'Understanding balance sheets, income statements, and cash flow statements is essential for investing. Our guide breaks down each statement and shows how to evaluate company health using key financial ratios.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #3B82F6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0086/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2022-07-15'),
            ],
            (object) [
                'title' => 'EnzoBank Hires CTO from Google',
                'slug' => 'enzobank-cto-hire-google',
                'data' => (object) [
                    'description' => 'EnzoBank appointed Dr. Sarah Chen, former Google VP of Engineering, as Chief Technology Officer. She will lead AI development, infrastructure scaling, and blockchain research initiatives for the company.',
                    'thumb_gradient' => 'linear-gradient(135deg, #5B21B6, #8B5CF6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0087/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2022-05-01'),
            ],
            (object) [
                'title' => 'Understanding Inflation: Portfolio Impact and Strategies',
                'slug' => 'understanding-inflation-portfolio-2022',
                'data' => (object) [
                    'description' => 'With inflation at multi-decade highs, we explain how rising prices affect different asset classes. Learn which sectors perform well during inflation, how to protect purchasing power, and positioning strategies.',
                    'thumb_gradient' => 'linear-gradient(135deg, #DC2626, #EF4444)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0088/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2022-04-01'),
            ],
            (object) [
                'title' => 'The Future of Digital Banking: EnzoBank Vision for 2030',
                'slug' => 'future-of-digital-banking-2030',
                'data' => (object) [
                    'description' => 'Founder Marcus Chen shares his vision for the future — from AI-powered advisors to DeFi integration. In 2030, your bank will be an intelligent partner that anticipates your needs and helps achieve your goals.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #4B5563)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0089/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2022-04-20'),
            ],
            (object) [
                'title' => 'Market Volatility in 2022: Strategies for Uncertain Times',
                'slug' => 'market-volatility-2022-strategies',
                'data' => (object) [
                    'description' => '2022 has been challenging with stocks and bonds declining together. Our team provides actionable advice: maintain long-term perspective, rebalance systematically, focus on quality, and avoid emotional decisions.',
                    'thumb_gradient' => 'linear-gradient(135deg, #991B1B, #FCA5A5)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0090/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2022-03-15'),
            ],
            (object) [
                'title' => 'EnzoBank Beta Launch: Early Access for 50,000 Users',
                'slug' => 'enzobank-beta-launch',
                'data' => (object) [
                    'description' => 'EnzoBank is accepting beta users! The first 50,000 get early access and free premium membership. Features include mobile banking, peer-to-peer payments, budgeting tools, and AI-powered spending insights.',
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #60A5FA)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0091/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2022-02-01'),
            ],
            (object) [
                'title' => 'New Year Financial Resolutions for 2022',
                'slug' => 'new-year-financial-resolutions-2022',
                'data' => (object) [
                    'description' => 'Reset your financial habits: build an emergency fund, pay down debt, increase retirement contributions, create a budget using the 50/30/20 rule, and review insurance coverage. Small actions lead to big results.',
                    'thumb_gradient' => 'linear-gradient(135deg, #0F766E, #5EEAD4)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0092/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2022-01-05'),
            ],
            (object) [
                'title' => 'EnzoBank Q2 2026 Earnings: Record Revenue Growth',
                'slug' => 'enzobank-q2-2026-earnings',
                'data' => (object) [
                    'description' => 'EnzoBank reported Q2 2026 revenue of $912 million, up 38% year over year, with net income of $345 million. The bank added 310,000 new accounts and processed over $4.5 billion in transactions during the quarter.',
                    'thumb_gradient' => 'linear-gradient(135deg, #047857, #34D399)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0093/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-08-01'),
            ],
            (object) [
                'title' => 'Back-to-School Financial Guide 2026: Smart Strategies',
                'slug' => 'back-to-school-financial-guide-2026',
                'data' => (object) [
                    'description' => 'Our 2026 back-to-school guide covers education savings strategies, 529 plan optimization, student loan planning, and budgeting for expenses. EnzoBank tools help you prepare for every education stage.',
                    'thumb_gradient' => 'linear-gradient(135deg, #7C3AED, #A78BFA)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0094/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-08-05'),
            ],
            (object) [
                'title' => 'Carbon Footprint Tracker: EnzoBank Green Initiative',
                'slug' => 'enzobank-carbon-footprint-tracker',
                'data' => (object) [
                    'description' => 'EnzoBank new carbon footprint tracker analyzes your spending to estimate environmental impact. Get personalized suggestions for reducing your footprint, carbon offsets, and green investment recommendations.',
                    'thumb_gradient' => 'linear-gradient(135deg, #16A34A, #22C55E)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0095/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-08-10'),
            ],
            (object) [
                'title' => 'Emerging Markets: 2026 Investment Opportunities',
                'slug' => 'emerging-markets-investment-2026',
                'data' => (object) [
                    'description' => 'Our emerging markets team identifies compelling opportunities in India, Southeast Asia, and Africa. Growing middle classes, digital adoption, and favorable demographics create a strong investment thesis for these regions.',
                    'thumb_gradient' => 'linear-gradient(135deg, #D97706, #FBBF24)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0096/400/300',
                ],
                'category' => (object) ['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-07-28'),
            ],
            (object) [
                'title' => 'EnzoBank Launches Premium Travel Insurance for Cardholders',
                'slug' => 'enzobank-travel-insurance',
                'data' => (object) [
                    'description' => 'EnzoBank Premium cardholders now receive comprehensive travel insurance including trip cancellation, lost luggage, rental car coverage, and emergency medical evacuation. Coverage is automatic with no enrollment needed.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #60A5FA)',
                    'thumb_icon' => 'card',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0097/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-25'),
            ],
            (object) [
                'title' => 'Cybersecurity in Banking: How EnzoBank Stays Ahead',
                'slug' => 'enzobank-cybersecurity-2026',
                'data' => (object) [
                    'description' => 'EnzoBank invests $50 million annually in cybersecurity. Our defense-in-depth strategy includes AI threat detection, zero-trust architecture, regular penetration testing, and a dedicated security operations center.',
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #334155)',
                    'thumb_icon' => 'shield',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0098/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-20'),
            ],
            (object) [
                'title' => 'Global Central Bank Digital Currency (CBDC) Developments',
                'slug' => 'cbdc-developments-2026',
                'data' => (object) [
                    'description' => 'Our research team analyzes the rapid global development of central bank digital currencies. With over 100 countries exploring CBDCs, we assess implications for cross-border payments, monetary policy, and investment strategies.',
                    'thumb_gradient' => 'linear-gradient(135deg, #5B21B6, #8B5CF6)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0099/400/300',
                ],
                'category' => (object) ['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-18'),
            ],
            (object) [
                'title' => 'EnzoBank Launches Automated Portfolio Rebalancing',
                'slug' => 'enzobank-auto-rebalancing',
                'data' => (object) [
                    'description' => 'EnzoBank introduces automatic portfolio rebalancing that brings your asset allocation back to target whenever it drifts beyond your chosen threshold. Customize rebalancing frequency, bands, and tax optimization preferences.',
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #1D4ED8)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://picsum.photos/seed/enzobank-article-0100/400/300',
                ],
                'category' => (object) ['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-16'),
            ],
        ]);
    }
}
