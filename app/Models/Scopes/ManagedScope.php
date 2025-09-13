<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use App\Models\Site;
use App\Models\Block;
use App\Models\User;

class ManagedScope implements Scope
{
    /**
     * Scope'un tekrar çalışmasını engelleyen bayrak.
     * @var bool
     */
    protected static $disabled = false;

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model)
    {
        // Eğer scope zaten çalışıyorsa veya devre dışı bırakılmışsa, tekrar çalıştırma.
        if (static::$disabled) {
            return;
        }

        // Auth::user() çağrısı yapmadan önce scope'u geçici olarak devre dışı bırak.
        static::$disabled = true;

        try {
            // Auth::user() artık bu scope'u tetiklemeyecek.
            $user = Auth::user();

            if ($user && !$user->hasRole('super-admin')) {
                if ($user->hasRole('site-admin')) {
                    $managedSiteIds = $user->managedSites()->pluck('sites.id');

                    if ($model instanceof Site) {
                        $builder->whereIn('id', $managedSiteIds);
                    } elseif ($model instanceof Block || $model instanceof User) {
                        $builder->whereIn('site_id', $managedSiteIds);
                    }
                } elseif ($user->hasRole('block-admin')) {
                    $managedBlockIds = $user->managedBlocks()->pluck('blocks.id');
                    if ($model instanceof Block) {
                        $builder->whereIn('id', $managedBlockIds);
                    } elseif ($model instanceof User) {
                        $builder->whereHas('unit', function ($q) use ($managedBlockIds) {
                            $q->whereIn('block_id', $managedBlockIds);
                        });
                    }
                }
            }
        } finally {
            // Scope'un işi bittikten sonra tekrar etkinleştir.
            static::$disabled = false;
        }
    }
    /*
    public function apply(Builder $builder, Model $model)
    {
        $user = Auth::user();
        // Eğer kullanıcı giriş yapmışsa ve super-admin DEĞİLSE devam et
        if ($user && !$user ->hasRole('super-admin')) {

            if ($user->hasRole('site-admin')) {
                // Yöneticinin sorumlu olduğu site ID'lerini al
                $managedSiteIds = $user->managedSites()->pluck('sites.id');

                // Sorgulanan modelin 'sites' tablosu olup olmadığını kontrol et
                if ($model instanceof \App\Models\Site) {
                    // Eğer modelin kendisi Site ise, direkt ID üzerinden filtrele
                    $builder->whereIn('sites.id', $managedSiteIds);
                } else {
                    // Değilse (Block, Unit, Fee vb.), 'site_id' sütunu üzerinden filtrele
                    $builder->whereIn('site_id', $managedSiteIds);
                }
            }
            elseif ($user->hasRole('block-admin')) {
                // Yöneticinin sorumlu olduğu blok ID'lerini al
                $managedBlockIds = $user->managedBlocks()->pluck('blocks.id');

                if ($model instanceof \App\Models\Block) {
                    $builder->whereIn('blocks.id', $managedBlockIds);
                } else {
                    $builder->whereIn('block_id', $managedBlockIds);
                }
            }
        }
    }
    */
}
