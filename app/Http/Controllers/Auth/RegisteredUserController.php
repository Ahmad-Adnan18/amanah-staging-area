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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // [BARU] Validasi Kode Registrasi Khusus Guru
            'registration_code' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Ambil kode rahasia dari file .env
                    $secretCode = env('GURU_REGISTRATION_CODE');
                    
                    // Jika .env tidak di-set, pendaftaran ditutup
                    if (empty($secretCode)) {
                        $fail('Pendaftaran guru saat ini sedang ditutup.');
                        return;
                    }

                    // Jika kode yang dimasukkan tidak cocok
                    if ($value !== $secretCode) {
                        $fail('Kode Registrasi Guru tidak valid.');
                    }
                }
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'ustadz_umum',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
