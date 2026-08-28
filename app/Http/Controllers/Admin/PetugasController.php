<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\PenugasanWilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('petugas.view'), 403);

        $petugasList = User::where('role', 'petugas')
            ->orWhereHas('roleRelation', function ($query) {
                $query->where('name', 'petugas');
            })
            ->with(['penugasanWilayahs.kecamatan', 'penugasanWilayahs.desa'])
            ->latest()
            ->paginate(10);

        return view('admin.petugas.index', compact('petugasList'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('petugas.create'), 403);

        $kecamatans = Kecamatan::orderBy('kecamatan', 'asc')->get();

        return view('admin.petugas.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('petugas.create'), 403);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username'     => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_hp'        => ['required', 'string', 'max:20'],
            'password'     => ['required', 'string', 'min:6'],
            'kecamatan_id' => ['required', 'exists:kecamatans,id'],
            'desa_id'      => ['required', 'exists:desas,id'],
            'rw'           => ['nullable', 'string', 'max:3'],
        ]);

        $rolePetugas = Role::where('name', 'petugas')->first();

        // 1. Buat user Petugas
        $user = User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'username'     => $validated['username'],
            'email'        => $validated['email'],
            'no_hp'        => $validated['no_hp'],
            'password'     => Hash::make($validated['password']),
            'role'         => 'petugas',
            'role_id'      => $rolePetugas?->id,
        ]);

        // 2. Buat Penugasan Wilayah
        PenugasanWilayah::create([
            'user_id'      => $user->id,
            'kecamatan_id' => $validated['kecamatan_id'],
            'desa_id'      => $validated['desa_id'],
            'rw'           => !empty($validated['rw']) ? str_pad($validated['rw'], 3, '0', STR_PAD_LEFT) : null,
        ]);

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', "Petugas '{$user->nama_lengkap}' berhasil ditambahkan dan ditugaskan.");
    }

    public function edit(User $petuga)
    {
        abort_if(!auth()->user()->hasPermission('petugas.update'), 403);

        $petugas = $petuga;
        $petugas->load(['penugasanWilayahs.kecamatan', 'penugasanWilayahs.desa']);

        $penugasan = $petugas->penugasanWilayahs->first();
        $kecamatans = Kecamatan::orderBy('kecamatan', 'asc')->get();
        $desas = $penugasan ? Desa::where('kec_id', $penugasan->kecamatan_id)->get() : collect();

        return view('admin.petugas.edit', compact('petugas', 'penugasan', 'kecamatans', 'desas'));
    }

    public function update(Request $request, User $petuga)
    {
        abort_if(!auth()->user()->hasPermission('petugas.update'), 403);

        $petugas = $petuga;

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username'     => [
                'required', 'string', 'max:255',
                Rule::unique('users', 'username')->ignore($petugas->id),
            ],
            'email'        => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($petugas->id),
            ],
            'no_hp'        => ['required', 'string', 'max:20'],
            'password'     => ['nullable', 'string', 'min:6'],
            'kecamatan_id' => ['required', 'exists:kecamatans,id'],
            'desa_id'      => ['required', 'exists:desas,id'],
            'rw'           => ['nullable', 'string', 'max:3'],
        ]);

        $userData = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'username'     => $validated['username'],
            'email'        => $validated['email'],
            'no_hp'        => $validated['no_hp'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $petugas->update($userData);

        // Update atau buat penugasan wilayah
        $penugasanData = [
            'kecamatan_id' => $validated['kecamatan_id'],
            'desa_id'      => $validated['desa_id'],
            'rw'           => !empty($validated['rw']) ? str_pad($validated['rw'], 3, '0', STR_PAD_LEFT) : null,
        ];

        PenugasanWilayah::updateOrCreate(
            ['user_id' => $petugas->id],
            $penugasanData
        );

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', "Data petugas '{$petugas->nama_lengkap}' dan penugasan wilayah berhasil diperbarui.");
    }

    public function destroy(User $petuga)
    {
        abort_if(!auth()->user()->hasPermission('petugas.delete'), 403);

        $petugas = $petuga;

        if ($petugas->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Hapus penugasan wilayah terkait
        PenugasanWilayah::where('user_id', $petugas->id)->delete();

        $petugas->delete();

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Akun petugas dan penugasan wilayah berhasil dihapus.');
    }
}
