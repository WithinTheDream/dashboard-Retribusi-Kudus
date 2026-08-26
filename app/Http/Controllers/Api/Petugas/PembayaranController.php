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
        $validated = $request->validate([
            'tagihan_id'        => ['required', 'exists:tagihans,id'],
            'metode_pembayaran' => ['nullable', 'in:tunai,qris'],
            'lat'               => ['nullable', 'string'],
            'long'              => ['nullable', 'string'],
        ]);

        $user = $request->user();

        try {
            DB::beginTransaction();

            $tagihan = Tagihan::findOrFail($validated['tagihan_id']);

            if ($tagihan->status === 'lunas') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tagihan ini sudah berstatus lunas.'
                ], 400);
            }

            // Ubah status tagihan menjadi lunas
            $tagihan->status = 'lunas';
            $tagihan->save();

            // Generate nomor bukti pembayaran (Contoh: BYR-20260826-0001)
            $countToday = Pembayaran::whereDate('created_at', today())->count() + 1;
            $noPembayaran = 'BYR-' . date('Ymd') . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

            // Catat pembayaran dengan koordinat GPS
            $pembayaran = Pembayaran::create([
                'nomor_pembayaran'  => $noPembayaran,
                'tagihan_id'        => $tagihan->id,
                'user_id'           => $user->id,
                'nominal_bayar'     => $tagihan->nominal,
                'metode_pembayaran' => $validated['metode_pembayaran'] ?? 'tunai',
                'waktu_bayar'       => now(),
                'lat'               => $validated['lat'] ?? null,
                'long'              => $validated['long'] ?? null,
                'status_sync'       => true,
                'is_setor'          => false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dicatat, status tagihan lunas.',
                'data'    => $pembayaran->load(['tagihan.wajibRetribusi'])
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
