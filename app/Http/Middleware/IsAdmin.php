<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin'))) {
            // Eğer sahipse, isteğin devam etmesine izin ver
            return $next($request);
        }

        abort(403, 'Bu sayfaya erişim yetkiniz yok.');
    }
}
