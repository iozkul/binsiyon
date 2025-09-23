<?php

namespace Modules\Personnel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Personnel\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $this->authorize('manage personnel');

        $activeSiteId = session('active_site_id');
        if (!$activeSiteId) {
            return redirect()->route('dashboard')->with('error', 'Lütfen bir site seçin.');
        }

        $employees = Employee::with('user')
            ->where('site_id', $activeSiteId)
            ->get();

        return view('personnel::employees.index', compact('employees'));
    }

    // Buraya create, store, edit, update, destroy metodları eklenecek...
}
