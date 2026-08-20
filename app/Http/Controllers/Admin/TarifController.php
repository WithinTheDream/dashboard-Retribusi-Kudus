<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tarif;
use App\Models\JenisRetribusi;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    public function index()
    {
        // Mengambil data tarif beserta relasi jenis retribusinya
        $tarifs = Tarif::with('jenisRetribusi')->get();
        return view('admin.tarif.index', compact('tarifs'));
    }

    public function create()
    {
        $jenisRetribusis = JenisRetribusi::all();
        return view('admin.tarif.create', compact('jenisRetribusis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_retribusi_id' => 'required|exists:jenis_retribusis,id',
            'nominal' => 'required|numeric|min:0',
        ]);

        Tarif::create($request->all());
        return redirect()->route('admin.tarif.index')->with('success', 'Data tarif berhasil ditambahkan.');
    }

    public function edit(Tarif $tarif)
    {
        $jenisRetribusis = JenisRetribusi::all();
        return view('admin.tarif.edit', compact('tarif', 'jenisRetribusis'));
    }

    public function update(Request $request, Tarif $tarif)
    {
        $request->validate([
            'jenis_retribusi_id' => 'required|exists:jenis_retribusis,id',
            'nominal' => 'required|numeric|min:0',
        ]);

        $tarif->update($request->all());
        return redirect()->route('admin.tarif.index')->with('success', 'Data tarif berhasil diperbarui.');
    }

    public function destroy(Tarif $tarif)
    {
        $tarif->delete();
        return redirect()->route('admin.tarif.index')->with('success', 'Data tarif berhasil dihapus.');
    }
}
