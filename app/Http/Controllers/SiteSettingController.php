<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class SiteSettingController extends Controller
{


    public function index()
    {
        // Yetki kontrolü
        Gate::authorize('manage site settings');

        Log::info('SiteSettingController@index metodu çalıştı. Kullanıcı: ' . Auth::id());

        $user=Auth::user();
        $siteId = $user->site_id;

        // Super-admin için site seçimi gerekebilir, şimdilik site-admin odaklı
        if (!$siteId && !$user->hasRole('super-admin')) {
            return redirect()->route('dashboard')->with('error', 'Bir siteye atanmamışsınız.');
        }

        $settingsRaw = SiteSetting::where('site_id', $siteId)->get();
        $settings = $settingsRaw->pluck('value', 'key');

        return view('site-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $siteId = Auth::user()->site_id;
        if (!$siteId) {
            return redirect()->back()->with('error', 'Bir siteye atanmamışsınız.');
        }

        $validatedData = $request->validate([
            'due_late_fee_rate' => 'nullable|numeric|min:0',
            'elevator_fee_exempt_floors' => 'nullable|string',
        ]);

        foreach ($validatedData as $key => $value) {
            SiteSetting::updateOrCreate(
                ['site_id' => $siteId, 'key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('site-settings.index')->with('success', 'Site ayarları başarıyla güncellendi.');
    }
}
