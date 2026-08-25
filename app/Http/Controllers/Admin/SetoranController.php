<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setoran;
use Illuminate\Http\Request;

class SetoranController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('setoran.view'), 403);
        
        $setorans = Setoran::with(['petugas', 'bendahara'])
            ->latest()
            ->paginate(10);

        return view('admin.setoran.index', compact('setorans'));
    }

    public function show(Setoran $setoran)
    {
        abort_if(!auth()->user()->hasPermission('setoran.view'), 403);
        
        // Asumsi relasi setoranDetails ada jika diperlukan
        $setoran->load(['petugas', 'bendahara']);
        
        return view('admin.setoran.show', compact('setoran'));
    }

    public function verify(Request $request, Setoran $setoran)
    {
        abort_if(!auth()->user()->hasPermission('setoran.update'), 403);

        $validated = $request->validate([
            'status_setoran' => 'required|in:diterima,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $setoran->update([
            'status_setoran' => $validated['status_setoran'],
            'bendahara_id' => auth()->id(),
            'waktu_verifikasi' => now(),
            'catatan' => $validated['catatan'],
        ]);

        return redirect()->route('admin.setoran.index')
            ->with('success', 'Status setoran berhasil diverifikasi.');
    }
}
