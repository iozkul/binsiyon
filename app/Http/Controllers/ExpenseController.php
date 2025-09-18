<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sadece kullanıcının yönettiği siteye ait giderleri göster
        $expenses = Expense::where('site_id', Auth::user()->site_id)->latest()->paginate(15);
        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('invoice');
        $data['site_id'] = Auth::user()->site_id;
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        if ($request->hasFile('invoice')) {
            $data['file_path'] = $request->file('invoice')->store('invoices', 'public');
        }

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Gider başarıyla oluşturuldu ve onaya gönderildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        // Giderin, kullanıcının sitesine ait olduğundan emin ol
        if ($expense->site_id !== Auth::user()->site_id) {
            abort(403);
        }
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        if ($expense->site_id !== Auth::user()->site_id) {
            abort(403);
        }
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        if ($expense->site_id !== Auth::user()->site_id) {
            abort(403);
        }

        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('invoice');

        if ($request->hasFile('invoice')) {
            if ($expense->file_path) {
                Storage::disk('public')->delete($expense->file_path);
            }
            $data['file_path'] = $request->file('invoice')->store('invoices', 'public');
        }

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'Gider başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        if ($expense->site_id !== Auth::user()->site_id) {
            abort(403);
        }

        // Giderin faturası varsa onu da sil
        if ($expense->file_path) {
            Storage::disk('public')->delete($expense->file_path);
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Gider başarıyla silindi.');
    }

    /**
     * Update the status of an expense (approve/reject).
     */
    public function updateStatus(Request $request, Expense $expense)
    {
        Gate::authorize('approve expenses');

        if ($expense->site_id !== Auth::user()->site_id) {
            abort(403);
        }

        $request->validate(['status' => 'required|in:approved,rejected']);

        $expense->status = $request->status;
        if ($request->status == 'approved') {
            $expense->approved_by = Auth::id();
            $expense->approved_at = now();
        } else {
            $expense->approved_by = null;
            $expense->approved_at = null;
        }
        $expense->save();

        return redirect()->back()->with('success', 'Gider durumu güncellendi.');
    }
}
