<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WajibRetribusi;
use App\Models\PengajuanWajibRetribusi;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Setoran;

class DashboardController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('dashboard.view'), 403);

        $totalPengajuan = PengajuanWajibRetribusi::where('status_pengajuan', 'menunggu')->count();
        $totalWargaAktif = WajibRetribusi::count();
        $totalTagihanBelumBayar = Tagihan::where('status', 'belum_bayar')->count();
        $totalTagihanLunas = Tagihan::where('status', 'lunas')->count();
        $totalPendapatan = Pembayaran::sum('nominal_bayar');
        $totalNominalTagihan = Tagihan::sum('nominal');
        $totalSetoranMenunggu = Setoran::where('status_setoran', 'menunggu')->count();

        $persenKepatuhan = $totalNominalTagihan > 0
            ? round(($totalPendapatan / $totalNominalTagihan) * 100, 1)
            : 0;

        return view('admin.dashboard', compact(
            'totalPengajuan',
            'totalWargaAktif',
            'totalTagihanBelumBayar',
            'totalTagihanLunas',
            'totalPendapatan',
            'totalNominalTagihan',
            'totalSetoranMenunggu',
            'persenKepatuhan'
        ));
    }
}
