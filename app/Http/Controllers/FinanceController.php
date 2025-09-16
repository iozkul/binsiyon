<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Fee;
use App\Models\LateFee;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index()
    {
        // Bu yetki kontrolünü eklemek iyi bir pratiktir.
        // Spatie seeder'ınızda 'manage finance' yetkisini oluşturmuştuk.
        $this->authorize('manage finance');

        // Gelecekte buraya aidat, borç gibi verileri çekecek kodlar gelecek.
        // Şimdilik sadece view'ı döndürelim.
        return view('finance.index');
    }

    public function calculateLateFees(Request $request)
    {
        $siteId = Auth::user()->site_id;
        if (!$siteId) {
            return redirect()->back()->with('error', 'Site bilgisi bulunamadı.');
        }

        // Kat Mülkiyeti Kanunu'na göre aylık %5 gecikme tazminatı
        $lateFeeRate = 0.05;

        $overdueFees = Fee::where('site_id', $siteId)
            ->where('due_date', '<', Carbon::today())
            ->where('status', '!=', 'paid')
            ->get();

        $calculatedCount = 0;
        foreach ($overdueFees as $fee) {
            // Bu ay için bu borca daha önce faiz uygulanmış mı kontrol et
            $alreadyApplied = LateFee::where('fee_id', $fee->id)
                ->whereMonth('calculation_date', Carbon::now()->month)
                ->whereYear('calculation_date', Carbon::now()->year)
                ->exists();

            if (!$alreadyApplied) {
                $lateFeeAmount = $fee->amount * $lateFeeRate;

                LateFee::create([
                    'fee_id' => $fee->id,
                    'unit_id' => $fee->unit_id,
                    'amount' => $lateFeeAmount,
                    'calculation_date' => Carbon::today(),
                    'description' => $fee->due_date->format('Y-m') . ' tarihli borç için gecikme tazminatı.'
                ]);

                // Ana borcun tutarını da güncelleyebilirsiniz veya ayrı takip edebilirsiniz.
                // Ayrı takip daha şeffaftır.
                $calculatedCount++;
            }
        }

        if ($calculatedCount > 0) {
            return redirect()->back()->with('success', "$calculatedCount adet borca gecikme tazminatı başarıyla uygulandı.");
        }

        return redirect()->back()->with('info', 'Gecikme tazminatı uygulanacak yeni bir borç bulunamadı.');
    }
}
