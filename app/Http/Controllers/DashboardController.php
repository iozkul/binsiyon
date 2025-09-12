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


        // 1. SÜPER-ADMIN İÇİN ÖZEL DASHBOARD
        // Süper-admin tüm sistemi görür.
        if ($user->hasRole('super-admin')) {

            // Yavaş olan User::count() yerine HIZLI olan yaklaşık sayım yöntemini kullanıyoruz.
            $totalUsers = Cache::remember('stats_total_users', now()->addMinutes(5), function () {
                $lastUser = User::latest('id')->first();
                return $lastUser ? $lastUser->id : 0; // Yaklaşık kullanıcı sayısı
            });

            $stats = [
                'total_users' => $totalUsers,
                'total_sites' => Site::count(), // Site sayısı az olduğu için bu hızlı çalışır.

            ];
            $recent_users = User::latest()->take(5)->get();
            $recent_sites = Site::latest()->take(5)->get();

            return view('admin.super_admin_dashboard', compact( // Farklı bir view dosyası
                'user',
                'stats',
                'recent_users',
                'recent_sites'
            ));
        }
/*
        // 2. SITE-ADMIN İÇİN ÖZEL DASHBOARD
        // Site-admin sadece kendi sitesiyle ilgili verileri görür.
        if ($user->hasAnyRole(['site-admin', 'block-admin'])) {
            $siteId = $user->site_id;
            $stats = [
                // Performans için yavaş olan User::count() sorgusunu kaldırıp,
                // sadece o sitedeki kullanıcıları sayıyoruz.
                'total_users' => User::where('site_id', $siteId)->count(),
                'total_sites' => 1,
            ];

            // Değişken adını view'in beklediği 'recent_users' yapıyoruz.
            $recent_users = User::where('site_id', $siteId)->latest()->take(5)->get();

            // super-admin'de olduğu gibi recent_sites değişkenini de ekleyelim ki view hata vermesin.
            // Boş bir collection gönderiyoruz.
            $recent_sites = collect();

            return view('admin.dashboard', compact('user', 'stats', 'recent_users', 'recent_sites'));
        }
*/
        // 2. YÖNETİCİ (SITE-ADMIN / BLOCK-ADMIN) DASHBOARD'U
        if ($user->hasAnyRole(['site-admin', 'block-admin'])) {
            $stats = [];
            $recent_users = collect();

            if ($user->hasRole('site-admin')) {
                // Site-admin ise, yönettiği sitelerdeki kullanıcıları say
                $managedSiteIds = $user->managedSites()->pluck('id');
                $stats['total_users'] = User::whereIn('site_id', $managedSiteIds)->count();
                $recent_users = User::whereIn('site_id', $managedSiteIds)->latest()->take(5)->get();

            } elseif ($user->hasRole('block-admin')) {
                // Block-admin ise, yönettiği bloklardaki kullanıcıları say
                $managedBlockIds = $user->managedBlocks()->pluck('blocks.id');
                // Bu bloklara bağlı birimlerdeki (unit) kullanıcıları bul
                $stats['total_users'] = User::whereHas('unit', function ($query) use ($managedBlockIds) {
                    $query->whereIn('block_id', $managedBlockIds);
                })->count();
                $recent_users = User::whereHas('unit', function ($query) use ($managedBlockIds) {
                    $query->whereIn('block_id', $managedBlockIds);
                })->latest()->take(5)->get();
            }

            // View'ın hata vermemesi için bu değişkenleri her zaman tanımlıyoruz
            $recent_sites = collect();
            $stats['total_sites'] = $user->managedSites()->count() ?: 1; // Yönettiği site yoksa 1 göster

            return view('admin.dashboard', compact('user', 'stats', 'recent_users', 'recent_sites'));
        }

        // 3. MÜLK SAHİBİ PANELİ
        if ($user->hasRole('property-owner') && !$user->unit_id) {
            $ownedUnits = $user->ownedUnits()->with('residents')->get();
            return view('owner.dashboard', compact('ownedUnits'));
        }

        // 4. SAKİN PANELİ (Bu kısım zaten hızlı çalışıyordu)
        $user->load('unit.block.site', 'unit.parkingSpaces');
        $unpaidFees = $user->fees()->whereNull('paid_at')->get();

        return view('residents.dashboard', compact('user', 'unpaidFees'));
    }
}
