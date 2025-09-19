<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\User;

class PermissionCacheService
{
    /**
     * Kullanıcının tüm yetkilerini (doğrudan ve rollerden gelen) hesaplar ve önbelleğe alır.
     * @param User $user
     */
    public function cacheUserPermissions(User $user): void
    {
        // Kullanıcının tüm izinlerini (roller üzerinden gelenler dahil) al.
        $permissions = $user->getAllPermissions()->pluck('name');

        // Kullanıcının rollerini al.
        $roles = $user->getRoleNames();

        // Kullanıcıya özel bir anahtarla (key) bu verileri 1 gün (86400 saniye) boyunca önbellekte tut.
        $cacheKey = "user_{$user->id}_permissions_and_roles";

        Cache::put($cacheKey, [
            'permissions' => $permissions,
            'roles' => $roles,
        ], 86400);
    }

    /**
     * Önbellekten kullanıcının yetkilerini temizler (örneğin rolü değiştiğinde).
     * @param User $user
     */
    public function clearUserCache(User $user): void
    {
        $cacheKey = "user_{$user->id}_permissions_and_roles";
        Cache::forget($cacheKey);
    }
}
