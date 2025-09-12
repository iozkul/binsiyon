<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SiteAdminScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Eğer kullanıcı giriş yapmışsa ve super-admin DEĞİLSE devam et
        if (Auth::check() && !Auth::user()->hasRole('super-admin')) {

            // Eğer kullanıcı site-admin ise, sorguyu filtrele
            if (Auth::user()->hasRole('site-admin')) {

                // Yöneticinin sorumlu olduğu site ID'lerini al
                $managedSiteIds = Auth::user()->managedSites()->pluck('id');

                // Sorgulanan modelin (Block, Unit, Fee vb.) site_id sütununu
                // sadece yöneticinin sorumlu olduğu site ID'leri ile eşleştir.
                $builder->whereIn('site_id', $managedSiteIds);
            }
    }
}
}
