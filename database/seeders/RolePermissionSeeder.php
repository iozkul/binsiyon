<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mevcut tüm cache'lenmiş rolleri ve yetkileri temizle
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // İzinleri (Permissions) oluştur
        $permissions = [
            'manage users', 'manage sites', 'manage blocks', 'manage apartments', 'manage residents', 'manage units',
            'manage finance', 'manage payments', 'manage announcements', 'view reports', 'manage reservations',
            'approve reservations', 'use reservations', 'use maintenance', 'use packages', 'use iot',
            'view incomes', 'create expenses',
            // Aidat (Dues) için CRUD yetkileri eklendi
            'list dues', 'view dues', 'create dues', 'edit dues', 'delete dues',
            'view dashboard'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Rolleri oluştur ve yetkileri ata
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        // super-admin'e AuthServiceProvider üzerinden tüm yetkiler veriliyor.

        $siteAdminRole = Role::firstOrCreate(['name' => 'site-admin']);
        $siteAdminRole->givePermissionTo([
            'manage users', 'manage blocks', 'manage apartments', 'manage residents', 'manage units',
            'manage finance', 'manage payments', 'manage announcements', 'view reports', 'manage reservations',
            'approve reservations', 'use maintenance', 'view incomes', 'create expenses', 'view dashboard',
            'list dues', 'view dues', 'create dues', 'edit dues', 'delete dues'
        ]);

        $accountantRole = Role::firstOrCreate(['name' => 'accountant']);
        $accountantRole->givePermissionTo([
            'manage finance', 'manage payments', 'view reports', 'view incomes', 'create expenses', 'view dashboard',
            'list dues', 'view dues', 'create dues', 'edit dues'
        ]);

        $blockAdminRole = Role::firstOrCreate(['name' => 'block-admin']);
        $blockAdminRole->givePermissionTo([
            'manage residents', 'manage announcements', 'view reports', 'view dashboard',
            'list dues', 'view dues'
        ]);

        $propertyOwnerRole = Role::firstOrCreate(['name' => 'property-owner']);
        $propertyOwnerRole->givePermissionTo([
            'view reports', 'use reservations', 'view dashboard',
            'list dues', 'view dues'
        ]);

        $residentRole = Role::firstOrCreate(['name' => 'resident']);
        $residentRole->givePermissionTo([
            'manage payments', 'use reservations', 'view dashboard',
            'list dues', 'view dues'
        ]);

        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $staffRole->givePermissionTo([
            'use maintenance', 'view dashboard'
        ]);

        Permission::firstOrCreate(['name' => 'manage budgets', 'guard_name' => 'web']);

        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'is_system' => 1]);
        $siteAdminRole = Role::firstOrCreate(['name' => 'site-admin']);
        $accountantRole = Role::firstOrCreate(['name' => 'accountant']);
        $siteAdminRole->givePermissionTo('manage budgets');
        $accountantRole->givePermissionTo('manage budgets');
        $superAdminRole->givePermissionTo(Permission::all());
    }
}
