<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\BudgetItem;
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
        $budgets = Budget::with('site')->latest()->paginate(15);
        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        $sites = Site::all(); // super-admin için tüm siteler
        return view('budgets.create', compact('sites'));
    }

    public function store(Request $request)
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

        return redirect()->route('budgets.index')->with('success', 'Bütçe başarıyla oluşturuldu.');
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
