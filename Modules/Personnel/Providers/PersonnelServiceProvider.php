<?php

namespace Modules\Personnel\Providers;

use Illuminate\Support\ServiceProvider;

class PersonnelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'personnel');
    }

    public function register()
    {
        //
    }
}
