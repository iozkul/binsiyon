<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $owner = auth()->user();

        // Sahibin tüm birimlerini, o birimlerdeki kiracılar (residents) ile birlikte getir
        $ownedUnits = $owner->ownedUnits()->with('residents')->get();

        // Bu birimlere gönderilen duyuruları veya mesajları da çekebilirsiniz (ayrı bir sorgu ile)
        // $announcements = Announcement::whereIn('unit_id', $ownedUnits->pluck('id'))->get();

        return view('owner.dashboard', compact('ownedUnits'));
    }
}
