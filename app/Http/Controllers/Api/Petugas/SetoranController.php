<?php

namespace App\Http\Controllers\Api\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Setoran;
use App\Models\SetoranDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SetoranController extends Controller
{
    public function rekap(Request $request)
    {
        $user = $request->user();
        
        // Ambil pembayaran tunai hari ini yang belum disetor
        $pembayarans = Pembayaran::with('tagihan')
            ->where('user_id', $user->id)
            ->where('metode_pembayaran', 'tunai')
            ->where('is_setor', false)
            ->whereDate('waktu_bayar', Carbon::today())
            ->get();

        $totalUang = $pembayarans->sum('nominal_bayar');

        return response()->json([
            'success' => true,
            'data' => [
                'total_uang' => $totalUang,
                'jumlah_transaksi' => $pembayarans->count(),
                'rincian' => $pembayarans
            ]
        ]);
    }

    public function submit(Request $request)
    {
        $user = $request->user();

        try {
            DB::beginTransaction();

            $pembayarans = Pembayaran::where('user_id', $user->id)
                ->where('metode_pembayaran', 'tunai')
                ->where('is_setor', false)
                ->whereDate('waktu_bayar', Carbon::today())
                ->get();

            if ($pembayarans->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada setoran untuk disubmit hari ini.'
                ], 400);
            }

            $totalUang = $pembayarans->sum('nominal_bayar');

            // Generate nomor referensi
            $noReferensi = 'SET-' . date('Ymd') . '-' . rand(1000, 9999);

            $setoran = Setoran::create([
                'nomor_referensi' => $noReferensi,
                'petugas_id' => $user->id,
                // 'bendahara_id' => null, // diisi nanti oleh bendahara
                'tanggal_setor' => now(),
                'total_nominal' => $totalUang,
                'status' => 'pending',
                'metode_setoran' => 'tunai', // atau transfer
            ]);

            foreach ($pembayarans as $p) {
                SetoranDetail::create([
                    'setoran_id' => $setoran->id,
                    'pembayaran_id' => $p->id,
                    'nominal' => $p->nominal_bayar,
                ]);

                // Update status verifikasi pembayaran
                $p->is_setor = true;
                $p->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Setoran berhasil dikirim.',
                'data' => $setoran
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses setoran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
