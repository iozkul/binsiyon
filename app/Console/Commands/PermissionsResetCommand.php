<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionsResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting permission system reset...');

        // 1. Tüm önbellekleri temizle
        Artisan::call('optimize:clear');
        Artisan::call('permission:cache-reset');
        $this->info('All caches cleared.');

        // 2. Spatie tablolarını boşalt
        $this->info('Truncating roles and permissions tables...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        Role::truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->info('Tables truncated.');

        // 3. Yetkileri ve Rolleri yeniden oluştur
        $this->info('Creating new roles and permissions...');
        $permissions = [
            'manage users', 'manage sites', 'manage finance',
            'manage payments', 'manage announcements', 'view reports'
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $superAdminRole = Role::create(['name' => 'super-admin']);
        $siteAdminRole = Role::create(['name' => 'site-admin']);
        Role::create(['name' => 'resident']);

        // 4. Yetkileri rollere ata
        $superAdminRole->givePermissionTo(Permission::all());
        $siteAdminRole->givePermissionTo(['manage sites', 'view reports']);
        $this->info('Roles and permissions created and assigned.');

        // 5. İlk kullanıcıyı bul ve super-admin rolünü ata
        $user = User::first();
        if ($user) {
            $user->syncRoles(['super-admin']);
            $this->info("User '{$user->name}' (ID: {$user->id}) has been assigned the super-admin role.");

            // 6. KESİN TEST: Yetkiyi anında kontrol et
            if ($user->can('manage users')) {
                $this->info('SUCCESS: The user can now "manage users".');
            } else {
                $this->error('FAILURE: The user STILL CANNOT "manage users". There is a deep underlying issue.');
            }
        } else {
            $this->warn('No users found in the database to assign the role to.');
        }

        $this->info('Permission system reset completed!');
        return 0;
    }
}
