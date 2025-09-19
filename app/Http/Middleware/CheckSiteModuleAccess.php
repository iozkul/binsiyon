<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSiteModuleAccess
{
    public function handle(Request $request, Closure $next, string $moduleName)
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        if ($user->site_id) {
            $site = $user->site; // User->site() ilişkisi varsayılıyor

            // Sitenin modüle erişimi var mı kontrol et
            if ($site && $site->modules()->where('name', $moduleName)->exists()) {
                return $next($request);
            }
        }

        abort(403, 'Bu modüle erişim yetkiniz bulunmamaktadır.');
    }
}
