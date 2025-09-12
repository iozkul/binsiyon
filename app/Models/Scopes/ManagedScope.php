<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class ManagedScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Eğer kullanıcı giriş yapmışsa ve super-admin DEĞİLSE devam et
        if (Auth::check() && !Auth::user()->hasRole('super-admin')) {

            $user = Auth::user();

            if ($user->hasRole('site-admin')) {
                // Yöneticinin sorumlu olduğu site ID'lerini al
                $managedSiteIds = $user->managedSites()->pluck('id');

                // Sorgulanan modelin 'sites' tablosu olup olmadığını kontrol et
                if ($model instanceof \App\Models\Site) {
                    // Eğer modelin kendisi Site ise, direkt ID üzerinden filtrele
                    $builder->whereIn('id', $managedSiteIds);
                } else {
                    // Değilse (Block, Unit, Fee vb.), 'site_id' sütunu üzerinden filtrele
                    $builder->whereIn('site_id', $managedSiteIds);
                }
            }
            elseif ($user->hasRole('block-admin')) {
                // Yöneticinin sorumlu olduğu blok ID'lerini al
                $managedBlockIds = $user->managedBlocks()->pluck('id');

                if ($model instanceof \App\Models\Block) {
                    $builder->whereIn('id', $managedBlockIds);
                } else {
                    $builder->whereIn('block_id', $managedBlockIds);
                }
            }
        }
    }
}
