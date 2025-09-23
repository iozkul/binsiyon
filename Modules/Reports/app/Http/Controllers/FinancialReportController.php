<?php

namespace Modules\Reports\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Site;

class FinancialReportController extends Controller
{
    /**
     * Display a financial summary report.
     *
     * @return \Illuminate\View\View
     */
    public function summary()
    {
        $this->authorize('view reports');

        $activeSiteId = session('active_site_id');

        if (!$activeSiteId) {
            return redirect()->route('dashboard')->with('error', 'Lütfen bir site seçin.');
        }

        $summary = [];

        if ($activeSiteId === 'all') {
            // Tüm siteler için özet dashboard verisi
            $user = auth()->user();
            $siteIds = $user->hasRole('super-admin')
                ? Site::pluck('id')
                : $user->sites()->pluck('id');

            $summary['title'] = 'Tüm Siteler Finansal Özet Raporu';
            $summary['total_income'] = DB::table('incomes')->whereIn('site_id', $siteIds)->sum('amount');
            $summary['total_expense'] = DB::table('expenses')->whereIn('site_id', $siteIds)->sum('amount');

        } else {
            // Tek bir site için özet
            $site = Site::findOrFail($activeSiteId);
            $summary['title'] = $site->name . ' - Finansal Özet Raporu';
            $summary['total_income'] = DB::table('incomes')->where('site_id', $activeSiteId)->sum('amount');
            $summary['total_expense'] = DB::table('expenses')->where('site_id', $activeSiteId)->sum('amount');
        }

        $summary['balance'] = $summary['total_income'] - $summary['total_expense'];

        return view('reports::financial.summary', compact('summary'));
    }
}
