<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Site;

class SiteSwitchController extends Controller
{
    /**
     * Kullanıcının aktif çalışma sitesini değiştirir ve session'a kaydeder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $siteId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchSite(Request $request, $siteId)
    {
        // Kullanıcının bu siteye erişim yetkisi var mı diye kontrol et.
        // Bu, bir kullanıcının URL'i değiştirerek yetkisi olmayan bir sitenin
        // verilerini görmesini engeller.
        $user = Auth::user();
        $allowedSites = $user->sites()->pluck('id')->toArray();

        // super-admin her siteyi seçebilir.
        if (!$user->hasRole('super-admin') && !in_array($siteId, $allowedSites)) {
            abort(403, 'Bu siteye erişim yetkiniz bulunmamaktadır.');
        }

        // Site ID'sini session'a kaydet.
        session(['active_site_id' => $siteId]);

        // Kullanıcıyı geldiği sayfaya bir başarı mesajı ile geri yönlendir.
        return Redirect::back()->with('status', 'Aktif site başarıyla değiştirildi.');
    }
}
