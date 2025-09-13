<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Block;

class UnitController extends Controller
{


    public function index()
    {
        // UnitPolicy'deki 'viewAny' kuralını kontrol et
        $this->authorize('viewAny', Unit::class);

        // Birimleri, ait oldukları blok ve site bilgisiyle birlikte getir.
        $units = Unit::with('block.site')->latest()->paginate(20);

        return view('units.index', compact('units'));
    }

    public function convert(Request $request, Unit $unit)
    {
        $validated = $request->validate(['new_type' => 'required|in:apartment,commercial,social']);
        $old_type = $unit->type;

        if ($old_type === $validated['new_type']) {
            return back()->with('error', 'Birim zaten bu tipte.');
        }

        DB::transaction(function () use ($unit, $old_type, $validated) {
            // 1. Tipi Güncelle
            $unit->update(['type' => $validated['new_type']]);

            // 2. Dönüşümü Kaydet (Logla)
            UnitConversion::create([
                'unit_id' => $unit->id,
                'from_type' => $old_type,
                'to_type' => $validated['new_type'],
                'changed_by_user_id' => auth()->id(),
                'changed_at' => now()
            ]);
        });

        return redirect()->route('units.show', $unit)->with('success', 'Birim tipi başarıyla dönüştürüldü.');
    }
    public function create()
    {
        // UnitPolicy'deki 'create' kuralını kontrol et
        $this->authorize('create', Unit::class);

        // Forma, birimlerin hangi bloğa ekleneceğini seçmek için
        // blok listesini gönderiyoruz.
        $blocks = Block::all();

        return view('units.create', compact('blocks'));
    }
    public function store(Request $request)
    {
        $this->authorize('create', Unit::class);
        $validated = $request->validate([
            'block_id' => 'required|exists:blocks,id',
            'apartment_count' => 'required|integer|min:0',
            'commercial_count' => 'required|integer|min:0',
            'social_count' => 'required|integer|min:0',
        ]);

        // ... (SiteController@store içindeki birim oluşturma döngülerini buraya taşıyın) ...
        // Daireleri Oluştur
        for ($j = 1; $j <= $validated['apartment_count']; $j++) {
            Unit::create([
                'block_id' => $validated['block_id'],
                'name_or_number' => 'Daire ' . $j, // Daha sonra bunları düzenleyebilirsiniz
                'type' => 'apartment',
            ]);
        }
        // Ticari ve Sosyal alanlar için de benzer döngüler...

        return redirect()->route('blocks.show', $validated['block_id'])->with('success', 'Birimler başarıyla eklendi.');
    }
    public function show(Unit $unit)
    {
        // UnitPolicy'deki 'view' kuralını kontrol et
        $this->authorize('view', $unit);

        // Detay sayfasında göstermek için ilişkili verileri de yüklüyoruz.
        $unit->load('block.site', 'residents');

        return view('units.show', compact('unit'));
    }
    public function edit(Unit $unit)
    {
        // UnitPolicy'deki 'update' kuralını kontrol et
        $this->authorize('update', $unit);

        // Formda blok seçimi yapabilmek için tüm blokları gönderiyoruz
        $blocks = Block::all();

        return view('units.edit', compact('unit', 'blocks'));
    }

    /**
     * Belirtilen birimi veritabanında günceller.
     */
    public function update(Request $request, Unit $unit)
    {
        $this->authorize('update', $unit);

        $validated = $request->validate([
            'name_or_number' => 'required|string|max:255',
            'block_id' => 'required|exists:blocks,id',
            'type' => 'required|in:apartment,commercial,social',
            'properties.square_meters' => 'nullable|numeric',
            'properties.room_count' => 'nullable|integer',
        ]);

        $unit->update($validated);

        return redirect()->route('units.index')->with('success', 'Birim başarıyla güncellendi.');
    }
}
