<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Site;
use App\Models\Block;
use App\Models\Unit;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserController extends Controller
{
    public function index()
    {
        // Policy'yi de ekleyebilirsiniz: $this->authorize('manage users');

        $users = User::with('roles')->get(); // Tüm kullanıcıları rolleriyle birlikte getir
        $roles = Role::all(); // Tüm rolleri getir

        return view('users.manage-roles', compact('users', 'roles'));
    }

    public function edit(User $user)
    {
        if (! auth()->user()->hasRole('super-admin')) {
            // ...ve kendi profilini düzenlemeye ÇALIŞMIYORSA...
            if (auth()->id() !== $user->id) {
                // ...o zaman işlemi reddet.
                abort(403, 'This action is unauthorized.');
            }
        }
        // Bu sayfayı sadece 'manage users' yetkisi olanlar görebilsin.
        //$this->authorize('manage users');
        //$this->authorize('update', $user);
        $availableUnits = Unit::whereDoesntHave('residents')
            ->orWhere('id', $user->unit_id)
            ->with('block.site')
            ->get();

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::all(), // Formdaki checkbox'ları doldurmak için tüm rolleri al
            'userRoles' => $user->roles->pluck('name')->toArray(), // Kullanıcının mevcut rollerini al
            'sites' => Site::all(),
        'userManagedSites' => $user->managedSites->pluck('id')->toArray(),

        //'blocks' => Block::with('site')->get(),
            'blocks' => Block::whereNotNull('site_id')->with('site')->get(),
        //'userManagedBlocks' => $user->managedBlocks->pluck('id')->toArray(),
            'userManagedBlocks' => $user->getManagedBlockIds()->toArray(),
        'permissions' => Permission::all(),
        'userPermissions' => $user->permissions->pluck('name')->toArray(),
            'units' => Unit::with('block.site')->get(), // Forma tüm birimleri gönderiyoruz
            'userOwnedUnits' => $user->ownedUnits->pluck('id')->toArray(), // Kullanıcının sahip olduğu birimleri gönderiyoruz
        ]);
    }
/*
    public function updateRole(Request $request, User $user)
    {
        // Policy'yi de ekleyebilirsiniz: $this->authorize('manage users');
        $this->authorize('manage users');


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'nullable|array',
            'managed_sites' => 'nullable|array', // Yeni
            'managed_blocks' => 'nullable|array', // Yeni
            'permissions' => 'nullable|array', // Yeni
        ]);

        //$user->syncRoles($request->role); // syncRoles, eski rolleri silip yenisini ekler.
        $user->update($validated);

        // 1. Rolleri senkronize et
        $user->syncRoles($request->input('roles', []));

        // 2. Yönettiği siteleri senkronize et
        $user->managedSites()->sync($request->input('managed_sites', []));

        // 3. Yönettiği blokları senkronize et
        $user->managedBlocks()->sync($request->input('managed_blocks', []));

        // 4. Doğrudan verilen özel yetkileri senkronize et
        $user->syncPermissions($request->input('permissions', []));

        //return back()->with('success', $user->name . ' kullanıcısının rolü ' . $request->role . ' olarak güncellendi.');
        return redirect()->route('users.index')->with('success', 'Kullanıcı başarıyla güncellendi.');

    }*/
    public function showLedger(User $user)
    {
        $this->authorize('viewLedger', $user); // Policy'de bu yetkiyi tanımlayacağız

        $fees = $user->fees()->get(['description', 'amount', 'due_date as date'])->map(function ($item) {
            $item->type = 'borc';
            return $item;
        });

        $payments = $user->payments()->get(['amount', 'payment_date as date'])->map(function ($item) {
            $item->description = 'Ödeme';
            $item->type = 'odeme';
            return $item;
        });

        $ledger = $fees->concat($payments)->sortBy('date');

        $balance = 0; // Bakiye hesaplaması
        $ledgerWithBalance = $ledger->map(function ($item) use (&$balance) {
            if ($item->type === 'borc') {
                $balance -= $item->amount;
            } else {
                $balance += $item->amount;
            }
            $item->balance = $balance;
            return $item;
        });

        return view('users.ledger', [
            'user' => $user,
            'ledger' => $ledgerWithBalance
        ]);
    }
    public function update(Request $request, User $user)
    {
        // Yetki kontrolü: Kullanıcıyı güncelleme yetkisi var mı?
        //$this->authorize('update', $user);
        if (! auth()->user()->hasRole('super-admin')) {
            // ...ve kendi profilini düzenlemeye ÇALIŞMIYORSA...
            if (auth()->id() !== $user->id) {
                // ...o zaman işlemi reddet.
                abort(403, 'This action is unauthorized.');
            }
        }
/*
        if (! auth()->user()->hasRole('super-admin')) {
            if (auth()->id() !== $user->id) {
                abort(403, 'This action is unauthorized1.');
            }
        }

        if ($request->has('unit_id')) {
            $this->authorize('assignUnit');
        }*/
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            /*
            'roles' => 'nullable|array',
            'unit_id' => 'nullable|exists:units,id',
            'managed_sites' => 'nullable|array',
            'managed_blocks' => 'nullable|array',
            'permissions' => 'nullable|array',
            'owned_units' => 'nullable|array',
            */

        ]);

        // Kullanıcının temel bilgilerini güncelle
        $user->update($validated);

        // Kullanıcının rollerini senkronize et (eskiyi sil, yeniyi ekle)
        //$user->syncRoles($request->input('roles', []));
        $user->syncRoles($request->input('roles', []));
        $user->managedSites()->sync($request->input('managed_sites', []));
        $user->managedBlocks()->sync($request->input('managed_blocks', []));
        $user->syncPermissions($request->input('permissions', []));

        // Kullanıcının yönettiği siteleri senkronize et
       // $user->managedSites()->sync($request->input('managed_sites', []));

        // Kullanıcının yönettiği blokları senkronize et
       // $user->managedBlocks()->sync($request->input('managed_blocks', []));
        Unit::where('owner_id', $user->id)->update(['owner_id' => null]);

        // 2. Formdan gelen yeni birim listesi varsa, o birimlerin 'owner_id'sini bu kullanıcı olarak güncelle.
        /*
        if ($request->has('owned_units')) {
            Unit::whereIn('id', $request->input('owned_units', []))->update(['owner_id' => $user->id]);
        }
        */
        Unit::where('owner_id', $user->id)->update(['owner_id' => null]);
        if ($request->has('owned_units')) {
            Unit::whereIn('id', $request->input('owned_units', []))->update(['owner_id' => $user->id]);
        }

        // Kullanıcıya doğrudan atanan özel yetkileri senkronize et
        $user->syncPermissions($request->input('permissions', []));
        //$user->ownedUnits()->sync($request->input('owned_units', []));
        return redirect()->route('users.index')->with('success', 'Kullanıcı başarıyla güncellendi.');
        \Log::info('Policy kontrolü', [
            'auth_user' => $user->id,
            'auth_roles' => $user->getRoleNames(),
            'target_user' => $model->id,
            'model_unit' => $model->unit ? $model->unit->id : null,
        ]);
        //dd('ADIM 4: UserController@update metodu BAŞLADI.');
    }
}
