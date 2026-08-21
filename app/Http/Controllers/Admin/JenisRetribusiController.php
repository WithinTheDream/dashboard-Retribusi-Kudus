<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisRetribusi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisRetribusiController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('jenis_retribusi.view'), 403);
        $jenisRetribusi = JenisRetribusi::latest()->paginate(10);

        return view('admin.jenis-retribusi.index', compact(
            'jenisRetribusi'
        ));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('jenis_retribusi.create'), 403);
        return view('admin.jenis-retribusi.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('jenis_retribusi.create'), 403);
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:255', 'unique:jenis_retribusis,kode'],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        JenisRetribusi::create($validated);

        return redirect()
            ->route('admin.jenis-retribusi.index')
            ->with('success', 'Jenis retribusi berhasil ditambahkan.');
    }

    public function edit(JenisRetribusi $jenisRetribusi)
    {
        abort_if(!auth()->user()->hasPermission('jenis_retribusi.update'), 403);
        return view('admin.jenis-retribusi.edit', compact(
            'jenisRetribusi'
        ));
    }

    public function update(
        Request $request,
        JenisRetribusi $jenisRetribusi
    ) {
        abort_if(!auth()->user()->hasPermission('jenis_retribusi.update'), 403);
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jenis_retribusis', 'kode')
                    ->ignore($jenisRetribusi->id),
            ],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $jenisRetribusi->update($validated);

        return redirect()
            ->route('admin.jenis-retribusi.index')
            ->with('success', 'Jenis retribusi berhasil diperbarui.');
    }

    public function destroy(JenisRetribusi $jenisRetribusi)
    {
        abort_if(!auth()->user()->hasPermission('jenis_retribusi.delete'), 403);
        if ($jenisRetribusi->tarifs()->exists()) {
            return back()->with(
                'error',
                'Jenis retribusi tidak dapat dihapus karena sudah memiliki tarif.'
            );
        }

        if ($jenisRetribusi->wajibRetribusi()->exists()) {
            return back()->with(
                'error',
                'Jenis retribusi tidak dapat dihapus karena sudah digunakan.'
            );
        }

        $jenisRetribusi->delete();

        return redirect()
            ->route('admin.jenis-retribusi.index')
            ->with('success', 'Jenis retribusi berhasil dihapus.');
    }
}
