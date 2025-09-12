<?php

namespace App\Policies;

use App\Models\Fee;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FeePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        return null;
    }

    // Bu metot hem 'ödendi işaretle' hem de 'güncelle' işlemleri için kullanılabilir.
    public function update(User $user, Fee $fee): bool
    {
        // Eğer kullanıcı site-admin ise, aidatın ait olduğu sitenin,
        // yöneticinin sorumlu olduğu sitelerden biri olup olmadığını kontrol et.
        if ($user->hasRole('site-admin')) {
            return $user->managedSites->contains($fee->site_id);
        }
        return false;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Fee $fee): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }



    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fee $fee): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Fee $fee): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Fee $fee): bool
    {
        return false;
    }
}
