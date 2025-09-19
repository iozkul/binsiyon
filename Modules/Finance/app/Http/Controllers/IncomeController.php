<?php

namespace Modules\Finance\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income; // Ana App Modelini kullanıyoruz
use App\Models\Site;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Income::class);
        $incomes = Income::with('site')->latest()->paginate(15);
        return view('finance::incomes.index', compact('incomes'));
    }

    public function create()
    {
        $this->authorize('create', Income::class);
        $sites = Site::all(); // TODO: Yetkiye göre site listesi gelmeli
        return view('finance::incomes.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Income::class);
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'income_date' => 'required|date',
        ]);

        Income::create($validated);
        return redirect()->route('finance.incomes.index')->with('success', 'Gelir başarıyla eklendi.');
    }

    // edit, update, destroy metodları da benzer şekilde eklenebilir.
}
