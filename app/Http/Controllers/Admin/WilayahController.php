<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::with('desas')->get();
        return view('admin.wilayah.index', compact('kecamatan'));
    }

    public function create()
    {
        return view('admin.wilayah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kecamatan' => 'required|string|max:255',
        ]);

        Kecamatan::create([
            'kecamatan' => $request->kecamatan,
        ]);

        return redirect()->route('admin.wilayah.index')->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        return view('admin.wilayah.edit', compact('kecamatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kecamatan' => 'required|string|max:255',
        ]);

        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->update([
            'kecamatan' => $request->kecamatan,
        ]);

        return redirect()->route('admin.wilayah.index')->with('success', 'Data kecamatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->delete();

        return redirect()->route('admin.wilayah.index')->with('success', 'Kecamatan berhasil dihapus.');
    }
}
