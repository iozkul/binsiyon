<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;


class SitePolicy
{
    use HandlesAuthorization;



    /**
     * Determine whether the user can view any models.
     */


    /*
    public function viewAny(User $user): bool
    {
        return $user->can('manage sites');
        //return $user->hasAnyRole(['site-admin', 'block-admin']);
        //return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    /*
    public function view(User $user, Site $site): bool
    {
        /*
        if ($user->can('manage sites') &&$user->hasRole('site-admin')) {
            // Sadece kendi yönettiği siteyi görebilir.
            //return $user->managedSites()->where('site_id', $site->id)->exists();
            return $user->managedSites->contains($site);
        }*//*
        return $user->can('manage sites') && $user->managedSites()->where('site_id', $site->id)->exists();
        //return $user->can('manage sites');
    }
*/
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        return null;
    }

    public function view(User $user, Site $site): bool
    {
        // Kullanıcının 'manage sites' yetkisine sahip olup olmadığını kontrol et
        // ve yönettiği siteler arasında ilgili site olup olmadığını kontrol et.
        //return $user->can('manage sites') && $user->managedSites->contains($site);
        // Kullanıcı, sitenin yöneticisi ise görebilir.
        return $user->managedSites->contains($site);
    }
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function update(User $user, Site $site): bool
    {
        // 'view' ile aynı mantık çalışır, çünkü güncelleme için de görme yetkisi gerekir.
        //return $user->can('manage sites') && $user->managedSites->contains($site);
        return $user->managedSites->contains($site);
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {

        return $user->can('manage sites');
    }

    /**
     * Determine whether the user can update the model.
     */
    /*
    public function update(User $user, Site $site): bool
    {
        /*
        if ($user->hasRole('site-admin')) {
            //return $user->managedSites()->where('site_id', $site->id)->exists();
            return $user->managedSites->contains($site);
        }
        return false;
        */
        // 'view' ile aynı mantık çalışır.
        /*
        if ($user->can('manage sites') && !$user->hasRole('super-admin')) {
            return $user->managedSites->contains($site);
        }
        return $user->can('manage sites');*//*
        return $user->can('manage sites') && $user->managedSites()->where('site_id', $site->id)->exists();
    }*/

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Site $site): bool
    {
        return $user->hasRole('super-admin') && $user->managedSites()->where('site_id', $site->id)->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Site $site): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Site $site): bool
    {
        return false;
    }
}
