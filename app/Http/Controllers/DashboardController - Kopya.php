<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Site;

use App\Models\SupportTicket;
use App\Models\Message;
use App\Models\Payment;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $userRoleNames = $user->getRoleNames();

        // 2. Kullanıcının rollerinden herhangi birinin yönetici listesinde olup olmadığını kontrol ediyoruz.
        $adminRoles = ['super-admin', 'site-admin', 'block-admin'];
        $isUserAdmin = $userRoleNames->intersect($adminRoles)->isNotEmpty();
/*
        if ($isUserAdmin) {

            // Bu blok artık doğru bir şekilde çalışacak.
            $stats = [
                'total_users' => User::count(),
                'total_sites' => Site::count(),
            ];
            $recent_users = User::latest()->take(5)->get();
            $recent_sites = Site::latest()->take(5)->get();

            return view('admin.dashboard', compact(
                'user',
                'stats',
                'recent_users',
                'recent_sites'
            ));
        }*/
        // Eğer kullanıcı super-admin veya site-admin ise, ana yönetim paneline yönlendir.
        if ($user->hasAnyRole(['super-admin', 'site-admin', 'block-admin'])) {

            // 2. Yönetici paneli için gerekli tüm verileri bu blokta oluşturuyoruz.
            $stats = [
                'total_users' => Cache::remember('stats_total_users', now()->addMinutes(5), function () {
                    return User::count();
                }),
                'total_sites' => Cache::remember('stats_total_sites', now()->addMinutes(5), function () {
                    return Site::count();
                }),
            ];
            // Yönetim paneli için ayrı bir view oluşturabilir veya
            // basit bir hoşgeldin sayfası gösterebilirsiniz.
            return view('admin.dashboard', compact('user'));
        }

        // Eğer kullanıcı mülk sahibi ise, onu mülk sahibi paneline yönlendir.
        if ($user->hasRole('property-owner') && !$user->unit_id) { // Kendisi oturmuyor ama mülk sahibi
            $ownedUnits = $user->ownedUnits()->with('residents')->get();
            return view('owner.dashboard', compact('ownedUnits'));
        }

        // Diğer tüm roller (resident, staff, kendi evinde oturan mülk sahibi)
        // standart sakin dashboard'unu görür.
        $user->load('unit.block.site', 'unit.parkingSpaces');

        // Bu kullanıcıya ait ödenmemiş aidatları (fees) çek
        $unpaidFees = $user->fees()->whereNull('paid_at')->get();

        // Bu kullanıcıya gönderilen duyuruları çek
        // $announcements = ...

        return view('residents.dashboard', [
            'user' => $user,
            'unpaidFees' => $unpaidFees,
        ]);
    }
}
