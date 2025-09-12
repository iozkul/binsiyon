<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\Site;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FixtureController extends Controller
{
    public function index()
    {
        $fixtures = Fixture::with('site')->latest()->paginate(20);
        return view('admin.fixtures.index', compact('fixtures'));
    }

    public function create()
    {
        $sites = Site::orderBy('name')->get();
        return view('admin.fixtures.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:purchase_date',
            'maintenance_interval_days' => 'nullable|integer|min:1',
            'last_maintenance_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($request->filled('last_maintenance_date') && $request->filled('maintenance_interval_days')) {
            $validated['next_maintenance_date'] = Carbon::parse($request->last_maintenance_date)
                ->addDays($request->maintenance_interval_days);
        }

        Fixture::create($validated);

        return redirect()->route('admin.fixtures.index')->with('success', 'Demirbaş başarıyla eklendi.');
    }

    public function edit(Fixture $fixture)
    {
        $sites = Site::orderBy('name')->get();
        return view('admin.fixtures.edit', compact('fixture', 'sites'));
    }

    public function update(Request $request, Fixture $fixture)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:purchase_date',
            'maintenance_interval_days' => 'nullable|integer|min:1',
            'last_maintenance_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($request->filled('last_maintenance_date') && $request->filled('maintenance_interval_days')) {
            $validated['next_maintenance_date'] = Carbon::parse($request->last_maintenance_date)
                ->addDays($request->maintenance_interval_days);
        } else {
            $validated['next_maintenance_date'] = null;
        }

        $fixture->update($validated);

        return redirect()->route('admin.fixtures.index')->with('success', 'Demirbaş başarıyla güncellendi.');
    }

    public function destroy(Fixture $fixture)
    {
        $fixture->delete();
        return redirect()->route('admin.fixtures.index')->with('success', 'Demirbaş başarıyla silindi.');
    }
}

