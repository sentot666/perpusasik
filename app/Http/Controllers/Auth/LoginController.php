<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showAdminLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('anggota')) {
                return redirect()->route('member.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.admin_login');
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

    public function adminLogin(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required'    => 'Username atau email administrator wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $login = trim($request->login);

        // Cari user administrator / petugas (hanya berdasarkan username atau email)
        $user = User::where(function($q) use ($login) {
                $q->where('email', $login)
                  ->orWhere('username', $login);
            })
            ->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            Log::warning("Security Alert: Failed admin login attempt for [{$login}] from IP [{$request->ip()}].");
            throw ValidationException::withMessages([
                'login' => 'Kredensial login administrator yang Anda masukkan salah.',
            ]);
        }

        if (!$user->is_active) {
            Log::warning("Security Alert: Inactive admin account login attempt for [{$login}] from IP [{$request->ip()}].");
            throw ValidationException::withMessages([
                'login' => 'Akun administrator ini sedang dinonaktifkan.',
            ]);
        }

        // Pastikan bukan akun anggota
        if ($user->hasRole('anggota')) {
            Log::warning("Security Alert: Unauthorized member tried to access admin login [{$login}] from IP [{$request->ip()}].");
            throw ValidationException::withMessages([
                'login' => 'Akses ditolak: Akun Anda terdaftar sebagai Anggota, bukan Administrator/Petugas.',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $user->update(['last_login_at' => now()]);
        Log::info("Security Audit: Admin user [{$user->username}] (ID: {$user->id}) successfully logged in from IP [{$request->ip()}].");

        return redirect()->intended(route('dashboard'));
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
            Log::info("Security Audit: User [{$user->username}] (ID: {$user->id}) logged in successfully from IP [{$request->ip()}].");

            if ($user->hasRole('anggota')) {
                return redirect()->intended(route('member.dashboard'));
            }
            return redirect()->intended(route('dashboard'));
        }

        Log::warning("Security Alert: Failed portal login attempt for identifier [{$login}] from IP [{$request->ip()}].");

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
