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
            'manage budgets',       // Bütçe yönetimi (Faz 1)
            'approve expenses',     // Giderleri onaylama (Faz 3)
            'manage bank accounts', // Banka ve Kasa hesaplarını yönetme (Faz 4)
            'manage vendors',       // Tedarikçileri yönetme (Faz 4)
            'view finance',         // Finansal verileri sadece görüntüleme (Denetçi için)
            'view own dashboard',
            'manage site settings',
            'manage budgets',       // Bütçe oluşturma ve yönetme
            'manage expenses',      // Giderleri yönetme (mevcut create_expenses'ten daha kapsamlı)
            'manage vendors',       // Tedarikçileri yönetme
            'view financial reports', // Tüm finansal raporları görme
            'manage payrolls',      // Personel ve bordroları yönetme
            'manage legal cases',    // Hukuki süreçleri ve karar defterini yönetme
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
            'auditor',
            'accountant',
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
            'manage budgets', 'approve expenses', 'manage bank accounts', 'manage vendors', 'view finance',
            'view own dashboard',
            'manage site settings',
            'manage expenses', 'manage vendors', 'view financial reports',
            'manage payrolls', 'manage legal cases',
        ]);
// Accountant (Muhasebeci)
        Role::firstOrCreate(['name' => 'accountant'])
        ->givePermissionTo([
            'manage finance', 'manage payments', 'view reports', 'view incomes', 'create expenses',
            // --- YENİ EKLENEN YETKİLER ---
            'manage bank accounts', 'manage vendors', 'view finance',
            'view own dashboard',
            'manage budgets', 'manage expenses', 'manage vendors', 'view financial reports', 'manage payrolls',

        ]);

        // Auditor (Denetçi) - YENİ ROL
        Role::firstOrCreate(['name' => 'auditor'])->givePermissionTo([
            'view reports', 'view incomes',
            'view own dashboard',
            'view finance',
        ]);
        Role::findByName('block-admin')->givePermissionTo([
            'manage apartments',
            'manage residents',
            'manage announcements',
            'view own dashboard',
        ]);

        Role::findByName('resident')->givePermissionTo([
            'manage announcements',
        ]);

        Role::findByName('staff')->givePermissionTo([
            'use maintenance',
            'view own dashboard',
        ]);

        Role::findByName('property-owner')->givePermissionTo([
            'manage apartments',
            'manage residents',
            'approve expenses',
            'view reports',
            'view own dashboard',
        ]);
    }
}
