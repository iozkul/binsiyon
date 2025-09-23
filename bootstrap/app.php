<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
//use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'site.selected' => \App\Http\Middleware\EnsureActiveSiteIsSelected::class,

        ]);
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\CheckUserStatus::class,
            \App\Http\Middleware\EnsureActiveSiteIsSelected::class,
        ]);
    })
    ->withProviders([
        App\Providers\NavbarServiceProvider::class,
        ...require __DIR__.'/module_providers.php',
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        /*
        // --- YENİ EKLENEN KOD BLOĞU ---
        $exceptions->renderable(function (AuthorizationException $e, $request) {
            // Eğer istek bir web sayfasından geliyorsa (API değilse)
            if (! $request->expectsJson()) {
                // Kullanıcıyı geldiği bir önceki sayfaya yönlendir
                // ve session'a 'error' adında bir uyarı mesajı ekle.
                return redirect()
                    ->back()
                    ->with('error', 'Bu işlemi gerçekleştirmek için yetkiniz bulunmamaktadır.');
            }
        });
        // --- KOD BLOĞU BİTİŞİ ---
        */
        // --- BU KISMI GÜNCELLEYİN ---
        /*
        $exceptions->renderable(function (HttpException $e, $request) {
            // Eğer yakalanan hatanın durum kodu 403 (Forbidden) ise
            if ($e->getStatusCode() === 403) {
                // ve istek bir web sayfasından geliyorsa (API değilse)
                if (! $request->expectsJson()) {
                    // Kullanıcıyı geldiği bir önceki sayfaya yönlendir
                    // ve session'a 'error' adında bir uyarı mesajı ekle.
                    return redirect()
                        ->back()
                        ->with('error', 'Bu işlemi gerçekleştirmek için yetkiniz bulunmamaktadır.');
                }
            }
            // --- DÜZELTME BİTTİ ---
        });
        */
        $exceptions->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                if (! $request->expectsJson()) {
                    $user = auth()->user();
                    $route = $request->route()?->getName() ?? $request->path();
                    $method = $request->method();

                    // Eğer Spatie Permission paketi kullanıyorsan
                    $missingPermission = method_exists($e, 'getMessage') ? $e->getMessage() : null;

                    $errorMessage = "Yetkisiz işlem: ";
                    $errorMessage .= $user
                        ? "Kullanıcı [{$user->id} - {$user->email}] "
                        : "Anonim kullanıcı ";
                    $errorMessage .= "[{$method}] {$route} erişmek istedi.";

                    if ($missingPermission) {
                        $errorMessage .= " Eksik izin: {$missingPermission}.";
                    }

                    return redirect()
                        ->back()
                        ->with('error', $errorMessage);
                }
            }
        });


    })->create();
