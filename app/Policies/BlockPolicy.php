<?php

namespace App\Policies;

use App\Models\Block;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlockPolicy
{
	use HandlesAuthorization;

	 public function __construct()
    {
        //dd('BlockPolicy Dosyası Çalışıyor!');
    }

    /**
     * Determine whether the user can view any models.
     */
	 public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super-admin')) {
			//dd('BlockPolicy->before: "super-admin" rolü bulundu, yetki VERİLDİ.');
            return true;
        }
		    //dd('BlockPolicy->before: Kullanıcı "super-admin" değil, diğer kurallara bakılacak.');

        return null;
    }

    public function viewAny(User $user): bool
    {
        // Yönetici rollerine sahip herkes blok listesini görebilir.
        //return $user->hasAnyRole(['super-admin', 'site-admin', 'block-admin']);
        return $user->can('manage blocks');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Block $block): bool
    {
        return $user->can('manage blocks') && $user->managedSites()->where('site_id', $block->id)->exists();
        // Site Yöneticisi, kendi sitesindeki bloğu görebilir.
        /*
        if ($user->hasRole('site-admin')) {
            return $user->managedSites->contains($block->site_id);
        }
        // Blok Yöneticisi, kendi bloğunu görebilir.
        if ($user->hasRole('block-admin')) {
            return $user->managedBlocks->contains($block);
        }
        return false; // Diğer roller (resident gibi) göremez.
        */
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'site-admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Block $block): bool
    {
		if ($user->hasRole('site-admin')) {
            return $user->managedSites->contains($block->site_id);
        }
        if ($user->hasRole('block-admin')) {
            return $user->managedBlocks->contains($block);
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Block $block): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Block $block): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Block $block): bool
    {
        return false;
    }
}
