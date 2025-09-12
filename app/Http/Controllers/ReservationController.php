<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Models\Site;
use App\Models\Block;
use App\Models\Unit;



class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Sakinlerin rezervasyon yapacağı ekran
    public function index()
    {
        $user = Auth::user();
        // Sakinin sitesindeki rezerve edilebilir sosyal alanları listele
        $reservableUnits = Unit::where('type', 'social')
            ->whereHas('block', function ($query) use ($user) {
                $query->where('site_id', $user->unit?->block?->site_id);
            })->get();

        $reservations = Reservation::whereHas('unit.block', function ($query) use ($user) {
            $query->where('site_id', $user->unit?->block?->site_id);
        })->get();

        //return view('reservations.index', compact('reservableUnits', 'reservations'));
        // Veritabanından durumu 'pending' olan rezervasyonları çekiyoruz.
        $pendingReservations = Reservation::where('status', 'pending')->get();

        // Diğer durumdaki rezervasyonları da çekebiliriz, örneğin:
        $approvedReservations = Reservation::where('status', 'approved')->get();

        // View dosyasına bu değişkenleri bir dizi içinde veya 'compact' ile gönderiyoruz.
        // 'compact' fonksiyonu, aynı isme sahip değişkenlerden bir dizi oluşturur.
        return view('reservations.index', compact('pendingReservations', 'approvedReservations'));

    }

    // Yöneticilerin rezervasyon taleplerini göreceği ekran
    public function manage()
    {
        // ManagedScope bu sorguyu yöneticinin sitesine göre otomatik filtreleyecektir.
        $pendingReservations = Reservation::where('status', 'pending')->with('user', 'unit')->get();
        return view('reservations.manage', compact('pendingReservations'));
    }

    // Sakin yeni rezervasyon oluşturduğunda
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $unit = Unit::findOrFail($validated['unit_id']);
        $this->authorize('create', [Reservation::class, $unit]);

        Reservation::create([
            'user_id' => Auth::id(),
            'unit_id' => $unit->id,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'pending' // Yönetici onayı bekliyor
        ]);

        return redirect()->route('reservations.index')->with('success', 'Rezervasyon talebiniz alındı, yönetici onayı bekleniyor.');
    }

    // Yönetici talebi onayladığında
    public function approve(Reservation $reservation)
    {
        $this->authorize('approve', $reservation);
        $reservation->update(['status' => 'approved']);
        // Kullanıcıya bildirim gönderilebilir.
        return redirect()->back()->with('success', 'Rezervasyon onaylandı.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /*
    public function store(Request $request)
    {
        $unit = Unit::findOrFail($request->unit_id);
        $this->authorize('create', [Reservation::class, $unit]); // Policy'de kontrol edilecek

        Reservation::create([
            'user_id' => Auth::id(),
            'unit_id' => $unit->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'pending'
        ]);
    }*/

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function unit() // <-- BU FONKSİYONU EKLEYİN
    {
        return $this->belongsTo(Unit::class);
    }

}
