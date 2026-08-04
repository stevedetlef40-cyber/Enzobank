<?php

namespace App\Traits\PaymentGateway;

use App\Models\Admin\CryptoAsset;

/**
 * Tatum Blockchain Gateway — stub trait for route registration.
 * Full Tatum API integration requires API keys; these methods
 * provide safe defaults so the admin panel does not crash.
 */
trait Tatum
{
    /**
     * Check if the given gateway is a Tatum gateway.
     */
    public function isTatum($gateway): bool
    {
        return strtolower($gateway->type ?? '') === 'tatum';
    }

    /**
     * Return the list of active blockchain chains supported by Tatum.
     */
    public function tatumActiveChains(): array
    {
        return [
            ['chain' => 'ethereum', 'coin' => 'ETH'],
            ['chain' => 'bitcoin',  'coin' => 'BTC'],
            ['chain' => 'polygon',  'coin' => 'MATIC'],
            ['chain' => 'binance',  'coin' => 'BNB'],
            ['chain' => 'tron',     'coin' => 'TRX'],
        ];
    }

    /**
     * Look up chain details for a given coin symbol.
     */
    public function tatumRegisteredChains(string $target): array
    {
        $chains = [
            'ETH' => ['chain' => 'ethereum', 'coin' => 'ETH'],
            'BTC' => ['chain' => 'bitcoin',  'coin' => 'BTC'],
            'MATIC' => ['chain' => 'polygon',  'coin' => 'MATIC'],
            'BNB' => ['chain' => 'binance',  'coin' => 'BNB'],
            'TRX' => ['chain' => 'tron',     'coin' => 'TRX'],
        ];

        return $chains[strtoupper($target)] ?? ['chain' => 'ethereum', 'coin' => 'ETH'];
    }

    /**
     * Stub: subscribe to account transactions. In production this calls
     * the Tatum API. Here we return a mock object so the controller
     * does not crash.
     */
    public function tatumSubscriptionForAccountTransaction(CryptoAsset $cryptoAsset, string $address): object
    {
        return (object) [
            'id' => 'stub_'.uniqid(),
        ];
    }

    /**
     * Stub: cancel a subscription. Safe no-op when no real subscription exists.
     */
    public function tatumSubscriptionCancelForAccountTransaction(string $subscribeId, $gateway): void
    {
        // No-op: in production this calls the Tatum API to remove the webhook.
    }

    /**
     * Stub: generate Tatum wallet assets. No-op for stub.
     */
    public function getTatumAssets($gateway): void
    {
        // No-op: in production this creates blockchain wallets via Tatum.
    }

    /**
     * Stub: fetch address balance from Tatum.
     */
    public function getTatumAddressBalance(CryptoAsset $cryptoAsset, array $walletInfo): object
    {
        return (object) [
            'balance' => 0,
        ];
    }
}
