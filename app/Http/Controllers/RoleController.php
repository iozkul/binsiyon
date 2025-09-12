<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;




class RoleController extends Controller
{
    //use Spatie\Permission\Models\Role;
    //use Spatie\Permission\Models\Permission;
    public function index()
    {
        // Sadece en üst seviye rolleri alıp, altındakileri ilişki ile çekiyoruz (ağaç yapısı için)
        $roles = Role::whereNull('parent_role_id')->with('childrenRecursive')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all()->groupBy('group'); // Yetkileri gruplayarak göstermek için
        return view('admin.roles.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'parent_role_id' => 'nullable|exists:roles,id',
            'role_type' => 'required|in:SYSTEM,CLIENT',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create($request->except('permissions'));
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')->with('success', 'Rol başarıyla oluşturuldu.');
    }

    public function edit(Role $role)
    {
        // Bir rolün kendisinin üst rolü olmasını engellemek için
        $roles = Role::where('id', '!=', $role->id)->get();
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'roles', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'parent_role_id' => 'nullable|exists:roles,id',
            'role_type' => 'required|in:SYSTEM,CLIENT',
            'permissions' => 'nullable|array'
        ]);

        $role->update($request->except('permissions'));
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')->with('success', 'Rol başarıyla güncellendi.');
    }

    public function destroy(Role $role)
    {
        // Not: Alt rolleri olan bir rolü silme mantığını burada ayrıca düşünmelisiniz.
        // Örneğin alt rolleri de silmek veya bir üst role bağlamak gerekebilir.
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Rol silindi.');
    }
/*
    public function edit(Role $role)
    {
        return view('roles.edit', [
            'role' => $role,
            'permissions' => Permission::all(),
            'rolePermissions' => $role->permissions->pluck('name')->toArray(),
        ]);
    }
    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required']);
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->input('permissions', []));
        return redirect()->route('roles.index')->with('success', 'Rol güncellendi.');
    }
*/
}
