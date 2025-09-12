<?php

namespace App\Policies;

use App\Models\Fixture;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FixturePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'super-admin' || $user->role === 'site-admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Fixture $fixture): bool
    {
        // Ana admin her şeyi görür.
        if ($user->role === 'super-admin') {
            return true;
        }

        // Site admini sadece kendi sitesindeki demirbaşı görebilir.
        if ($user->role === 'site-admin') {
            return $user->site_id === $fixture->site_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'super-admin' || $user->role === 'site-admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Fixture $fixture): bool
    {
        // Ana admin her şeyi güncelleyebilir.
        if ($user->role === 'super-admin') {
            return true;
        }

        // Site admini sadece kendi sitesindeki demirbaşı güncelleyebilir.
        if ($user->role === 'site-admin') {
            return $user->site_id === $fixture->site_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fixture $fixture): bool
    {
        return $user->role === 'super-admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Fixture $fixture): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Fixture $fixture): bool
    {
        return false;
    }
    public function before($user, $ability)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
    }

}
