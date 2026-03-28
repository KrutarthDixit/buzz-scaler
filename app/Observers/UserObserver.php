<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Automatically create a wallet with zero balance when a user model is created
        $user->wallet()->create([
            'balance' => 0,
        ]);
    }
}
