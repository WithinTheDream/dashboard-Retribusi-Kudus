<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setoran;
use Illuminate\Http\Request;

class SetoranController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('setoran.view'), 403);

        $status = $request->query('status');

        $query = Setoran::with(['petugas', 'bendahara'])->latest();

        if (in_array($status, ['menunggu', 'diterima', 'ditolak'])) {
            $query->where('status_setoran', $status);
        }

        $setorans = $query->paginate(10)->withQueryString();

        // Hitung badge untuk setiap tab
        $counts = [
            'all'      => Setoran::count(),
            'menunggu' => Setoran::where('status_setoran', 'menunggu')->count(),
            'diterima' => Setoran::where('status_setoran', 'diterima')->count(),
            'ditolak'  => Setoran::where('status_setoran', 'ditolak')->count(),
        ];

        return view('admin.setoran.index', compact('setorans', 'status', 'counts'));
    }

    public function show(Setoran $setoran)
    {
        abort_if(!auth()->user()->hasPermission('setoran.view'), 403);

        $setoran->load([
            'petugas',
            'bendahara',
            'details.pembayaran.tagihan.wajibRetribusi.desa',
            'details.pembayaran.tagihan.wajibRetribusi.kecamatan',
        ]);

        return view('admin.setoran.show', compact('setoran'));
    }

    public function verify(Request $request, Setoran $setoran)
    {
        abort_if(!auth()->user()->hasPermission('setoran.update'), 403);

        $validated = $request->validate([
            'status_setoran' => 'required|in:diterima,ditolak',
            'catatan'        => 'nullable|string|max:500',
        ]);

        $setoran->update([
            'status_setoran'   => $validated['status_setoran'],
            'bendahara_id'     => auth()->id(),
            'waktu_verifikasi' => now(),
            'catatan'          => $validated['catatan'] ?? null,
        ]);

        $pesan = $validated['status_setoran'] === 'diterima'
            ? "Setoran {$setoran->nomor_setoran} berhasil diterima dan dicatat ke kas."
            : "Setoran {$setoran->nomor_setoran} telah ditolak dengan catatan.";

        return redirect()->route('admin.setoran.index')
            ->with('success', $pesan);
    }
}
