<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Cache temizle
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1️⃣ Yetkiler
        $permissions = [
            'manage sites',
            'manage blocks',
            'manage users',
            'manage apartments',
            'manage residents',
            'manage units',
            'manage finance',
            'manage payments',
            'manage announcements',
            'view reports',
            'manage reservations',
            'approve reservations',
            'use reservations',
            'use maintenance',
            'use packages',
            'use iot',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2️⃣ Roller
        $roles = [
            'super-admin',
            'site-admin',
            'block-admin',
            'resident',
            'staff',
            'property-owner',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 3️⃣ Role bazlı yetkilendirme
        Role::findByName('super-admin')->givePermissionTo(Permission::all());

        Role::findByName('site-admin')->givePermissionTo([
            'manage sites',
            'manage blocks',
            'manage apartments',
            'manage residents',
            'manage finance',
            'manage announcements',
            'view reports',
            'use reservations',
            'use maintenance',
        ]);

        Role::findByName('block-admin')->givePermissionTo([
            'manage apartments',
            'manage residents',
            'manage announcements',
        ]);

        Role::findByName('resident')->givePermissionTo([
            'manage announcements',
        ]);

        Role::findByName('staff')->givePermissionTo([
            'use maintenance',
        ]);

        Role::findByName('property-owner')->givePermissionTo([
            'manage apartments',
            'manage residents',
        ]);
    }
}
