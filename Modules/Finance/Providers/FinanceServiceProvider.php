<?php

namespace Modules\Finance\Providers;

use Illuminate\Support\ServiceProvider;

class FinanceServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

    }
}
