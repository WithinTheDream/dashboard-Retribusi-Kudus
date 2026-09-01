<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('laporan.view'), 403);

        $validated = $request->validate([
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'tahun' => ['nullable', 'integer', 'min:2020', 'max:2099'],
        ]);

        $bulan = (int) ($validated['bulan'] ?? now()->month);
        $tahun = (int) ($validated['tahun'] ?? now()->year);

        $tagihans = Tagihan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        $pembayaranQuery = Pembayaran::whereHas('tagihan', function ($query) use ($bulan, $tahun) {
            $query->where('bulan', $bulan)
                ->where('tahun', $tahun);
        })->with(['tagihan.wajibRetribusi', 'petugas'])->latest();

        $rekap = [
            'total_tagihan' => $tagihans->count(),
            'total_nominal_tagihan' => $tagihans->sum('nominal'),
            'total_tagihan_lunas' => $tagihans->where('status', 'lunas')->count(),
            'total_pembayaran' => (clone $pembayaranQuery)->count(),
            'total_nominal_bayar' => (clone $pembayaranQuery)->sum('nominal_bayar') ?? 0,
        ];

        $pembayarans = $pembayaranQuery->paginate(10);

        return view('admin.laporan.index', compact(
            'bulan', 'tahun', 'pembayarans', 'rekap'
        ));
    }

    public function exportPdf(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('laporan.export') && !auth()->user()->hasPermission('laporan.view'), 403);

        $validated = $request->validate([
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'tahun' => ['nullable', 'integer', 'min:2020', 'max:2099'],
        ]);

        $bulan = (int) ($validated['bulan'] ?? now()->month);
        $tahun = (int) ($validated['tahun'] ?? now()->year);

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $tagihans = Tagihan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        $pembayarans = Pembayaran::whereHas('tagihan', function ($query) use ($bulan, $tahun) {
            $query->where('bulan', $bulan)
                ->where('tahun', $tahun);
        })->with(['tagihan.wajibRetribusi.desa', 'tagihan.wajibRetribusi.kecamatan', 'petugas'])->latest()->get();

        $rekap = [
            'total_tagihan' => $tagihans->count(),
            'total_nominal_tagihan' => $tagihans->sum('nominal'),
            'total_tagihan_lunas' => $tagihans->where('status', 'lunas')->count(),
            'total_pembayaran' => $pembayarans->count(),
            'total_nominal_bayar' => $pembayarans->sum('nominal_bayar') ?? 0,
        ];

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'bulan', 'tahun', 'namaBulan', 'pembayarans', 'rekap'
        ))->setPaper('a4', 'portrait');

        $filename = "Laporan_Retribusi_Kudus_{$namaBulan[$bulan]}_{$tahun}.pdf";

        return $pdf->download($filename);
    }
}