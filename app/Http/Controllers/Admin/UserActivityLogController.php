<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserActivityLog;
use App\Models\User;

class UserActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = UserActivityLog::with(['user', 'actor'])->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('actor_id')) {
            $query->where('actor_id', $request->actor_id);
        }
        if ($request->filled('action_code')) {
            $query->where('action_code', $request->action_code);
        }

        $logs = $query->paginate(50);
        $users = User::orderBy('name')->get(); // Filtre dropdown'ı için

        return view('admin.logs.index', compact('logs', 'users'));
    }
}
