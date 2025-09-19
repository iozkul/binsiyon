<?php

namespace App\Services;

use App\Models\User;
use App\Models\Unit;
use App\Models\ResidentHistory; // Yeni oluşturulacak model
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MoveResidentService
{
    public function handle(User $user, Unit $newUnit, Carbon $moveDate): bool
    {
        try {
            DB::transaction(function () use ($user, $newUnit, $moveDate) {
                // 1. Mevcut (eski) birimi bul ve geçmişe kaydet
                $currentUnit = $user->unit;
                if ($currentUnit) {
                    ResidentHistory::updateOrCreate(
                        ['user_id' => $user->id, 'unit_id' => $currentUnit->id, 'end_date' => null],
                        ['end_date' => $moveDate->copy()->subDay()] // Taşınma gününden bir gün önce bitir
                    );
                }

                // 2. Yeni birim için yeni bir sakinlik geçmişi oluştur
                ResidentHistory::create([
                    'user_id' => $user->id,
                    'unit_id' => $newUnit->id,
                    'site_id' => $newUnit->block->site_id,
                    'start_date' => $moveDate,
                    'end_date' => null,
                ]);

                // 3. Kullanıcının mevcut birim ve site bilgilerini güncelle
                $user->unit_id = $newUnit->id;
                $user->site_id = $newUnit->block->site_id; // site_id'yi de güncelle
                $user->save();

                // 4. Finansal İşlemler (Örnek: Olay Fırlatma)
                // Burada eski borçların sonlandırılması ve yeni aidatların başlatılması
                // için bir Event fırlatılabilir.
                // event(new ResidentMoved($user, $currentUnit, $newUnit, $moveDate));

                // 5. Audit Log (Observer bunu zaten yakalayacaktır)
            });
        } catch (\Exception $e) {
            // Hata durumunda loglama yapabilir ve false dönebilirsiniz.
            report($e);
            return false;
        }

        return true;
    }
}
