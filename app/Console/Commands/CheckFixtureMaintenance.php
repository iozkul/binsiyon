<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fixture;
use App\Models\User;
use App\Notifications\FixtureMaintenanceDue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class CheckFixtureMaintenance extends Command
{
    protected $signature = 'fixtures:check-maintenance';
    protected $description = 'Checks for upcoming fixture maintenances and sends notifications.';

    public function handle()
    {
        $this->info('Demirbaş bakım kontrolü başlatılıyor...');

        // Bakım tarihi 7 gün içinde olan veya geçmiş olan demirbaşları bul
        $fixturesNeedingMaintenance = Fixture::with('site')
            ->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<=', Carbon::now()->addDays(7))
            ->get();

        if ($fixturesNeedingMaintenance->isEmpty()) {
            $this->info('Bakımı yaklaşan demirbaş bulunamadı.');
            return;
        }

        $this->info($fixturesNeedingMaintenance->count() . ' adet demirbaş için bildirim gönderilecek.');

        // Demirbaşları sitelerine göre grupla
        $fixturesBySite = $fixturesNeedingMaintenance->groupBy('site_id');

        foreach ($fixturesBySite as $siteId => $fixtures) {
            // İlgili siteye atanmış admin rolüne sahip kullanıcıları bul
            $admins = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();

            // Eğer belirli bir site yöneticisi rolünüz varsa (örneğin 'site-manager')
            // ve kullanıcılar sitelere atanmışsa, sorguyu şu şekilde değiştirebilirsiniz:
            // $siteManagers = User::where('site_id', $siteId)->whereHas('roles', ...)->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new FixtureMaintenanceDue($fixtures, $fixtures->first()->site));
                $this->info($fixtures->first()->site->name . ' sitesi için ' . $admins->count() . ' yöneticiye bildirim gönderildi.');
            }
        }

        $this->info('Demirbaş bakım kontrolü tamamlandı.');
        return 0;
    }
}
