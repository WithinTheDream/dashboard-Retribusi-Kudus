<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisRetribusi;
use App\Models\User;
use App\Models\WajibRetribusi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalWajibRetribusi = WajibRetribusi::count();

        $pengajuanMenunggu = WajibRetribusi::where(
            'status_pengajuan',
            'menunggu'
        )->count();

        $totalJenisRetribusi = JenisRetribusi::count();

        $totalUsers = User::count();

        return view('admin.dashboard', compact(
            'totalWajibRetribusi',
            'pengajuanMenunggu',
            'totalJenisRetribusi',
            'totalUsers'
        ));
    }
}