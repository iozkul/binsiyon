<?php

namespace Modules\Finance\app\Services;

use App\Models\User;
use Modules\Finance\app\Models\MonthlyDue;
use App\Models\Site;

class DashboardService
{
    public function getResidentData(User $user): array
    {
        $dues = MonthlyDue::where('resident_user_id', $user->id)->get();

        return [
            'totalDue' => $dues->whereIn('status', ['pending', 'overdue'])->sum('amount'),
            'paidDue' => $dues->where('status', 'paid')->sum('amount'),
            'upcomingDues' => $dues->where('status', 'pending')->sortBy('due_date')->take(5),
        ];
    }

    public function getPropertyOwnerData(User $user): array
    {
        // Mülk sahibinin sahip olduğu dairelerin resident_user_id'lerini bul
        // TODO: User modelinde ownedApartments() ilişkisi tanımlanmalı
        // $residentIds = $user->ownedApartments()->pluck('resident_user_id')->filter();
        // $dues = MonthlyDue::whereIn('resident_user_id', $residentIds)->get();

        // Şimdilik resident ile aynı datayı döndürelim
        return $this->getResidentData($user);
    }

    public function getSiteAdminData(User $user): array
    {
        // TODO: User modelinde managedSites() ilişkisi tanımlanmalı
        // $siteIds = $user->managedSites()->pluck('id');
        $dues = MonthlyDue::/*whereIn('site_id', $siteIds)->*/get(); // Scope devrede olduğu için tümünü çekebiliriz

        return [
            'totalCollection' => $dues->where('status', 'paid')->sum('amount'),
            'totalPending' => $dues->whereIn('status', ['pending', 'overdue'])->sum('amount'),
            'totalSites' => Site::count(), // $siteIds->count() olmalı
        ];
    }

    // getBlockAdminData ve getSuperAdminData metodları da benzer şekilde doldurulabilir.
    public function getSuperAdminData(): array
    {
        $dues = MonthlyDue::all();
        return [
            'totalCollection' => $dues->where('status', 'paid')->sum('amount'),
            'totalPending' => $dues->whereIn('status', ['pending', 'overdue'])->sum('amount'),
            'totalSites' => Site::count(),
            'totalUsers' => User::count(),
        ];
    }

    public function getBlockAdminData(User $user): array
    {
        // Mülk sahibinin sahip olduğu dairelerin resident_user_id'lerini bul
        // TODO: User modelinde ownedApartments() ilişkisi tanımlanmalı
        // $residentIds = $user->ownedApartments()->pluck('resident_user_id')->filter();
        // $dues = MonthlyDue::whereIn('resident_user_id', $residentIds)->get();

        // Şimdilik resident ile aynı datayı döndürelim
        return $this->getResidentData($user);
    }
}
