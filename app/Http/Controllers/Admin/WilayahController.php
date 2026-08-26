<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('wilayah.view'), 403);
        $kecamatan = Kecamatan::with('desas')->get();
        return view('admin.wilayah.index', compact('kecamatan'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('wilayah.create'), 403);
        return view('admin.wilayah.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('wilayah.create'), 403);
        $validated = $request->validate([
            'kecamatan' => 'required|string|max:255',
        ]);

        Kecamatan::create($validated);

        return redirect()->route('admin.wilayah.index')->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->hasPermission('wilayah.update'), 403);
        $kecamatan = Kecamatan::with('desas')->findOrFail($id);
        return view('admin.wilayah.edit', compact('kecamatan'));
    }

    public function update(Request $request, $id)
    {
        abort_if(!auth()->user()->hasPermission('wilayah.update'), 403);
        $validated = $request->validate([
            'kecamatan' => 'required|string|max:255',
        ]);

        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->update($validated);

        return redirect()->route('admin.wilayah.index')->with('success', 'Data kecamatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        abort_if(!auth()->user()->hasPermission('wilayah.delete'), 403);
        $kecamatan = Kecamatan::findOrFail($id);

        if ($kecamatan->desas()->exists()) {
            return back()->with('error', 'Kecamatan tidak dapat dihapus karena masih memiliki data desa.');
        }

        $kecamatan->delete();

        return redirect()->route('admin.wilayah.index')->with('success', 'Kecamatan berhasil dihapus.');
    }

    public function storeDesa(Request $request, Kecamatan $kecamatan)
    {
        abort_if(!auth()->user()->hasPermission('wilayah.create'), 403);
        $validated = $request->validate([
            'desa' => 'required|string|max:255',
        ]);

        $kecamatan->desas()->create([
            'desa' => $validated['desa'],
        ]);

        return back()->with('success', 'Desa/Kelurahan berhasil ditambahkan ke Kecamatan ' . $kecamatan->kecamatan);
    }

    public function destroyDesa(Desa $desa)
    {
        abort_if(!auth()->user()->hasPermission('wilayah.delete'), 403);

        if ($desa->wajibRetribusi()->exists()) {
            return back()->with('error', 'Desa tidak dapat dihapus karena sudah memiliki data wajib retribusi.');
        }

        $desa->delete();

        return back()->with('success', 'Desa berhasil dihapus.');
    }
}
