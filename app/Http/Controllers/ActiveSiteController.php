<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

        // Eğer 'all' seçeneği geldiyse ve kullanıcı super-admin değilse, işlemi reddet.
        if ($siteId === 'all' && !$user->hasRole('super-admin')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        // Eğer belirli bir site ID'si geldiyse, kullanıcının o siteye erişim yetkisi var mı kontrol et.
        if ($siteId !== 'all') {
            $site = Site::findOrFail($siteId);
            // Burada kullanıcının o siteye atanıp atanmadığını kontrol eden bir policy veya gate olabilir.
            // Örnek: if (!Gate::allows('view-site', $site)) { abort(403); }
        }

        // Seçimi session'a kaydet.
        session(['active_site_id' => $siteId]);

        $message = 'Aktif çalışma ortamı değiştirildi.';
        if($siteId !== 'all') {
            $message = 'Aktif site olarak "' . Site::find($siteId)->name . '" seçildi.';
        } else {
            $message = 'Tüm siteler için özet görünümüne geçildi.';
        }

        // Kullanıcıyı geldiği sayfaya bir başarı mesajıyla geri yönlendir.
        return back()->with('success', $message);
    }
}
