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
        abort_if(!auth()->user()->hasPermission('tarif.view'), 403);
        // Mengambil data tarif beserta relasi jenis retribusinya
        $tarifs = Tarif::with('jenisRetribusi')->get();
        return view('admin.tarif.index', compact('tarifs'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('tarif.create'), 403);
        $jenisRetribusis = JenisRetribusi::all();
        return view('admin.tarif.create', compact('jenisRetribusis'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('tarif.create'), 403);
        $request->validate([
            'jenis_retribusi_id' => 'required|exists:jenis_retribusis,id',
            'nominal' => 'required|numeric|min:0',
        ]);

        Tarif::create($request->all());
        return redirect()->route('admin.tarif.index')->with('success', 'Data tarif berhasil ditambahkan.');
    }

    public function edit(Tarif $tarif)
    {
        abort_if(!auth()->user()->hasPermission('tarif.update'), 403);
        $jenisRetribusis = JenisRetribusi::all();
        return view('admin.tarif.edit', compact('tarif', 'jenisRetribusis'));
    }

    public function update(Request $request, Tarif $tarif)
    {
        abort_if(!auth()->user()->hasPermission('tarif.update'), 403);
        $request->validate([
            'jenis_retribusi_id' => 'required|exists:jenis_retribusis,id',
            'nominal' => 'required|numeric|min:0',
        ]);

        $tarif->update($request->all());
        return redirect()->route('admin.tarif.index')->with('success', 'Data tarif berhasil diperbarui.');
    }

    public function destroy(Tarif $tarif)
    {
        abort_if(!auth()->user()->hasPermission('tarif.delete'), 403);
        $tarif->delete();
        return redirect()->route('admin.tarif.index')->with('success', 'Data tarif berhasil dihapus.');
    }
}
