<?php

namespace Modules\Finance\app\Services;

use Modules\Finance\app\Models\MonthlyDue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class MonthlyDueService
{
    /**
     * Kullanıcının yetkilerine göre ana sorguya otomatik kapsam (scope) ekler.
     */
    private function applyUserScope(Builder $query): Builder
    {
        $user = Auth::user();

        // Super Admin her şeyi görür.
        if ($user->hasRole('super_admin')) {
            return $query;
        }

        // Site Yöneticisi sadece kendi sitelerini görür.
        if ($user->hasRole('site_owner')) {
            // TODO: User modelinde `managedSites()` ilişkisi tanımlanmalı.
            // Bu ilişki, kullanıcının yönetici olduğu siteleri döndürmelidir.
            // Örnek: return $this->belongsToMany(Site::class, 'site_user', 'user_id', 'site_id');
            // $managedSiteIds = $user->managedSites()->pluck('id');
            // return $query->whereIn('site_id', $managedSiteIds);
        }

        // Blok Yöneticisi sadece kendi bloklarındaki daireleri görür.
        if ($user->hasRole('block_admin')) {
            // TODO: User modelinde `managedBlocks()` ilişkisi tanımlanmalı.
            // $managedBlockIds = $user->managedBlocks()->pluck('id');
            // return $query->whereHas('apartment', function ($q) use ($managedBlockIds) {
            //     $q->whereIn('block_id', $managedBlockIds);
            // });
        }

        // Diğer roller için varsayılan olarak boş sonuç döndürerek güvenliği sağla.
        return $query->whereRaw('1 = 0');
    }

    public function getFilteredDues(array $filters)
    {
        $query = MonthlyDue::with(['site', 'apartment', 'resident']);

        // GÜVENLİK ADIMI: Sorguya otomatik olarak kullanıcı kapsamını uygula.
        $query = $this->applyUserScope($query);

        if (!empty($filters['site_id'])) {
            $query->where('site_id', $filters['site_id']);
        }
        if (!empty($filters['apartment_id'])) {
            $query->where('apartment_id', $filters['apartment_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(15)->withQueryString();
    }

    public function create(array $data): MonthlyDue
    {
        // TODO: Data içerisindeki site_id'nin kullanıcının yetkili olduğu bir site olduğunu doğrula.
        // Bu Policy ile de yapılabilir: Gate::authorize('createForSite', [MonthlyDue::class, $data['site_id']]);
        return MonthlyDue::create($data);
    }

    public function update(MonthlyDue $monthlyDue, array $data): bool
    {
        // Policy zaten bu aidatı güncelleme yetkisi olup olmadığını kontrol ediyor.
        return $monthlyDue->update($data);
    }
}
