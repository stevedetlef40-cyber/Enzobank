<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CryptoDeposit;
use App\Services\DepositGateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CryptoDepositController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();

            return $next($request);
        });
    }

    /**
     * Step 1: Show crypto selection form
     */
    public function index()
    {
        $page_title = 'Fund Wallet';
        $coins = get_crypto_coins($this->user);

        return view('user.sections.crypto-deposit.index', compact('page_title', 'coins'));
    }

    /**
     * Process step 1: validate amount + coin, redirect to address page
     */
    public function store(Request $request)
    {
        $coins = get_crypto_coins($this->user);
        $validKeys = array_keys($coins);

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:10|max:999999.99',
            'coin_key' => 'required|string|in:'.implode(',', $validKeys),
        ], [
            'amount.min' => 'Minimum deposit is $10.00',
            'amount.max' => 'Maximum deposit exceeded',
            'coin_key.in' => 'Please select a valid cryptocurrency',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $coinKey = $request->coin_key;
        $coin = $coins[$coinKey] ?? null;

        if (! $coin) {
            return back()->with(['error' => ['Invalid cryptocurrency selected.']])->withInput();
        }

        return redirect()->route('user.crypto.deposit.address', [
            'coin_key' => $coinKey,
            'amount' => $request->amount,
        ]);
    }

    /**
     * Step 2: Show deposit address with QR, copy, and confirm button
     */
    public function address(Request $request)
    {
        $coinKey = $request->query('coin_key');
        $amount = $request->query('amount');
        $coins = get_crypto_coins($this->user);

        if (! $coinKey || ! isset($coins[$coinKey])) {
            return redirect()->route('user.crypto.deposit.index')
                ->with(['error' => ['Invalid selection. Please try again.']]);
        }

        $amount = floatval($amount);
        if ($amount < 10) {
            return redirect()->route('user.crypto.deposit.index')
                ->with(['error' => ['Minimum deposit is $10.00.']]);
        }

        $coin = $coins[$coinKey];
        $page_title = 'Deposit '.$coin['coin'].' ('.$coin['network'].')';

        return view('user.sections.crypto-deposit.address', compact(
            'page_title', 'coin', 'coinKey', 'amount'
        ));
    }

    /**
     * Step 3: Show confirmation form (proof upload / tx hash)
     */
    public function confirm(Request $request)
    {
        $coinKey = $request->query('coin_key');
        $amount = $request->query('amount');
        $coins = get_crypto_coins($this->user);

        if (! $coinKey || ! isset($coins[$coinKey])) {
            return redirect()->route('user.crypto.deposit.index')
                ->with(['error' => ['Invalid selection. Please try again.']]);
        }

        $amount = floatval($amount);
        $coin = $coins[$coinKey];
        $page_title = 'Confirm Payment';

        return view('user.sections.crypto-deposit.confirm', compact(
            'page_title', 'coin', 'coinKey', 'amount'
        ));
    }

    /**
     * Process step 3: upload proof / tx hash, create crypto_deposit record
     */
    public function submit(Request $request)
    {
        $coins = get_crypto_coins($this->user);
        $validKeys = array_keys($coins);

        $validator = Validator::make($request->all(), [
            'coin_key' => 'required|string|in:'.implode(',', $validKeys),
            'amount' => 'required|numeric|min:10|max:999999.99',
            'tx_hash' => 'nullable|string|max:255',
            'proof' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'amount.min' => 'Minimum deposit is $10.00',
            'proof.image' => 'Proof must be an image file (jpg, png)',
            'proof.mimes' => 'Proof must be jpg or png format',
            'proof.max' => 'Proof image must not exceed 5MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Per-user crypto deposit controls (set by admin)
        if (! $this->user->crypto_status) {
            return back()->with(['error' => ['Crypto deposit is currently disabled for your account.']])->withInput();
        }
        if ($this->user->crypto_limit !== null && $request->amount > $this->user->crypto_limit) {
            return back()->with(['error' => ['Maximum crypto deposit amount is '.get_amount($this->user->crypto_limit).'.']])->withInput();
        }

        // At least one of tx_hash or proof is required
        if (! $request->tx_hash && ! $request->hasFile('proof')) {
            return back()->with(['error' => ['Please provide either a transaction hash or upload a proof screenshot.']])->withInput();
        }

        $coinKey = $request->coin_key;
        $coin = $coins[$coinKey] ?? null;

        if (! $coin) {
            return back()->with(['error' => ['Invalid cryptocurrency selected.']]);
        }

        // Handle proof file upload
        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('crypto-proofs', 'public');
        }

        // Create deposit record
        $deposit = CryptoDeposit::create([
            'user_id' => $this->user->id,
            'coin_symbol' => $coin['coin'],
            'network' => $coin['network'],
            'wallet_address' => $coin['address'],
            'amount_usd' => $request->amount,
            'amount_crypto' => null,
            'tx_hash' => $request->tx_hash,
            'proof' => $proofPath,
            'status' => 'pending',
        ]);

        // Notify user about pending deposit
        try {
            DepositGateService::notifyDepositSubmitted($this->user, $request->amount, $coin['coin']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Deposit submitted notification failed: '.$e->getMessage());
        }

        return redirect()->route('user.crypto.deposit.success', $deposit->id);
    }

    /**
     * Step 4: Success screen
     */
    public function success($id)
    {
        $deposit = CryptoDeposit::auth()->findOrFail($id);
        $page_title = 'Payment Submitted';

        return view('user.sections.crypto-deposit.success', compact('page_title', 'deposit'));
    }
}
