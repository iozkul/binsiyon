<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Site; // Site modelinizi projenize göre ekleyin
use Illuminate\Http\Request;
use Carbon\Carbon;

class FixtureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Fixture::class); // Kullanıcı listeyi görebilir mi?

        // Önemli: Site admini sadece kendi demirbaşlarını görmeli
        $user = auth()->user();
        if (!$user->hasRole('super-admin')) {
            if ($user->hasRole === 'site-admin') {
                $fixtures = Fixture::with('site')->where('site_id', $user->site_id)->orderBy('next_maintenance_date', 'asc')->get();
            } else {
                $fixtures = Fixture::with('site')->orderBy('next_maintenance_date', 'asc')->get();
            }

        }
        // Demirbaşları bir sonraki bakım tarihine göre sıralayarak listele
        $fixtures = Fixture::with('site')->orderBy('next_maintenance_date', 'asc')->get();
        return view('fixtures.index', compact('fixtures'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Fixture::class);
        $sites = Site::all(); // Formda site seçimi için tüm siteleri gönder
        $this->authorize('create', Fixture::class);
        return view('fixtures.create', compact('sites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Fixture::class);
        $validatedData = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date',
            'maintenance_interval_days' => 'nullable|integer|min:1',
            'last_maintenance_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|string',
        ]);

        // Bir sonraki bakım tarihini otomatik hesapla
        if (isset($validatedData['last_maintenance_date']) && isset($validatedData['maintenance_interval_days'])) {
            $validatedData['next_maintenance_date'] = Carbon::parse($validatedData['last_maintenance_date'])
                ->addDays($validatedData['maintenance_interval_days']);
        }

        Fixture::create($validatedData);

        return redirect()->route('fixtures.index')->with('success', 'Demirbaş başarıyla eklendi.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fixture $fixture)
    {
        $this->authorize('update', $fixture);
        $sites = Site::all();
        return view('fixtures.edit', compact('fixture', 'sites'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fixture $fixture)
    {
        $this->authorize('update', $fixture);
        $validatedData = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date',
            'maintenance_interval_days' => 'nullable|integer|min:1',
            'last_maintenance_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|string',
        ]);

        // Bir sonraki bakım tarihini otomatik hesapla
        if (isset($validatedData['last_maintenance_date']) && isset($validatedData['maintenance_interval_days'])) {
            $validatedData['next_maintenance_date'] = Carbon::parse($validatedData['last_maintenance_date'])
                ->addDays($validatedData['maintenance_interval_days']);
        } else {
            $validatedData['next_maintenance_date'] = null;
        }

        $fixture->update($validatedData);

        return redirect()->route('fixtures.index')->with('success', 'Demirbaş başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fixture $fixture)
    {
        $this->authorize('delete', $fixture);
        $fixture->delete(); // Soft delete
        return redirect()->route('fixtures.index')->with('success', 'Demirbaş başarıyla silindi.');
    }
}
