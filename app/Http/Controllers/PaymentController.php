<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
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
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);
        $fee = Fee::findOrFail($validated['fee_id']);

        DB::transaction(function () use ($validated, $fee) {
            // 1. Ödemeyi kaydet
            $payment = Payment::create($validated);

            // 2. Aidat borcunun durumunu güncelle (paid_at sütununu doldur)
            $fee->update(['paid_at' => $payment->payment_date]);

            // 3. Transactions tablosuna gelir olarak ekle
            Transaction::create([
                'site_id' => $fee->site_id,
                'type' => 'income',
                'category' => 'aidat',
                'description' => $fee->description . ' ödemesi',
                'amount' => $payment->amount,
                'transaction_date' => $payment->payment_date,
            ]);
        });

        return redirect()->back()->with('success', 'Ödeme başarıyla kaydedildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            // Orijinal kaydı "iptal edildi" olarak işaretle
            $payment->is_reversed = true;
            $payment->status = 'reversed';
            $payment->save();

            // Ters kaydı oluştur
            $reversalPayment = $payment->replicate(); // Orijinalin kopyasını al
            $reversalPayment->amount = -$payment->amount; // Tutarı negatife çevir
            $reversalPayment->status = 'reversal_entry';
            $reversalPayment->reverses_payment_id = $payment->id; // Hangi kaydı iptal ettiğini belirt
            $reversalPayment->save();

            // İlgili borcun durumunu tekrar "ödenmedi" olarak güncelle
            if ($payment->debt) {
                $payment->debt->update(['status' => 'unpaid']);
            }
        });

        return back()->with('success', 'Ödeme kaydı iptal edildi (ters kayıt oluşturuldu).');
    }
}
