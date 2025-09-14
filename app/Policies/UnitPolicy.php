<?php

namespace App\Policies;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;


class UnitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    use HandlesAuthorization;

    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['site-admin', 'block-admin']);
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Unit $unit): bool
    {
        // Önce birimin ait olduğu bloğa erişim sağla.
        $block = $unit->block;

        // Blok bilgisi null ise erişime izin verme.
        if (is_null($block)) {
            return false;
        }
/*
        if ($user->hasRole('site-admin')) {
            return $user->managedSites->contains($unit->block->site_id);
        }
        if ($user->hasRole('block-admin')) {
            return $user->managedBlocks->contains($unit->block_id);
        }
        if ($user->hasRole('property-owner')) {
            return $user->unit->owner_id === $user->id;
        }
        return false;*/
        // 'site-admin' ise yönettiği sitelerin arasında birimin sitesini kontrol et.
        if ($user->hasRole('site-admin')) {
            return $user->managedSites()->where('id', $block->site_id)->exists();
        }

        // 'block-admin' ise yönettiği blokların arasında birimin bloğunu kontrol et.
        if ($user->hasRole('block-admin')) {
            return $user->managedBlocks()->where('id', $block->id)->exists();
        }

        // Eğer birim sahibi ise kendi birimini görebilir.
        if ($user->hasRole('residence') || $user->hasRole('owner')) {
            return $user->id === $unit->owner_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['site-admin']);
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Unit $unit): bool
    {
        if ($user->hasRole('site-admin')) {
            return $user->manages_site_id === $unit->block->site_id;
        }
        if ($user->hasRole('block-admin')) {
            return $user->manages_block_id === $unit->block_id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Unit $unit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Unit $unit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Unit $unit): bool
    {
        return false;
    }
}
