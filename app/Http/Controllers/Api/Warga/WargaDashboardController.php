<?php

namespace App\Http\Controllers\Api\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WajibRetribusi;
use App\Models\Tagihan;

class WargaDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $wajibRetribusi = WajibRetribusi::where('user_id', $user->id)->first();

        if (!$wajibRetribusi) {
            return response()->json([
                'success' => true,
                'data' => [
                    'profil' => null,
                    'sisa_tagihan' => [],
                    'total_sisa_tagihan' => 0,
                    'riwayat_pembayaran' => [],
                ]
            ]);
        }

        // Tagihan belum bayar (Sisa Tagihan)
        $sisaTagihan = Tagihan::where('wajib_retribusi_id', $wajibRetribusi->id)
            ->where('status', 'belum_bayar')
            ->get();

        // Riwayat Pembayaran (Tagihan lunas)
        $riwayatPembayaran = Tagihan::with('pembayaran')
            ->where('wajib_retribusi_id', $wajibRetribusi->id)
            ->where('status', 'lunas')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'profil' => $wajibRetribusi,
                'sisa_tagihan' => $sisaTagihan,
                'total_sisa_tagihan' => $sisaTagihan->sum('nominal'),
                'riwayat_pembayaran' => $riwayatPembayaran,
            ]
        ]);
    }
}
