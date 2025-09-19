<?php

namespace Modules\Finance\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Finance\app\Models\MonthlyDue;

class MyFinancesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $monthlyDues = MonthlyDue::where('resident_user_id', $user->id)
            ->with(['site', 'payments'])
            ->latest('period')
            ->paginate(10);

        return view('finance::my-finances.index', compact('monthlyDues'));
    }
}
