<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Apartment::class);
        // $this->authorize('manage buildings');
        $apartments = Apartment::with('block.site')->latest()->paginate(20);
        //$apartment = Apartment::all();
        return view('apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('manage buildings');
    return view('apartment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name'=>'required']);
    Apartment::create($request->all());
    return redirect()->route('apartment.index')->with('success','Daire eklendi');
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment)
    {
        // 1. Gerekli ilişkileri yüklüyoruz
        $apartment->load([
            'block.site.manager', // Daire -> Blok -> Site -> Site Yöneticisi
            'block.manager',      // Daire -> Blok -> Blok Yöneticisi
            'residents'           // Dairedeki sakinler
        ]);

        // 2. Sakinler üzerinden demografik bilgileri hesaplıyoruz
        // Bunun için User modelinizde 'gender' (male/female), 'birth_date', 'has_disability' gibi alanlar olmalı
        $residents = $apartment->residents;
        $demographics = [
            'total' => $residents->count(),
            'male' => $residents->where('gender', 'male')->count(),
            'female' => $residents->where('gender', 'female')->count(),
            'children' => $residents->where('is_child', true)->count(), // 'is_child' boolean alanı varsayımı
            'disabled' => $residents->where('has_disability', true)->count(),
            'elderly' => $residents->where('is_elderly', true)->count(),
        ];

        // 3. Daireye ait finansal durumu (aidat, borçlar) çekiyoruz
        // Örnek:
        // $dues = Due::where('apartment_id', $apartment->id)->latest()->get();
        $dues = [ // Örnek veri
            ['date' => '2025-08-01', 'description' => 'Ağustos Aidatı', 'amount' => 500, 'status' => 'Ödendi'],
            ['date' => '2025-07-01', 'description' => 'Temmuz Aidatı', 'amount' => 500, 'status' => 'Ödendi'],
        ];

        return view('apartments.show', [
            'apartment' => $apartment,
            'demographics' => $demographics,
            'dues' => $dues
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment)
    {
        return view('apartment.edit',compact('apartment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Apartment $apartment)
    {
         $apartment->update($request->all()); return redirect()->route('apartment.index')->with('success','Güncellendi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartment $apartment)
    {
        $apartment->delete(); return redirect()->route('apartment.index')->with('success','Silindi');
    }
}
