<?php

namespace App\Http\Controllers\Api\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\PenugasanWilayah;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Ambil penugasan wilayah untuk petugas ini
        $penugasans = PenugasanWilayah::where('user_id', $user->id)->get();

        if ($penugasans->isEmpty()) {
            return response()->json([
                'success' => true,
                'has_assignment' => false,
                'message' => 'Akun Anda belum memiliki penugasan wilayah. Silakan hubungi Admin Dinas.',
                'data' => []
            ]);
        }

        // Ambil tagihan yang belum bayar di wilayah penugasan
        $tagihanQuery = Tagihan::with(['wajibRetribusi.desa', 'wajibRetribusi.kecamatan'])
            ->where('status', 'belum_bayar')
            ->whereHas('wajibRetribusi', function ($query) use ($penugasans) {
                $query->where(function ($q) use ($penugasans) {
                    foreach ($penugasans as $penugasan) {
                        $q->orWhere(function ($subQ) use ($penugasan) {
                            $subQ->where('kecamatan_id', $penugasan->kecamatan_id)
                                 ->where('desa_id', $penugasan->desa_id);
                            
                            if ($penugasan->rw) {
                                $subQ->where('rw', $penugasan->rw);
                            }
                        });
                    }
                });
            });

        $tagihans = $tagihanQuery->get();

        return response()->json([
            'success' => true,
            'data' => $tagihans
        ]);
    }
}
