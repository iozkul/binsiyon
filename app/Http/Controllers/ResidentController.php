<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class ResidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /*
    public function index()
    {

		 //dd(auth()->user()->getRoleNames());
		$this->authorize('viewAny', User::class);
        $user = Auth::user();

         // 1. Veritabanından tüm kullanıcıları çekiyoruz.
        //$residents = User::latest()->get(); // latest() ile en son eklenenleri üste alırız.
        // 2. Temel Sorguyu Başlat: Sadece sakin olan kullanıcıları hedef alalım
        // Örnek: 'resident' veya 'property-owner' gibi rollere sahip olanlar
        $query = User::query()->whereHas('roles', function ($q) {
            $q->whereIn('name', ['resident', 'property-owner', 'staff']);
        });
        if ($user->hasRole('super-admin')) {
            // KURAL 1: super-admin ise hiçbir ek filtre uygulama, tüm sakinleri görsün.
            // Bu blok boş kalır, sorgu tüm sakinleri getirecek şekilde devam eder.

        }
        // 3. Veri Kapsamını Uygula (DATA SCOPING)
        // Süper admin bu filtrelere takılmaz çünkü AuthServiceProvider'daki Gate::before onu yetkilendirir.
        elseif ($user->hasRole('site-admin')) {
            // Eğer kullanıcı site yöneticisiyse, SADECE kendi yönettiği sitelerdeki kullanıcıları listele.
            // Bu, User modelinizde site_id kolonu olduğunu varsayar.
            $managedSiteIds = $user->managedSites()->pluck('sites.id');
            $query->whereIn('site_id', $managedSiteIds);

        } elseif ($user->hasRole('block-admin')) {
            // Eğer kullanıcı blok yöneticisiyse, SADECE kendi yönettiği bloklardaki kullanıcıları listele.
            // Bu, User -> Unit -> Block ilişkisi üzerinden çalışır.
            //$managedBlockIds = $user->managedBlocks()->pluck('blocks.id');
            //$managedBlockIds = $user->managedBlocks()->select('blocks.id')->pluck('blocks.id');
            $managedBlockIds = $user->managedBlocks()->select('blocks.id')->pluck('blocks.id');
            //$managedBlockIds = $user->managedBlocks()->pluck('blocks.id');

            // if ($managedBlockIds->isEmpty()) {


            // Kullanıcıların ait olduğu birimleri (unit) ve o birimlerin ait olduğu blokları
            // kontrol eden bir alt sorgu (whereHas) yazıyoruz.
            $query->whereHas('unit', function ($unitQuery) use ($managedBlockIds) {
                $unitQuery->whereIn('block_id', $managedBlockIds);
            });
        }
        //$residents = User::with(['site', 'block', 'roles'])->latest()->paginate(20);
        $residents = $query->with(['site', 'unit.block', 'roles'])->latest()->paginate(20);

        // 2. Kullanıcıları 'residents.index' view'ına 'residents' değişkeniyle gönderiyoruz.
        return view('residents.index', [
            'residents' => $residents
        ]);


    }
*/
    /*
    public function index()
    {
        $user = Auth::user();

        // Sorguyu başlat
        $query = User::role(['resident', 'staff','super-admin','site-admin','block-admin']);

        // Eğer kullanıcı super-admin değilse, sadece yönettiği sitelerdeki sakinleri göster
        if (!$user->hasRole('super-admin')) {
            // Site admin ise yönettiği sitelerdeki kullanıcıları getir
            if ($user->hasRole('site-admin')) {
                $managedSiteIds = $user->managedSites()->pluck('sites.id');
                $query->whereIn('site_id', $managedSiteIds);
            }
            // Block admin ise yönettiği bloklardaki kullanıcıları getir
            elseif ($user->hasRole('block-admin')) {
                $managedBlockIds = $user->managedBlocks()->pluck('blocks.id');
                // Bu bloklara bağlı dairelerdeki (unit) kullanıcıları bul
                $query->whereHas('unit', function ($q) use ($managedBlockIds) {
                    $q->whereIn('block_id', $managedBlockIds);
                });
            }
        }
        // super-admin ise hiçbir filtreleme yapma, tüm 'residence' rolündeki kullanıcıları getir

        $residents = $query->latest()->paginate(10); // veya ->get();

        return view('residents.index', compact('residents'));
    } */
    public function index()
    {
        // User::managed() diyerek yerel scope'u burada çağırıyoruz.
        // Bu sayede filtreleme sadece bu sorgu için çalışır.
        $residents = User::managed()->role(['resident', 'staff','super-admin','site-admin','block-admin'])->latest()->paginate(10);

        return view('residents.index', compact('residents'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Resident $resident)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resident $resident)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resident $resident)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resident $resident)
    {
        //
    }
	public function showAssignForm()
    {
        // Hiçbir rolü olmayan kullanıcıları bul
        $usersWithoutRoles = User::whereDoesntHave('roles')->get();

        // Atama yapmak için sistemdeki tüm rolleri al
        $roles = Role::all();

        // Verileri view'a gönder
        return view('residents.assign-roles', [
            'users' => $usersWithoutRoles,
            'roles' => $roles
        ]);
    }
	 public function assignRole(Request $request, User $user)
    {
        // Gelen veriyi doğrula: 'role' alanı zorunlu ve roles tablosunda var olmalı
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        // Kullanıcının mevcut tüm rollerini silip sadece yeni seçileni ata.
        // Bu, kullanıcının birden fazla rol biriktirmesini engeller.
        $user->syncRoles($request->role);

        // Başarı mesajıyla birlikte bir önceki sayfaya yönlendir
        return back()->with('success', $user->name . ' kullanıcısına ' . $request->role . ' rolü başarıyla atandı.');
    }
}
