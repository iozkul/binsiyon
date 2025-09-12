<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Login olduktan sonra bu middleware çalışacak.
        if (Auth::check()) {
            $user = Auth::user();

            // 1. Öncelik: Admin tarafından yasaklı mı?
            if ($user->is_banned_by_admin) {
                Auth::logout(); // Kullanıcıyı sistemden at
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/login')->with('error', 'Hesabınız bir yönetici tarafından askıya alınmıştır.');
            }

            // 2. Öncelik: E-postasını onaylamış mı?
            if (!$user->is_email_confirmed) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/login')->with('error', 'Giriş yapabilmek için lütfen e-posta adresinizi onaylayın.');
            }
        }
        return $next($request);
    }
}
