<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
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
            'username' => 'required|string|max:100|unique:users,username',
            'email'    => 'nullable|email|max:255|unique:users,email',
            'password' => ['required', Password::min(8)],
            'role'     => 'required|exists:roles,name',
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = true;

        $user = User::create($validated);
        $user->assignRole($request->role);

        return back()->with('success', 'User/Petugas berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:100|unique:users,username,' . $user->id,
            'email'     => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'role'      => 'required|exists:roles,name',
            'password'  => ['nullable', Password::min(8)],
            'is_active' => 'required|boolean',
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

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        $user->syncRoles([$request->role]);

        return back()->with('success', 'Data user/petugas berhasil diperbarui.');
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
