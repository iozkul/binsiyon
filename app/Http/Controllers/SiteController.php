<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*
       $this->authorize('manage sites');
    $sites = Site::latest()->paginate(10);
    return view('sites.index', compact('sites'));
        */
        $this->authorize('viewAny', Site::class);

        $user = Auth::user();
        $query = Site::query(); // Temel sorguyu başlat
        //dd($query->toSql());
        // Eğer kullanıcı super-admin DEĞİLSE, sorguyu filtrele.
        if (!$user->hasRole('super-admin')) {
            // Kullanıcının yönettiği site ID'lerini al
            $managedSiteIds = $user->managedSites()->pluck('id');
            if ($managedSiteIds->isEmpty()) {
                abort(403, 'Yönetici olarak herhangi bir siteye atanmamışsınız. Lütfen sistem yöneticisi ile iletişime geçin.');
            }

            // Sorguya, sadece bu ID'lere sahip olan siteleri getirmesini söyle
            $query->whereIn('id', $managedSiteIds);
        }



        // Sorguyu tamamla ve sayfala
        $sites = $query->withCount('blocks')->latest()->paginate(15);

        return view('sites.index', compact('sites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //$this->authorize('manage sites');
        $this->authorize('create', Site::class);
        // Sadece super-admin yönetici atayabilsin
        $potential_managers = [];
        if (auth()->user()->hasRole('super-admin')) {
            // Burada tüm kullanıcıları değil, yönetici olabilecek rollere sahip olanları filtreleyebilirsiniz.
            $potential_managers = User::whereHas('roles', function($q){
                $q->whereIn('name', ['site-admin', 'block-admin']);
            })->get();
        }

        return view('sites.create', compact('potential_managers'));
        //return view('sites.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Site::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'address_line' => 'required|string',
            'postal_code' => 'nullable|string|max:10', // nullable olduğu için 'required' değil
            'manager_ids' => 'sometimes|array',
            'manager_ids.*' => 'exists:users,id',
            'blocks' => 'required|array|min:1',
            'blocks.*.name' => 'required|string|max:255',
            'blocks.*.apartment_count' => 'required|integer|min:0',
            'blocks.*.commercial_count' => 'required|integer|min:0',
            'blocks.*.social_count' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            // 1. Siteyi Oluştur
            $site = Site::create($validated);

            // 2. Yöneticileri Ata
            if (auth()->user()->hasRole('super-admin') && !empty($validated['manager_ids'])) {
                $site->managers()->attach($validated['manager_ids']);
            }

            // 3. Gelen her blok verisi için döngü başlat
            foreach ($validated['blocks'] as $blockData) {
                // Bloğu oluştur
                $block = $site->blocks()->create(['name' => $blockData['name']]);

                // O bloğa ait daireleri oluştur
                for ($j = 1; $j <= $blockData['apartment_count']; $j++) {
                    $block->units()->create([
                        'name_or_number' => 'Daire ' . $j,
                        'type' => 'apartment',
                        'properties' => ['square_meters' => 100, 'room_count' => 3] // Varsayılan özellikler
                    ]);
                }
                // O bloğa ait ticari alanları oluştur
                for ($k = 1; $k <= $blockData['commercial_count']; $k++) {
                    $block->units()->create([
                        'name_or_number' => 'Dükkan ' . $k,
                        'type' => 'commercial',
                        'properties' => ['square_meters' => 80]
                    ]);
                }
                // O bloğa ait sosyal alanları oluştur
                for ($l = 1; $l <= $blockData['social_count']; $l++) {
                    $block->units()->create([
                        'name_or_number' => 'Sosyal Alan ' . $l,
                        'type' => 'social',
                        'properties' => ['capacity' => 50]
                    ]);
                }
            }
        });

        return redirect()->route('sites.index')->with('success', 'Site ve tüm birimleri başarıyla oluşturuldu.');

        /*
        $request->validate(['name'=>'required|string|max:255']);
    Site::create($request->all());
    return redirect()->route('sites.index')->with('success','Site eklendi');
        */
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {
        $site->load('blocks.units.residents', 'blocks.manager'); // Gerekli ilişkileri tek seferde yükle (Performans için)

        $blockData = $site->blocks->map(function ($block) {
            return [
                'name' => $block->name,
                'apartment_count' => $block->units->count(),
                'manager' => $block->manager?->name ?? 'Atanmamış'
            ];
        });

        // Gelir-Gider, Personel gibi verileri ilgili modellerden çekmeniz gerekecek.
        // Örnek:
        // $income = $site->transactions()->where('type', 'income')->sum('amount');
        // $staff = $site->staff()->get();

        return view('sites.show', compact('site', 'blockData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site)
    {
        $this->authorize('update', $site);

        // Yönetici atama listesi için potansiyel yöneticileri alıyoruz.
        $potential_managers = User::role(['super-admin', 'site-admin'])->get();

        // Bu sitenin mevcut yöneticilerinin ID'lerini bir dizi olarak alıyoruz.
        $siteManagers = $site->managers->pluck('id')->toArray();

        return view('sites.edit', compact('site', 'potential_managers', 'siteManagers'));

        //return view('sites.edit',compact('site'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site)
    {
		$request->validate(['name' => 'required|string|max:255']);
        $site->update($request->all()); return redirect()->route('sites.index')->with('success','Güncellendi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {

         $site->delete(); return redirect()->route('sites.index')->with('success','Silindi');
    }
}
