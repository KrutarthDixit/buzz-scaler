<?php

namespace App\Traits;

use App\Models\WalletTransaction;

trait TracksWalletTransactions
{
    /**
     * Boot the trait and register model events.
     */
    public static function bootTracksWalletTransactions(): void
    {
        static::created(function ($wallet) {
            if ($wallet->balance != 0) {
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => 'credit',
                    'amount' => $wallet->balance,
                    'balance_before' => 0,
                    'balance_after' => $wallet->balance,
                    'description' => 'Initial wallet balance',
                ]);
            }
        });

        static::updating(function ($wallet) {
            if ($wallet->isDirty('balance')) {
                $originalBalance = $wallet->getOriginal('balance');
                $newBalance = $wallet->balance;
                $difference = $newBalance - $originalBalance;

                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => $difference >= 0 ? 'credit' : 'debit',
                    'amount' => abs($difference),
                    'balance_before' => $originalBalance,
                    'balance_after' => $newBalance,
                    'description' => $wallet->transaction_description ?? null,
                ]);

                // Clear the temporary attribute so it doesn't get saved
                unset($wallet->transaction_description);
            }
        });
    }
}
