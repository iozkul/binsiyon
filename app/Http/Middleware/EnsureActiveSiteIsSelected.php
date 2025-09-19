<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSiteIsSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Sadece super-admin olmayan ve site-admin rolüne sahip kullanıcılar için çalışır.
        if ($user && $user->hasRole('site-admin') && !$user->hasRole('super-admin')) {
            // Eğer yöneteceği site(ler) varsa ama aktif bir site seçmemişse
            if ($user->managedSites()->exists() && !session()->has('active_site_id')) {
                // Ve şu an zaten site seçme sayfasında değilse, oraya yönlendir.
                if (!$request->routeIs('context.selectSite')) {
                    return redirect()->route('context.selectSite');
                }
            }
        }
        return $next($request);
    }
}
