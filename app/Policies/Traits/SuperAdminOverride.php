<?php

namespace App\Policies\Traits;

use App\Models\User;

trait SuperAdminOverride
{
    /**
     * Tüm yetki kontrollerinden önce çalışır ve 'super_admin' rolüne tam yetki verir.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return null;
    }
}
