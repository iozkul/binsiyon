<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class SiteScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Kullanıcı giriş yapmamışsa (örn: artisan komutları) veya super-admin ise, scope'u uygulama.
        if (!Auth::check() || Auth::user()->hasRole('super-admin')) {
            return;
        }
        $user = Auth::user();
        $tableName = $model->getTable();
        // Eğer kullanıcı site-admin, block-admin, accountant gibi site bazlı bir role sahipse
        if (Auth::user()->hasAnyRole(['site-admin', 'block-admin', 'accountant', 'staff'])) {
            $userSiteId = Auth::user()->site_id;

            if ($userSiteId) {
                // Modelin tablosunda 'site_id' kolonu varsa, sorguyu filtrele.
                if (Schema::hasColumn($model->getTable(), 'site_id')) {
                    //$builder->where($model->getTable() . '.site_id', $userSiteId);
                    $activeSiteId = session('active_site_id');

                    if ($activeSiteId && $activeSiteId !== 'all') {
                        // Kullanıcının bu siteye erişim yetkisi var mı diye kontrol et
                        if ($user->sites()->where('site_id', $activeSiteId)->exists()) {
                            $builder->where($tableName . '.site_id', $activeSiteId);
                        } else {
                            // Yetkisi olmayan bir site seçiliyse, hiçbir veri döndürme
                            //$builder->whereRaw('1 = 0');
                            // Aktif site seçili değilse, kullanıcının yönettiği tüm sitelerin verisini getir
                            $managedSiteIds = $user->sites()->pluck('id')->toArray();
                            $builder->whereIn($tableName . '.site_id', $managedSiteIds);
                        }
                }
            } else {
                // Eğer site bazlı bir role sahip ama site_id'si yoksa, hiçbir şey görmemeli
                $builder->whereRaw('1 = 0');
            }
        }
    }
}
}
