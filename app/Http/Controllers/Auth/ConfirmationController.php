<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ConfirmationController extends Controller
{
    public function confirm(string $token)
    {
        $user = User::where('confirmation_token', $token)->first();

        if (!$user) {
            // Token geçersizse veya kullanıcı bulunamazsa
            return redirect('/login')->with('error', 'Geçersiz onay kodu!');
        }

        $user->is_email_confirmed = true;
        $user->confirmation_token = null; // Token'ı temizle
        $user->save();

        return redirect('/login')->with('status', 'Hesabınız başarıyla onaylandı. Artık giriş yapabilirsiniz.');
    }
}
