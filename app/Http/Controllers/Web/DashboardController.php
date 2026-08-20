<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PengajuanWajibRetribusi;
use App\Models\WajibRetribusi;
use App\Models\Tagihan;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengumpulkan data statistik untuk ditampilkan di Card Dashboard Web
        $totalPengajuan = PengajuanWajibRetribusi::count();
        $totalWargaAktif = WajibRetribusi::where('status_aktif', true)->count();
        $totalTagihanBelumBayar = Tagihan::where('status', 'belum_bayar')->count();
        $totalPendapatan = Pembayaran::sum('nominal_bayar');

        return view('admin.dashboard', compact(
            'totalPengajuan',
            'totalWargaAktif',
            'totalTagihanBelumBayar',
            'totalPendapatan'
        ));
    }
}
