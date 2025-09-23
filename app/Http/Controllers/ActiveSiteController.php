<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class ActiveSiteController extends Controller
{
    /**
     * Kullanıcının aktif çalışma sitesini değiştirir ve session'a kaydeder.
     */
    public function switch(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|string', // 'all' değeri de gelebileceği için string
        ]);

        $siteId = $validated['site_id'];
        $user = auth()->user();

        // Güvenlik: 'all' seçeneği geldiyse ve kullanıcı super-admin değilse, işlemi reddet.
        if ($siteId === 'all' && !$user->hasRole('super-admin')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        // Güvenlik: Belirli bir site ID'si geldiyse, kullanıcının o siteye erişim yetkisi var mı kontrol et.
        if ($siteId !== 'all') {
            $site = Site::findOrFail($siteId);
            // Super-admin olmayan kullanıcılar için, sadece kendi yönettiği siteler arasından seçim yapabilmeli.
            if (!$user->hasRole('super-admin') && !$user->sites->contains($siteId)) {
                abort(403, 'Bu siteyi yönetme yetkiniz bulunmamaktadır.');
            }
        }

        // Seçimi session'a kaydet.
        session(['active_site_id' => $siteId]);

        // Bilgilendirme mesajını oluştur ve geri yönlendir.
        $message = 'Tüm siteler için özet görünümüne geçildi.';
        if ($siteId !== 'all') {
            $siteName = Site::find($siteId)->name;
            $message = 'Aktif site olarak "' . $siteName . '" seçildi.';
        }

        return redirect()->back()->with('success', $message);
    }
}
