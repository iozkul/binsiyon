<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Transaction::class);
        $transactions = Transaction::latest('transaction_date')->paginate(20);
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Transaction::class);
        $user = Auth::user();
        // Site admini sadece kendi sitelerini görmeli, super-admin hepsini.
        $sites = $user->hasRole('super-admin') ? Site::all() : $user->managedSites;
        return view('transactions.create', compact('sites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Transaction::class);
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
        ]);
        Transaction::create($validated);
        return redirect()->route('transactions.index')->with('success', 'Finansal işlem başarıyla kaydedildi.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
