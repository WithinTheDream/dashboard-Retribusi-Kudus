<?php

namespace App\Http\Controllers\Api\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function tunai(Request $request)
    {
        $request->validate([
            'tagihan_id' => 'required|exists:tagihans,id',
            'tanggal_bayar' => 'nullable|string',
        ]);

        $user = $request->user();

        try {
            DB::beginTransaction();

            $tagihan = Tagihan::findOrFail($request->tagihan_id);

            // Handle idempotency jika tagihan sudah lunas sebelumnya (misal dari sync sebelumnya)
            if ($tagihan->status === 'lunas') {
                $existing = Pembayaran::where('tagihan_id', $tagihan->id)->first();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Tagihan ini sudah lunas sebelumnya.',
                    'data' => $existing
                ], 200);
            }

            // Tentukan waktu bayar (prioritaskan timestamp dari offline device jika ada)
            $waktuBayar = now();
            $isSync = false;

            if ($request->filled('tanggal_bayar')) {
                try {
                    $waktuBayar = Carbon::parse($request->tanggal_bayar);
                    $isSync = true;
                } catch (\Exception $e) {
                    $waktuBayar = now();
                }
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
                'waktu_bayar' => $waktuBayar,
                'status_sync' => $isSync,
                'is_setor' => false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dicatat.',
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
