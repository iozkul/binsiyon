<?php

namespace Modules\Finance\App\Http\Controllers;

use App\Http\Controllers\Controller;
//use App\Models\Budget;
use Modules\Finance\App\Models\Budget;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class BudgetController extends Controller
{

    public function __construct()
    {

        $this->authorizeResource(Budget::class, 'budget');
    }

    public function index()
    {
        //dd(session()->all());
        /*
        $activeSiteId = session('active_site_id');
        $user = auth()->user();

        // Middleware zaten kontrol ediyor ama yine de burada bir kontrol olabilir.
        if (!$activeSiteId) {
            return redirect()->route('dashboard'); // Middleware zaten yönlendirecektir.
        }

        $query = Budget::query();

        // EĞER "TÜM SİTELER" SEÇİLDİYSE VE KULLANICI SUPER-ADMIN İSE
        if ($activeSiteId === 'all' && $user->hasRole('super-admin')) {
            // Herhangi bir site filtresi uygulama, tüm bütçeleri getir.
            // İsteğe bağlı olarak kullanıcının erişebileceği sitelerle de kısıtlanabilir.
        } else {
            // Aksi halde, sadece aktif olan siteye göre filtrele.
            $query->where('site_id', $activeSiteId);
        }

        $budgets = $query->latest()->paginate(15);

        return view('finance::budgets.index', compact('budgets'));
        */
        $activeSiteId = session('active_site_id');
        // Middleware zaten kontrol ediyor ama burada tekrar kontrol etmek iyidir.

        $user = auth()->user();

        // Eğer "Tüm Siteler" seçiliyse ve kullanıcı super-admin ise
        if ($activeSiteId === 'all' && $user->hasRole('super-admin')) {
            // Tüm sitelere ait bütçeleri getir.
            $budgets = Budget::latest()->paginate(15);
        } else {
            // Sadece aktif siteye ait bütçeleri getir.
            $budgets = Budget::where('site_id', $activeSiteId)->latest()->paginate(15);
        }

        return view('finance::budgets.index', compact('budgets'));
    }

    /**
     * Yeni bütçe oluşturma formunu gösterir.
     */
    public function create()
    {
        // Site seçimi artık aktif site üzerinden yapıldığı için tüm siteleri göndermeye gerek yok.
        return view('finance::budgets.create');
    }
/*
    public function index()
    {
        $budgets = Budget::with('site')->latest()->paginate(15);
        return view('budgets.index', compact('budgets'));
    }
*/

    public function store(Request $request)
    {
        $activeSiteId = session('active_site_id');
        if (!$activeSiteId || $activeSiteId === 'all') {
            return back()->with('error', 'Bütçe oluşturmak için bir site seçmelisiniz.')->withInput();
        }

        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:2020|max:2050',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:income,expense',
            'items.*.category' => 'required|string|max:255',
            'items.*.description' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
        ]);
        /*
        DB::transaction(function () use ($request) {
            $budget = Budget::create([
                'site_id' => $request->site_id,
                'name' => $request->name,
                'year' => $request->year,
                'description' => $request->description,
                'created_by_user_id' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $budget->items()->create($item);
            }
        });
*/
        $budget = new Budget($request->all());
        $budget->site_id = $activeSiteId;
        $budget->created_by = Auth::id();
        $budget->save();

        //return redirect()->route('budgets.index')->with('success', 'Bütçe başarıyla oluşturuldu.');
        return redirect()->route('finance.budgets.index')->with('success', 'Bütçe başarıyla oluşturuldu.');

    }

    public function show(Budget $budget)
    {
        $budget->load('items', 'site', 'createdBy');
        return view('budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        $sites = Site::all();
        $budget->load('items');
        return view('budgets.edit', compact('budget', 'sites'));
    }

    public function update(Request $request, Budget $budget)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:2020|max:2050',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:income,expense',
            'items.*.category' => 'required|string|max:255',
            'items.*.description' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $budget) {
            $budget->update($request->only('site_id', 'name', 'year', 'description'));

            $budget->items()->delete();

            foreach ($request->items as $item) {
                $budget->items()->create($item);
            }
        });

        return redirect()->route('budgets.show', $budget)->with('success', 'Bütçe başarıyla güncellendi.');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        return redirect()->route('budgets.index')->with('success', 'Bütçe başarıyla silindi.');
    }
}
