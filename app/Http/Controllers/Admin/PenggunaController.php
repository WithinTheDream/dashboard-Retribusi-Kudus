<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index()
    {
        $penggunas = User::latest()->paginate(10);

        return view('admin.pengguna.index', compact('penggunas'));
    }

    public function create()
    {
        return view('admin.pengguna.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,petugas,bendahara,user'],
        ]);

        User::create($validated);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna)
    {
        return view('admin.pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, User $pengguna)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('users', 'username')->ignore($pengguna->id),
            ],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($pengguna->id),
            ],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,petugas,bendahara,user'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $pengguna->update($validated);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $pengguna)
    {
        if ($pengguna->id === auth()->id()) {
            return back()->with(
                'error',
                'Anda tidak dapat menghapus akun yang sedang digunakan.'
            );
        }

        $pengguna->delete();

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil dihapus.');
    }
}