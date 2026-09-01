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
        $tarifs = Tarif::with('jenisRetribusi')->latest()->get();
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
        $validated = $request->validate([
            'jenis_retribusi_id' => ['required', 'exists:jenis_retribusis,id'],
            'nominal'            => ['required', 'numeric', 'min:0'],
            'satuan'             => ['nullable', 'string', 'max:50'],
            'periode'            => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'is_aktif'           => ['nullable', 'boolean'],
        ]);

        // Default fallbacks untuk memastikan integritas basis data
        $validated['satuan'] = !empty($validated['satuan']) ? $validated['satuan'] : 'Bulan';
        $validated['periode'] = !empty($validated['periode']) ? $validated['periode'] : (int) date('Y');
        $validated['is_aktif'] = $request->boolean('is_aktif', true);

        Tarif::create($validated);

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
        $validated = $request->validate([
            'jenis_retribusi_id' => ['required', 'exists:jenis_retribusis,id'],
            'nominal'            => ['required', 'numeric', 'min:0'],
            'satuan'             => ['nullable', 'string', 'max:50'],
            'periode'            => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'is_aktif'           => ['nullable', 'boolean'],
        ]);

        $validated['satuan'] = !empty($validated['satuan']) ? $validated['satuan'] : 'Bulan';
        $validated['periode'] = !empty($validated['periode']) ? $validated['periode'] : (int) date('Y');
        $validated['is_aktif'] = $request->boolean('is_aktif', false);

        $tarif->update($validated);

        return redirect()->route('admin.tarif.index')->with('success', 'Data tarif berhasil diperbarui.');
    }

    public function destroy(Tarif $tarif)
    {
        abort_if(!auth()->user()->hasPermission('tarif.delete'), 403);
        $tarif->delete();
        return redirect()->route('admin.tarif.index')->with('success', 'Data tarif berhasil dihapus.');
    }
}
