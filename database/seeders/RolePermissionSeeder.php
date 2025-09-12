<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Örnek izinler
        $permissions = [
            'view dashboard',
            'manage sites',
            'manage blocks',
            'manage apartments',
            'manage residents',
            'manage finance',
            'manage announcements',
            'view reports',
        ];

        // İzinleri oluştur
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Super admin rolü
        $superAdminRole = Role::firstOrCreate(['name'=>'super-admin']);
        $superAdminRole->syncPermissions(Permission::all());

        // Kullanıcıya ata (ID 1 örnek)
        $user = User::find(1);
        if ($user) {
            $user->assignRole($superAdminRole);
        }
    }
}
