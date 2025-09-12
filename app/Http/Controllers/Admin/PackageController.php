<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $stats = [
            'total_packages' => Package::count(),
            'total_features' => Feature::count(),
            'assigned_users' => User::whereNotNull('package_id')->distinct('id')->count(),
        ];

        $packages = Package::withCount('features')->latest()->paginate(10);

        return view('admin.packages.index', compact('packages', 'stats'));
    }

    // Yeni paket oluşturma formunu gösterir
    public function create()
    {
        $features = Feature::all();
        return view('admin.packages.create', compact('features'));
    }

    // Yeni paketi veritabanına kaydeder
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id', // Gelen her feature ID'si features tablosunda var mı?
        ]);

        $package = new Package();
        $package->name = $validated['name'];
        $package->description = $validated['description'];
        $package->price = $validated['price'];
        $package->save();

        if (!empty($validated['features'])) {
            $package->features()->sync($validated['features']);
        }

        return redirect()->route('admin.packages.index')->with('success', 'Paket başarıyla oluşturuldu.');
    }

    // Paketi düzenleme formunu gösterir
    public function edit(Package $package)
    {
        $features = Feature::all();
        // Paketin mevcut özelliklerinin ID'lerini bir diziye alıyoruz
        $packageFeatures = $package->features->pluck('id')->toArray();
        return view('admin.packages.edit', compact('package', 'features', 'packageFeatures'));
    }

    // Güncelleme işlemini kaydeder
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
        ]);

        $package->update($validated);

        // sync metodu, eski ilişkileri siler ve sadece yeni gelenleri ekler. Harika bir yöntemdir.
        $package->features()->sync($request->features);

        return redirect()->route('admin.packages.index')->with('success', 'Paket başarıyla güncellendi.');
    }
}
