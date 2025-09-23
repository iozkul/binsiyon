<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitTransferController extends Controller
{
    public function showTransferForm(Unit $unit)
    {
        $this->authorize('manage units'); // Policy ile yetki kontrolü

        $unit->load('owner', 'debts');
        $outstandingDebts = $unit->debts()->where('status', '!=', 'paid')->get();

        // Sisteme kayıtlı, mülk sahibi olabilecek diğer kullanıcıları listele
        $potentialOwners = User::whereHas('roles', function($q) {
            $q->where('name', 'property-owner');
        })->where('id', '!=', $unit->owner_id)->get();

        return view('finance::units.transfer', compact('unit', 'outstandingDebts', 'potentialOwners'));
    }

    public function processTransfer(Request $request, Unit $unit)
    {
        $this->authorize('manage units');

        $request->validate([
            'new_owner_id' => 'required|exists:users,id',
            'transfer_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $oldOwner = $unit->owner;
        $newOwner = User::find($request->new_owner_id);

        DB::beginTransaction();
        try {
            // 1. Eski sahibi üniteden ayır
            $unit->owner_id = $newOwner->id;

            // 2. Devir notlarını kaydet (yeni bir tablo veya unit'in bir alanına)
            // Örnek: $unit->devir_notu = $request->notes;
            $unit->save();

            // 3. (Opsiyonel) Eski sahibin 'property-owner' rolünü kaldır, yenisine ekle.
            // Bu, kullanıcı yönetimi ekranından manuel de yapılabilir.

            // 4. Denetim kaydı oluştur
            activity()
                ->performedOn($unit)
                ->causedBy(auth()->user())
                ->log("Ünite devri yapıldı. Eski malik: {$oldOwner->name}, Yeni malik: {$newOwner->name}. Devir tarihi: {$request->transfer_date}");

            DB::commit();

            return redirect()->route('units.show', $unit->id)->with('success', 'Ünite devri başarıyla tamamlandı. Mevcut borçlar yeni malike yansıtılmıştır.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Devir işlemi sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }
}
