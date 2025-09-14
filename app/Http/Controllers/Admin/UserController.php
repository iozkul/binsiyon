<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Package;
use Spatie\Permission\Models\Role;
use App\Notifications\AdminToUserCommunication;
use Illuminate\Support\Facades\Notification;


class UserController extends Controller
{
    public function index()
    {
        //$users = User::latest()->paginate(20);
        $users = User::with(['roles', 'package', 'debts'])
            ->latest()
            ->paginate(20);
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
    public function manageRoles(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('admin.users.manage_roles', compact('user', 'roles', 'userRoles'));
    }

    public function assignRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'required|array'
        ]);

        // Spatie/laravel-permission paketinin sağladığı kullanışlı metod
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', $user->name . ' kullanıcısının rolleri güncellendi.');
    }

    public function edit(User $user)
    {
        $sites = Site::all(); // Tüm siteleri view'a gönder
        return view('admin.users.edit', compact('user', 'sites'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string', // db_binsiyon.sql'de bu alan var
            'site_id' => 'nullable|exists:sites,id', // Sitenin varlığını kontrol et
            'sites' => 'nullable|array',
            'sites.*' => 'exists:sites,id' // Gönderilen her site ID'si var mı?
        ]);

        $user->update($request->all());
        if ($request->has('sites')) {
            // Pivot tabloyu senkronize et
            $user->sites()->sync($request->sites);

            // Geriye dönük uyumluluk için:
            // İlk seçilen siteyi veya herhangi birini ana site_id olarak ata.
            $primarySiteId = $request->sites[0] ?? null;
            $user->site_id = $primarySiteId;
            $user->save();
        } else {
            // Hiç site seçilmediyse ilişkileri temizle
            $user->sites()->detach();
            $user->site_id = null;
            $user->save();
        }
        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı bilgileri güncellendi.');
    }
    public function sendMessage(Request $request, User $user)
    {
        $request->validate(['subject' => 'required', 'message' => 'required']);

        // Tek bir kullanıcıya bildirim gönder
        $user->notify(new AdminToUserCommunication($request->subject, $request->message));

        // VEYA birden fazla kullanıcıya:
        // $users = User::whereIn('id', $request->user_ids)->get();
        // Notification::send($users, new AdminToUserCommunication($request->subject, $request->message));

        return back()->with('success', 'Mesaj başarıyla gönderildi.');
    }
}
