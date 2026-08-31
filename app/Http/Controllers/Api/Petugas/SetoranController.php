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

            // Generate nomor setoran unik
            $noSetoran = 'SET-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $setoran = Setoran::create([
                'nomor_setoran'   => $noSetoran,
                'user_id'         => $user->id,
                'tanggal_setor'   => now()->toDateString(),
                'total_setoran'   => $totalUang,
                'status_setoran'  => 'menunggu',
            ]);

            foreach ($pembayarans as $p) {
                SetoranDetail::create([
                    'setoran_id'    => $setoran->id,
                    'pembayaran_id' => $p->id,
                ]);

                // Update status pembayaran menjadi sudah disetor
                $p->is_setor = true;
                $p->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Setoran berhasil dikirim ke bendahara.',
                'data' => $setoran
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses setoran.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
