<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Site;

class ActiveContextController extends Controller
{
    /**
     * Site seçim sayfasını gösterir.
     */
    public function selectSite()
    {
        $user = Auth::user();
        $managedSites = $user->managedSites;

        // Eğer yönettiği sadece 1 site varsa, otomatik seç ve dashboard'a yönlendir.
        if ($managedSites->count() === 1) {
            session(['active_site_id' => $managedSites->first()->id]);
            return Redirect::route('dashboard');
        }

        return view('context.select-site', ['managedSites' => $managedSites]);
    }

    /**
     * Kullanıcının aktif sitesini değiştirir.
     */
    public function switchSite(Request $request)
    {
        $request->validate([
            'site_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $siteId = $request->input('site_id');

        // Kullanıcının bu siteyi yönetme yetkisi olup olmadığını kontrol et. (Güvenlik)
        if ($user->managedSites()->where('sites.id', $siteId)->exists() || $user->hasRole('super-admin')) {
            session(['active_site_id' => $siteId]);
        } else {
            return Redirect::back()->with('error', 'Bu siteyi yönetme yetkiniz yok.');
        }

        return Redirect::to($request->input('redirect_to', route('dashboard')));
    }
}
