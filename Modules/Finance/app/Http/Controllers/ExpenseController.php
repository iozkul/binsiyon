<?php

namespace Modules\Finance\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense; // SQL şemanıza göre ana App modelini kullanıyoruz
use App\Models\Site;

class ExpenseController extends Controller
{
    public function index()
    {
        // Policy'ler henüz tanımlanmadı, şimdilik rol kontrolü yapıyoruz.
        // $this->authorize('viewAny', Expense::class);
        $expenses = Expense::with('site')->latest()->paginate(15);
        return view('finance::expenses.index', compact('expenses'));
    }

    public function create()
    {
        // $this->authorize('create', Expense::class);
        $sites = Site::all(); // Yetkiye göre site listesi gelmeli
        return view('finance::expenses.create', compact('sites'));
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Expense::class);
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'category' => 'nullable|string|max:100',
        ]);

        Expense::create($validated);
        return redirect()->route('finance.expenses.index')->with('success', 'Gider başarıyla eklendi.');
    }
}
