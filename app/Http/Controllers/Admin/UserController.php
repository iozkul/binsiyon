<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Package;


class UserController extends Controller
{
    public function index()
    {
        //$users = User::latest()->paginate(20);
        $users = User::with('roles', 'package')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }
// Kullanıcıya paket atama formunu gösterir
    public function assignPackageForm(User $user)
    {
        $packages = Package::where('is_active', true)->get();
        return view('admin.users.assign-package', compact('user', 'packages'));
    }

    // Paket atama işlemini kaydeder
    public function assignPackage(Request $request, User $user)
    {
        $request->validate(['package_id' => 'required|exists:packages,id']);
        $user->package_id = $request->package_id;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', $user->name . ' kullanıcısına paket başarıyla atandı.');
    }
    public function ban(User $user)
    {
        $user->update(['is_banned_by_admin' => true]);
        return back()->with('success', $user->name . ' adlı kullanıcı başarıyla engellendi.');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned_by_admin' => false]);
        return back()->with('success', $user->name . ' adlı kullanıcının engeli kaldırıldı.');
    }
    // Kullanıcıyı engelleme/engeli kaldırma
    public function toggleBan(User $user)
    {
        if ($user->banned_at) {
            $user->banned_at = null;
            $message = $user->name . ' kullanıcısının engeli kaldırıldı.';
        } else {
            $user->banned_at = now();
            $message = $user->name . ' kullanıcısı engellendi.';
        }
        $user->save();
        return back()->with('success', $message);
    }
}
