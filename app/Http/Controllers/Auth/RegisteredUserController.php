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

            // [BARU] Validasi Kode Registrasi Khusus Guru (Double Validation)
            'registration_code' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $secretCode = env('GURU_REGISTRATION_CODE');
                    if (empty($secretCode)) {
                        $fail('Pendaftaran guru saat ini sedang ditutup.');
                        return;
                    }
                    if ($value !== $secretCode) {
                        $fail('Kode Registrasi Aplikasi tidak valid.');
                    }
                }
            ],
            // Validasi Kode Guru Unik
            'teacher_code' => [
                'required', 
                'string', 
                'exists:teachers,teacher_code', // Wajib ada di tabel teachers
                function ($attribute, $value, $fail) {
                     // Cek apakah kode guru ini sudah ada yang nge-klaim (user_id != null)
                     $teacher = \App\Models\Teacher::where('teacher_code', $value)->first();
                     if ($teacher && $teacher->user_id !== null) {
                         $fail('Kode guru ini sudah terdaftar dengan akun lain.');
                     }
                }
            ],
        ]);

        // Cari data guru berdasarkan kode
        $teacher = \App\Models\Teacher::where('teacher_code', $request->teacher_code)->first();

        // Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'ustadz_umum',
        ]);

        // [BARU] Hubungkan User dengan Data Guru
        if ($teacher) {
            $teacher->update(['user_id' => $user->id]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
