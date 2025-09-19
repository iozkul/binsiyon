<?php

namespace Modules\Finance\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;

class PaymentController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', Payment::class);
        // SQL şemanızda `user_id` üzerinden ilişki var
        $payments = Payment::with('user')->latest()->paginate(15);
        return view('finance::payments.index', compact('payments'));
    }

    public function create()
    {
        // $this->authorize('create', Payment::class);
        $users = User::all(); // Yetkiye göre kullanıcı listesi gelmeli
        return view('finance::payments.create', compact('users'));
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Payment::class);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        Payment::create($validated);
        return redirect()->route('finance.payments.index')->with('success', 'Ödeme başarıyla kaydedildi.');
    }
}
