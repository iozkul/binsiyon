<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        // Raporları sadece 'view reports' yetkisi olanlar görebilir.
        $this->middleware('can:view reports');
    }

    public function incomeExpense(Request $request)
    {
        $siteId = Auth::user()->site_id; // veya seçili site
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $incomes = Income::where('site_id', $siteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $expenses = Expense::where('site_id', $siteId)
            ->where('status', 'paid') // Sadece ödenmiş giderler
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->sum('amount');

        return view('reports.income-expense', compact('incomes', 'expenses', 'startDate', 'endDate'));
    }

    public function debtors(Request $request)
    {
        $siteId = Auth::user()->site_id;

        // Bu sorgu daha karmaşık olacaktır. User'ların Fee'lerini ve Payment'larını toplayıp farkını almanız gerekir.
        // Örnek bir mantık:
        $debtors = User::where('site_id', $siteId)
            ->withSum('fees', 'amount')
            ->withSum('payments', 'amount')
            ->get()
            ->filter(function ($user) {
                return ($user->fees_sum_amount - $user->payments_sum_amount) > 0;
            });

        return view('reports.debtors', compact('debtors'));
    }
}
