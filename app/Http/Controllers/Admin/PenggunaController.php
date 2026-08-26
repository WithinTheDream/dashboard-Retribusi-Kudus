<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('users.view'), 403);

        $query = User::with('roleRelation');

        // Jika bukan Super Admin (misal Admin Dinas), batasi hanya melihat petugas dan warga
        if (!auth()->user()->isSuperAdmin()) {
            $query->whereIn('role', ['petugas', 'user']);
        }

        $penggunas = $query->latest()->paginate(10);

        return view('admin.pengguna.index', compact('penggunas'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('users.create'), 403);

        // Super Admin dapat membuat semua role, Admin Dinas hanya petugas dan warga
        $roles = auth()->user()->isSuperAdmin()
            ? Role::all()
            : Role::whereIn('name', ['petugas', 'user'])->get();

        return view('admin.pengguna.create', compact('roles'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('users.create'), 403);

        $allowedRoles = auth()->user()->isSuperAdmin()
            ? Role::pluck('name')->toArray()
            : ['petugas', 'user'];

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username'     => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_hp'        => ['required', 'string', 'max:20'],
            'password'     => ['required', 'string', 'min:8'],
            'role'         => ['required', 'string', Rule::in($allowedRoles)],
        ]);

        $role = Role::where('name', $validated['role'])->first();
        if ($role) {
            $validated['role_id'] = $role->id;
        }

        User::create($validated);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna)
    {
        abort_if(!auth()->user()->hasPermission('users.update'), 403);

        // Cegah Admin Dinas mengedit akun Super Admin / Pimpinan / Bendahara
        if (!auth()->user()->isSuperAdmin() && !in_array($pengguna->role, ['petugas', 'user'])) {
            abort(403, 'Anda hanya dapat mengelola pengguna dengan role Petugas dan Warga.');
        }

        $roles = auth()->user()->isSuperAdmin()
            ? Role::all()
            : Role::whereIn('name', ['petugas', 'user'])->get();

        return view('admin.pengguna.edit', compact('pengguna', 'roles'));
    }

    public function update(Request $request, User $pengguna)
    {
        abort_if(!auth()->user()->hasPermission('users.update'), 403);

        if (!auth()->user()->isSuperAdmin() && !in_array($pengguna->role, ['petugas', 'user'])) {
            abort(403, 'Anda hanya dapat mengelola pengguna dengan role Petugas dan Warga.');
        }

        $allowedRoles = auth()->user()->isSuperAdmin()
            ? Role::pluck('name')->toArray()
            : ['petugas', 'user'];

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username'     => [
                'required', 'string', 'max:255',
                Rule::unique('users', 'username')->ignore($pengguna->id),
            ],
            'email'        => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($pengguna->id),
            ],
            'no_hp'        => ['required', 'string', 'max:20'],
            'password'     => ['nullable', 'string', 'min:8'],
            'role'         => ['required', 'string', Rule::in($allowedRoles)],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $role = Role::where('name', $validated['role'])->first();
        if ($role) {
            $validated['role_id'] = $role->id;
        }

        $pengguna->update($validated);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $pengguna)
    {
        abort_if(!auth()->user()->hasPermission('users.delete'), 403);

        if ($pengguna->id === auth()->id()) {
            return back()->with(
                'error',
                'Anda tidak dapat menghapus akun yang sedang digunakan.'
            );
        }

        if (!auth()->user()->isSuperAdmin() && !in_array($pengguna->role, ['petugas', 'user'])) {
            abort(403, 'Anda hanya dapat menghapus pengguna dengan role Petugas dan Warga.');
        }

        $pengguna->delete();

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil dihapus.');
    }
}