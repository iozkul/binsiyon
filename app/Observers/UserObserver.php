<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivityLog; // Log modelini oluşturmanız gerekecek

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user)
    {
        $changes = $user->getChanges();
        // Şifre değişikliğini loglamak istemeyiz.
        if (isset($changes['password'])) {
            unset($changes['password']);
        }
        if (isset($changes['remember_token'])) {
            unset($changes['remember_token']);
        }

        if (empty($changes)) {
            return;
        }

        UserActivityLog::create([
            'user_id' => $user->id,
            'actor_id' => Auth::id(),
            'action_code' => 'USER_PROFILE_UPDATED',
            'old_values' => json_encode(collect($user->getOriginal())->only(array_keys($changes))),
            'new_values' => json_encode($changes)
        ]);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
