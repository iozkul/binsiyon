<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use App\Models\User;
//use Spatie\Permission\Models\Role;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*
		//dd(auth()->user()->getRoleNames());

	//$this->authorize('manage buildings');
	$this->authorize('viewAny', Block::class);
        $blocks = Block::with('site')->withCount('units')->latest()->paginate(15);

        //$block = Block::all();
	//$blocks = Block::latest()->paginate(10);
       // $blocks = Block::with('manager')->latest()->paginate(10);
        //$blocks = Block::with('apartments.residents')->get();

        return view('blocks.index', compact('blocks'));
        */
        /*
        $this->authorize('viewAny', Block::class);

        $user = Auth::user();
        $query = Block::with('site')->withCount('units'); // Temel sorguyu başlat

        // Eğer kullanıcı super-admin DEĞİLSE, sorguyu role göre filtrele.
        if (!$user->hasRole('super-admin')) {

            if ($user->hasRole('site-admin')) {
                // Site yöneticisi, yönettiği sitelerdeki TÜM blokları görür.
                $managedSiteIds = $user->managedSites()->pluck('id');

                // 2. YENİ KONTROL: Eğer yönettiği site yoksa, hata ver.
                if ($managedSiteIds->isEmpty()) {
                    abort(403, 'Yönetici olarak herhangi bir siteye atanmamışsınız. Lütfen sistem yöneticisi ile iletişime geçin.');
                }

                $query->whereIn('site_id', $managedSiteIds);

            } elseif ($user->hasRole('block-admin')) {
                // Blok yöneticisi, SADECE kendisine atanmış blokları görür.
                $managedBlockIds = $user->managedBlocks()->pluck('id');
                if ($managedBlockIds->isEmpty()) {
                    abort(403, 'Yönetici olarak herhangi bir bloğa atanmamışsınız.');
                }
                $query->whereIn('id', $managedBlockIds);
            } else {
                // Eğer ne site ne de blok yöneticisi değilse (örn: sakin),
                // hiçbir bloğu görememeli. Boş sonuç döndür.
                $query->whereRaw('1 = 0');
            }

        }

        // Sorguyu tamamla ve sayfala
        $blocks = $query->latest()->paginate(15);

        return view('blocks.index', compact('blocks'));*/
        $this->authorize('viewAny', Block::class); // Yetki kontrolü

        $user = Auth::user();
        $query = Block::with('site')->withCount('units');

        // SADECE site-admin için filtreleme yeterli olacaktır,
        // çünkü block-admin zaten sadece kendi bloğunu görmeli, blok listesini değil.
        // Menüde "Bloklar" linkini block-admin'den gizleyebilirsiniz.
        if ($user->hasRole('site-admin')) {
            $managedSiteIds = $user->managedSites()->pluck('id');
            $query->whereIn('site_id', $managedSiteIds);
        }
        elseif ($user->hasRole('block-admin')) {
            $managedBlockIds = $user->managedBlocks()->pluck('id_blk');
            $query->whereIn('id_blk', $managedBlockIds);
        }

        $blocks = $query->latest()->paginate(15);
        return view('blocks.index', compact('blocks'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //$this->authorize('manage buildings');
        $this->authorize('create', Block::class);
    return view('blocks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name'=>'required']);
    Block::create($request->all());
    return redirect()->route('block.index')->with('success','Blok eklendi');
    }

    /**
     * Display the specified resource.
     */
    public function show(Block $block)
    {$this->authorize('view', $block);

        // İlişkileri yüklüyoruz. Bu doğru.
        $block->load('manager', 'units.residents');

        // --- KODUN DÜZELTİLMİŞ HALİ ---

        // 2. İstenen istatistikleri hesaplıyoruz

        // İlişkiyi özellik olarak çağırarak sonuçları (koleksiyonu) alıyoruz.
        $units = $block->units;

        // flatMap ile bir bloğa ait tüm birimlerdeki sakinleri tek bir listede topluyoruz.
        $allResidents = $units->flatMap(function ($unit) {
            return $unit->residents;
        });

        $unitCount = $units->count();
        $residentCount = $allResidents->count();
        $statusStats = $allResidents->countBy('status'); // User modelinde 'status' alanı olduğunu varsayıyoruz.

        // 3. Gelir-Gider verileri...
        $financialSummary = [
            'income' => 12000,
            'expense' => 9500
        ];

        // 4. Tüm verileri view'a gönderiyoruz. İsimleri de daha anlaşılır hale getirdik.
        return view('blocks.show', [
            'block' => $block,
            'unitCount' => $unitCount,
            'residentCount' => $residentCount,
            'statusStats' => $statusStats,
            'financialSummary' => $financialSummary,
            'residents' => $allResidents // Sakin listesini de view'a gönderiyoruz
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Block $block)
    {
        // Yetki kontrolü (BlockPolicy'deki 'update' metodunu kullanır)
        $this->authorize('update', $block);
        $block->loadCount([
            'units as apartment_count' => fn($query) => $query->where('type', 'apartment'),
            'units as commercial_count' => fn($query) => $query->where('type', 'commercial'),
            'units as social_count' => fn($query) => $query->where('type', 'social'),
        ]);
        // Forma, site seçimi dropdown'ını doldurmak için tüm siteleri gönderiyoruz.
        $sites = Site::all();

        // Artık hem '$block' hem de '$sites' değişkenleri view'a gönderiliyor.
        return view('blocks.edit', compact('block', 'sites'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Block $block)
    {
        $block->update($request->all()); return redirect()->route('blocks.index')->with('success','Güncellendi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block)
    {
        $block->delete(); return redirect()->route('blocks.index')->with('success','Silindi');
    }
}
