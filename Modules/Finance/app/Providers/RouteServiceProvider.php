<?php

namespace Modules\Finance\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Modülün controller'ları için varsayılan namespace.
     */
    protected string $moduleNamespace = 'Modules\Finance\App\Http\Controllers';

    /**
     * Rota tanımlamalarını yapar.
     */
    public function boot(): void
    {

        parent::boot();
    }
    public function map(): void
    {

        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Modül için "web" rotalarını tanımlar.
     * Bu rotalar session, CSRF koruması gibi özelliklere sahip olur.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web') // <-- SORUNU ÇÖZEN ANAHTAR SATIR
        ->namespace($this->moduleNamespace)
            ->group(module_path('Finance', '/routes/web.php'));
    }

    /**
     * Modül için "api" rotalarını tanımlar.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Finance', '/routes/api.php'));
    }
}
