<?php

namespace App\Http\Controllers\Api\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function tunai(Request $request)
    {
        $request->validate([
            'tagihan_id' => 'required|exists:tagihans,id',
        ]);

        $user = $request->user();

        try {
            DB::beginTransaction();

            $tagihan = Tagihan::findOrFail($request->tagihan_id);

            if ($tagihan->status === 'lunas') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tagihan ini sudah lunas.'
                ], 400);
            }

            // Ubah status tagihan
            $tagihan->status = 'lunas';
            $tagihan->save();

            // Generate nomor referensi pembayaran (misal: INV-YYYYMMDD-RANDOM)
            $noPembayaran = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

            // Catat pembayaran
            $pembayaran = Pembayaran::create([
                'nomor_pembayaran' => $noPembayaran,
                'tagihan_id' => $tagihan->id,
                'user_id' => $user->id,
                'nominal_bayar' => $tagihan->nominal,
                'metode_pembayaran' => 'tunai',
                'waktu_bayar' => now(),
                'status_sync' => false,
                'is_setor' => false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil, status tagihan lunas.',
                'data' => $pembayaran
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
