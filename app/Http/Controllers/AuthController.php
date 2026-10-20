<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        // Normalisasi email; jangan mengubah isi password.
        if (is_string($request->input('email'))) {
            $request->merge([
                'email' => Str::lower(trim($request->input('email'))),
            ]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'max:64', 'confirmed'],
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar. Silakan login.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.max' => 'Email maksimal 255 karakter.',
            'password.min' => 'Password minimal 12 karakter.',
            'password.max' => 'Password maksimal 64 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ], ['name' => 'Nama', 'email' => 'Email', 'password' => 'Password']);

        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role = 'user'; // Ditentukan server, bukan dari input form.
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil. Anda masuk sebagai pengguna.');
    }

    public function login(Request $request): RedirectResponse
    {
        if (is_string($request->input('email'))) {
            $request->merge([
                'email' => Str::lower(trim($request->input('email'))),
            ]);
        }

        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'max' => ':attribute terlalu panjang.',
        ], ['email' => 'Email', 'password' => 'Password']);

        $key = 'login:'.hash('sha256', $credentials['email'].'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        if (! Auth::attempt($credentials)) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'email' => 'Email atau password tidak sesuai.',
            ]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        // Tujuan tetap agar pengguna tidak diarahkan ke halaman admin yang ditolak.
        return redirect()->route('dashboard')
            ->with('success', 'Berhasil masuk. Selamat datang di dashboard.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}
