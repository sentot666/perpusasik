<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Only show non-anggota users in this management module
        $query = User::with('roles')->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'anggota');
        });

        if ($search = $request->search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }
        $users = $query->latest()->paginate(20)->withQueryString();
        
        // Exclude anggota from dropdown options
        $roles = Role::where('name', '!=', 'anggota')->get();

        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:100',
            'email'    => 'nullable|email|max:255',
            'password' => ['required', Password::min(8)],
            'role'     => 'required|exists:roles,name',
        ], [
            'password.min'  => 'Password minimal harus 8 karakter.',
            'role.required' => 'Role/Peran wajib dipilih.',
            'role.exists'   => 'Role yang dipilih tidak valid atau belum tersedia.',
        ]);

        // 1. Cek apakah ada akun AKTIF dengan username yang sama
        $activeUserByUsername = User::where('username', $request->username)->first();
        if ($activeUserByUsername) {
            return back()->withInput()->withErrors(['username' => 'Username ini sudah digunakan oleh akun lain yang aktif.']);
        }

        // 2. Cek apakah ada akun AKTIF dengan email yang sama
        if ($request->filled('email')) {
            $activeUserByEmail = User::where('email', $request->email)->first();
            if ($activeUserByEmail) {
                return back()->withInput()->withErrors(['email' => 'Email ini sudah digunakan oleh akun lain yang aktif.']);
            }
        }

        $roleName = $validated['role'];
        unset($validated['role']);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = true;

        try {
            // 3. Cek apakah ada akun di tong sampah (soft-deleted) dengan username atau email tersebut
            $trashedByUsername = User::onlyTrashed()->where('username', $request->username)->first();
            $trashedByEmail    = $request->filled('email') 
                ? User::onlyTrashed()->where('email', $request->email)->first() 
                : null;

            $trashedTarget = $trashedByUsername ?? $trashedByEmail;

            if ($trashedTarget) {
                // Jika trashedByUsername dan trashedByEmail adalah dua baris berbeda, bersihkan email baris kedua agar tidak bentrok
                if ($trashedByUsername && $trashedByEmail && $trashedByUsername->id !== $trashedByEmail->id) {
                    $trashedByEmail->update(['email' => null]);
                }

                DB::transaction(function () use ($trashedTarget, $validated, $roleName) {
                    $trashedTarget->restore();
                    $trashedTarget->name = $validated['name'];
                    $trashedTarget->username = $validated['username'];
                    $trashedTarget->email = $validated['email'] ?? null;
                    $trashedTarget->password = $validated['password'];
                    $trashedTarget->is_active = true;
                    $trashedTarget->save();

                    $trashedTarget->syncRoles([$roleName]);
                });

                return back()->with('success', 'Akun petugas yang sebelumnya pernah dinonaktifkan/dihapus berhasil dipulihkan dan diperbarui.');
            }

            // 4. Jika murni akun baru
            DB::transaction(function () use ($validated, $roleName) {
                $user = User::create($validated);
                $user->assignRole($roleName);
            });

            return back()->with('success', 'User/Petugas berhasil ditambahkan.');
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan: Username atau Email tersebut sudah terdaftar di sistem. Harap gunakan username/email lain.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($user->id)],
            'email'     => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'      => 'required|exists:roles,name',
            'password'  => ['nullable', Password::min(8)],
            'is_active' => 'required|boolean',
        ], [
            'username.unique' => 'Username ini sudah terdaftar di sistem.',
            'email.unique'    => 'Email ini sudah terdaftar di sistem.',
            'password.min'    => 'Password minimal harus 8 karakter.',
            'role.required'   => 'Role/Peran wajib dipilih.',
            'role.exists'     => 'Role yang dipilih tidak valid atau belum tersedia.',
        ]);

        // Prevent self-demotion or self-deactivation
        if ($user->id === auth()->id()) {
            if ($request->role !== 'super-admin' && $user->hasRole('super-admin')) {
                return back()->with('error', 'Anda tidak dapat mengubah hak akses Super Admin Anda sendiri.');
            }
            if (!$request->is_active) {
                return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            }
        }

        try {
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($request->password);
            } else {
                unset($validated['password']);
            }

            $roleName = $validated['role'];
            unset($validated['role']);

            DB::transaction(function () use ($user, $validated, $roleName) {
                $user->update($validated);
                $user->syncRoles([$roleName]);
            });

            return back()->with('success', 'Data user/petugas berhasil diperbarui.');
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui: Username atau Email tersebut sudah digunakan oleh akun lain di sistem.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }
        $user->delete();
        return back()->with('success', 'User/Petugas berhasil dihapus.');
    }
}
