<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

	 public function before(User $user, string $ability): bool|null
    {
        /*
		$roles = $user->getRoleNames();
		if ($roles->contains('super-admin')) {
        //if ($user->hasRole('super-admin')) {
            return true;
        }
        return null;*/
        if ($user->getRoleNames()->contains('super-admin')) {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //return $user->hasAnyRole(['super-admin', 'site-admin', 'block-admin']);
       // return $user->hasAnyPermission(['manage users', 'manage residents']);
        return $user->hasAnyRole(['super-admin', 'site-admin', 'block-admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('site-admin') && $model->unit) {
            return $user->managedSites->contains($model->unit->block->site_id);
        }
        // Blok yöneticisi, kendi bloğundaki bir sakini görebilir.
        if ($user->hasRole('block-admin') && $model->unit) {
            return $user->managedBlocks->contains($model->unit->block_id);
        }

        // Herkes kendi profilini görebilir.
        if ($user->id === $model->id) {
            return true;
        }
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
     * Determine whether the user can update the model.
     */
    /**
     * Bir kullanıcıyı kimlerin güncelleyebileceğini belirler.
     * @param  \App\Models\User  $user  İşlemi YAPAN kullanıcı (giriş yapmış olan)
     * @param  \App\Models\User  $model İşlemin YAPILDIĞI kullanıcı (profili güncellenen)
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        // KURAL 1: İşlemi yapan kişi bir 'super-admin' ise, her zaman izin ver.
        // En güvenilir yöntem olan getRoleNames()->contains() kullanıyoruz.
        if ($user->getRoleNames()->contains('super-admin')) {
            return true;
        }

        // KURAL 2: Bir kullanıcı kendi profilini güncelliyorsa, izin ver.
        if ($user->id === $model->id) {
            return true;
        }

        // KURAL 3: Site yöneticisi, kendi sitesindeki bir kullanıcıyı güncelliyorsa, izin ver.
        // Güncellenen kullanıcının bir birime atanmış olması gerekir ($model->unit).
        if ($user->hasRole('site-admin') && $model->unit) {
            return $user->managedSites->contains($model->unit->block->site_id);
        }
        // Kural 2: 'manage users' yetkisi olan biri başkalarını güncelleyebilir.
        if ($user->can('manage users')) {
            // İsteğe bağlı: super-admin olmayan bir site-admin'in başka bir site-admin'i
            // düzenlemesini engellemek gibi daha detaylı kurallar eklenebilir.
            return true;
        }
        // Yukarıdaki kurallardan hiçbiri karşılanmazsa, işlemi reddet.
        return false;
    }

    public function viewLedger(User $currentUser, User $targetUser): bool
    {
        // Bir kullanıcı sadece kendi hesap özetini görebilir.
        if ($currentUser->id === $targetUser->id) {
            return true;
        }

        // Site yöneticisi, kendi sitesindeki bir sakinin hesap özetini görebilir.
        if ($currentUser->hasRole('site-admin') && $targetUser->unit) {
            return $currentUser->managedSites->contains($targetUser->unit->block->site_id);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
    public function assignUnit(User $currentUser): bool
    {
        // Sadece 'super-admin' rolüne sahip olanlar bu işlemi yapabilir.
        return $currentUser->hasRole('super-admin');
    }


}
