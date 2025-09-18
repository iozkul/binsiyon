<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Fee;
use App\Models\LateFee;
use Carbon\Carbon;
use App\Models\Income;
use App\Models\Payment;
use App\Models\Expense;

class FinanceController extends Controller
{
    public function index()
    {
        $siteId = Auth::user()->site_id;

        if (!$siteId && !Auth::user()->hasRole('super-admin')) {
            // Super-admin değilse ve bir sitesi yoksa, finansal veri göremez.
            return view('finance.index', [
                'totalIncome' => 0,
                'totalExpense' => 0,
                'totalDebt' => 0,
                'error' => 'Herhangi bir siteye yönetici olarak atanmamışsınız.'
            ]);
        }

        // Artık sorgular doğrudan ve çok daha hızlı çalışacak.
        $totalIncome = Income::where('site_id', $siteId)->sum('amount');
        $totalExpense = Expense::where('site_id', 'paid')->sum('amount');

        $totalDues = Fee::where('site_id', $siteId)->sum('amount');
        $totalPayments = Payment::where('site_id', $siteId)->sum('amount');
        $totalDebt = $totalDues - $totalPayments;

        return view('finance.index', compact('totalIncome', 'totalExpense', 'totalDebt'));
    }

    public function calculateLateFees(Request $request)
    {
        $siteId = Auth::user()->site_id;
        if (!$siteId) {
            return redirect()->back()->with('error', 'İşlem yapılacak bir site seçili değil.');
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
