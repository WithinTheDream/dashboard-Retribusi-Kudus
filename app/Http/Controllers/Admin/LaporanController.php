<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('laporan.view'), 403);

        $validated = $request->validate([
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'tahun' => ['nullable', 'integer', 'min:2020', 'max:2099'],
        ]);

        $bulan = $validated['bulan'] ?? now()->month;
        $tahun = $validated['tahun'] ?? now()->year;

        $tagihans = Tagihan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        $pembayaranQuery = Pembayaran::whereHas('tagihan', function ($query) use ($bulan, $tahun) {
            $query->where('bulan', $bulan)
                ->where('tahun', $tahun);
        })->with(['tagihan.wajibRetribusi', 'petugas'])->latest();

        $pembayarans = $pembayaranQuery->paginate(10);

        $rekap = [
            'total_tagihan' => $tagihans->count(),
            'total_nominal_tagihan' => $tagihans->sum('nominal'),
            'total_tagihan_lunas' => $tagihans->where('status', 'lunas')->count(),
            'total_pembayaran' => (clone $pembayaranQuery)->count(),
            'total_nominal_bayar' => (clone $pembayaranQuery)->sum('nominal_bayar'),
        ];

        return view('admin.laporan.index', compact(
            'bulan', 'tahun', 'pembayarans', 'rekap'
        ));
    }
}