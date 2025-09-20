<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Site;
use App\Models\Fee;
use App\Models\Expense;
use App\Models\Announcement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the appropriate dashboard based on the user's role.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $data = [];
        $activeSiteId = session('active_site_id');
/*
        if ($activeSiteId === 'all') {
            $user = auth()->user();
            $siteIds = $user->hasRole('super-admin')
                ? Site::pluck('id')
                : $user->sites()->pluck('id');

            $stats = [
                'total_sites' => $siteIds->count(),
                'total_units' => DB::table('units')->whereIn('site_id', $siteIds)->count(),
                'total_income' => DB::table('incomes')->whereIn('site_id', $siteIds)->sum('amount'),
                'total_expense' => DB::table('expenses')->whereIn('site_id', $siteIds)->sum('amount'),
            ];
            //return view('dashboard-aggregate', compact('stats'));
        }*/
        if ($user->hasRole('super-admin')) {
            $siteIds = $user->hasRole('super-admin')
                ? Site::pluck('id')
                : $user->sites()->pluck('id');


            $view = 'admin.super_admin_dashboard';
            $data = $this->getSuperAdminData();
        } elseif ($user->hasRole('site-admin')) {
            $view = 'dashboards.site-admin';
            $data = $this->getSiteAdminData($user);
        } elseif ($user->hasRole('block-admin')) {
            $view = 'dashboards.block-admin';
            $data = $this->getBlockAdminData($user);
        } elseif ($user->hasRole('accountant')) {
            $view = 'dashboards.accountant';
            $data = $this->getAccountantData($user);
        } elseif ($user->hasRole('resident')) {
            $view = 'dashboards.resident';
            $data = $this->getResidentData($user);
        } elseif ($user->hasRole('property-owner')) {
            $view = 'dashboards.property-owner';
            $data = $this->getPropertyOwnerData($user);
        } elseif ($user->hasRole('staff')) {
            $view = 'dashboards.staff';
            $data = $this->getStaffData($user);
        } elseif ($user->hasRole('auditor')) {
            $view = 'dashboards.auditor';
            $data = $this->getAuditorData($user);
        } else {
            // Varsayılan dashboard
            $view = 'dashboard';
        }
        $stats = [
            'total_sites' => $siteIds->count(),
            'total_units' => DB::table('units')->whereIn('site_id', $siteIds)->count(),
            'total_income' => DB::table('incomes')->whereIn('site_id', $siteIds)->sum('amount'),
            'total_expense' => DB::table('expenses')->whereIn('site_id', $siteIds)->sum('amount'),
        ];
        return view($view, $data, compact('stats'));
    }
    public function setActiveSite($siteId, Request $request)
    {
        $user = auth()->user();
        if ($siteId !== 'all' && !$user->hasRole('super-admin') && !$user->sites->contains($siteId)) {
            abort(403, 'Bu siteyi yönetme yetkiniz yok.');
        }

        session(['active_site_id' => $siteId]);
        return redirect()->route('dashboard');
    }
    // --- Her Rol İçin Veri Çekme Metotları ---

    private function getSuperAdminData()
    {
        return [
            'total_sites' => Site::count(),
            'total_users' => \App\Models\User::count(),
            // Diğer global metrikler...
        ];
    }

    private function getSiteAdminData($user)
    {
        $siteId = $user->site_id;
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return [
            'total_income_this_month' => Fee::where('site_id', $siteId)->whereBetween('paid_at', [$startOfMonth, $endOfMonth])->sum('amount'),
            'total_expense_this_month' => Expense::where('site_id', $siteId)->whereBetween('expense_date', [$startOfMonth, $endOfMonth])->sum('amount'),
            'due_fees_count' => Fee::where('site_id', $siteId)->where('status', 'unpaid')->where('due_date', '<', Carbon::now())->count(),
            'latest_announcements' => Announcement::where('site_id', $siteId)->latest()->take(5)->get(),
        ];
    }

    private function getAccountantData($user)
    {
        // getSiteAdminData ile benzer olabilir, finansal odaklı eklemeler yapılabilir.
        $siteId = $user->site_id;
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return [
            'total_income_this_month' => Fee::where('site_id', $siteId)->whereBetween('paid_at', [$startOfMonth, $endOfMonth])->sum('amount'),
            'total_expense_this_month' => Expense::where('site_id', $siteId)->whereBetween('expense_date', [$startOfMonth, $endOfMonth])->sum('amount'),
            'total_due_amount' => Fee::where('site_id', $siteId)->where('status', 'unpaid')->where('due_date', '<', Carbon::now())->sum('amount'),
            'latest_payments' => \App\Models\Payment::where('site_id', $siteId)->latest()->take(10)->get(),
        ];
    }


    private function getResidentData($user)
    {
        return [
            'my_total_debt' => Fee::where('user_id', $user->id)->where('status', 'unpaid')->sum('amount'),
            'my_latest_fees' => Fee::where('user_id', $user->id)->latest()->take(5)->get(),
            'latest_announcements' => Announcement::where('site_id', $user->site_id)->latest()->take(5)->get(),
        ];
    }

    // Diğer roller için benzer metotlar (getBlockAdminData, getPropertyOwnerData vb.) oluşturulmalıdır.
    // Bu metotlar, kullanıcının ilişkili olduğu blok veya mülkleri dikkate alarak veri çekmelidir.

    private function getBlockAdminData($user) {
        // block_user pivot tablosundan kullanıcının yönettiği blokları al
        $blockIds = $user->blocks()->pluck('blocks.id');

        return [
            'block_names' => $user->blocks()->pluck('blocks.name')->implode(', '),
            'due_fees_count_in_blocks' => Fee::whereIn('block_id', $blockIds)->where('status', 'unpaid')->where('due_date', '<', Carbon::now())->count(),
            'latest_announcements_in_blocks' => Announcement::whereIn('block_id', $blockIds)->latest()->take(5)->get(),
        ];
    }

    private function getPropertyOwnerData($user) {
        // Mülk sahibinin sahip olduğu unit'leri bul
        $unitIds = \App\Models\Unit::where('owner_id', $user->id)->pluck('id');

        return [
            'total_units' => $unitIds->count(),
            'units_total_debt' => Fee::whereIn('unit_id', $unitIds)->where('status', 'unpaid')->sum('amount'),
            'latest_announcements' => Announcement::where('site_id', $user->site_id)->latest()->take(5)->get(),
        ];
    }

    private function getStaffData($user) {
        return [
            // Staff için görev/bakım modülü entegre edildiğinde burası doldurulacak.
            'assigned_tasks' => [],
        ];
    }

    private function getAuditorData($user) {
        // getAccountantData ile benzer veriler sunulabilir, fakat sadece görüntüleme amaçlı.
        $siteId = $user->site_id;
        return [
            'total_income_all_time' => \App\Models\Income::where('site_id', $siteId)->sum('amount'),
            'total_expense_all_time' => Expense::where('site_id', $siteId)->sum('amount'),
            'latest_transactions' => \App\Models\Transaction::where('site_id', $siteId)->latest()->take(20)->get(),
        ];
    }

}
