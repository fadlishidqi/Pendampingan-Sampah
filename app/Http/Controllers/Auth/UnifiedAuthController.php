<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UnifiedAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Login pakai email/password.
     * - Jika admin -> /admin/map
     * - Jika non-admin -> /dashboard
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();
        if ($user && !empty($user->is_admin)) {
            return redirect()->intended('/admin/map');
        }

        return redirect()->intended('/dashboard');
    }

    /**
     * Login guest tanpa email/password (klik tombol).
     * Guest tetap pakai 1 akun internal di tabel users.
     */
    public function guest(Request $request)
    {
        // 1 akun guest internal (email ini user tidak perlu tahu)
        $guest = User::firstOrCreate(
            ['email' => 'guest@pendampingan.local'],
            [
                'name' => 'Guest',
                'password' => Hash::make(str()->random(32)),
                'is_admin' => 0,
            ]
        );

        // Pastikan tetap non-admin
        if (!empty($guest->is_admin)) {
            $guest->is_admin = 0;
            $guest->save();
        }

        Auth::login($guest, false);
        $request->session()->regenerate();

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
