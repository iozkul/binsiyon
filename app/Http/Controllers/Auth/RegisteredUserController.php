<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserConfirmationMail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'education_status' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name, // 'name' alanını otomatik oluşturuyoruz
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'education_status' => $request->education_status,
        ]);
// Eşsiz token oluştur ve kullanıcıya ata
        $user->confirmation_token = Str::random(60);
        $user->save();

        // Onay mailini gönder
        Mail::to($user->email)->send(new UserConfirmationMail($user));

        event(new Registered($user));

        Auth::login($user);

        //return redirect(route('dashboard', absolute: false));
        return redirect('/login')->with('status', 'Kayıt başarılı! Lütfen e-posta adresinize gönderilen link ile hesabınızı onaylayın.');
    }
}
