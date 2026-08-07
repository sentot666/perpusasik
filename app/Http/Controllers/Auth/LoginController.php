<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function showMemberLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('anggota')) {
                return redirect()->route('member.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.member_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required'    => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $login = trim($request->login);

        // Cari user berdasarkan email, username, nama lengkap, atau NIS/Kode Anggota pada relasi member
        $user = User::where('email', $login)
            ->orWhere('username', $login)
            ->orWhere('name', $login)
            ->orWhereHas('member', function ($q) use ($login) {
                $q->where('member_code', $login)
                  ->orWhere('identity_number', $login);
            })
            ->first();

        if ($user && $user->is_active && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            // Update last login
            $user->update(['last_login_at' => now()]);

            if ($user->hasRole('anggota')) {
                return redirect()->intended(route('member.dashboard'));
            }
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'login' => 'Nama/Username atau password (tanggal lahir) yang Anda masukkan salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('opac.index');
    }
}
