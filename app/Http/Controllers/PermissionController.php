<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('manage users'); // Sadece yetkililer görsün
        $permissions = Permission::paginate(20);
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('manage users');
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('manage users');
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name'
        ]);

        Permission::create($validated);

        return redirect()->route('permissions.index')->with('success', 'Yeni yetki başarıyla oluşturuldu.');

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
        $this->authorize('manage users');
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Yetki başarıyla silindi.');

    }
}
