<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }



    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Fatura validasyonu
        ]);

        $data = $request->except('invoice');
        $data['site_id'] = Auth::user()->site_id;
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending'; // Yeni giderler her zaman onaya düşer

        if ($request->hasFile('invoice')) {
            // 'public' diskini kullanarak dosyayı storage/app/public/invoices altına kaydeder
            // Proje kök dizininde "php artisan storage:link" komutunu çalıştırmayı unutmayın.
            $data['file_path'] = $request->file('invoice')->store('invoices', 'public');
        }

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Gider başarıyla oluşturuldu ve onaya gönderildi.');
    }

    public function update(Request $request, Expense $expense)
    {
        Gate::authorize('update', $expense);

        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('invoice');

        if ($request->hasFile('invoice')) {
            // Eğer yeni bir fatura yüklenirse, eskisini sil
            if ($expense->file_path) {
                Storage::disk('public')->delete($expense->file_path);
            }
            $data['file_path'] = $request->file('invoice')->store('invoices', 'public');
        }

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'Gider başarıyla güncellendi.');
    }

    /**
     * Bir giderin durumunu günceller (Onayla/Reddet).
     */
    public function updateStatus(Request $request, Expense $expense)
    {
        // Bu işlemi sadece 'approve expenses' yetkisine sahip olanlar yapabilir.
        if (Gate::denies('approve expenses')) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $expense->status = $request->status;
        if ($request->status == 'approved') {
            $expense->approved_by = Auth::id();
            $expense->approved_at = now();
        } else {
            $expense->approved_by = null;
            $expense->approved_at = null;
        }
        $expense->save();

        return redirect()->route('expenses.index')->with('success', 'Gider durumu başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */


    public function destroy(Expense $expense)
    {
        //
    }
}
