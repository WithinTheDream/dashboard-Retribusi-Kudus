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
        
        // Ambil pembayaran tunai yang belum disetor oleh petugas ini
        $pembayarans = Pembayaran::with('tagihan.wajibRetribusi')
            ->where('user_id', $user->id)
            ->where('metode_pembayaran', 'tunai')
            ->where('is_setor', false)
            ->get();

        $totalUang = $pembayarans->sum('nominal_bayar');

        return response()->json([
            'success' => true,
            'data' => [
                'total_uang'       => $totalUang,
                'jumlah_transaksi' => $pembayarans->count(),
                'rincian'          => $pembayarans
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
                ->get();

            if ($pembayarans->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada penerimaan pembayaran tunai yang perlu disetorkan saat ini.'
                ], 400);
            }

            $totalUang = $pembayarans->sum('nominal_bayar');

            // Generate nomor setoran (Contoh: SET-20260826-0001)
            $countToday = Setoran::whereDate('created_at', today())->count() + 1;
            $nomorSetoran = 'SET-' . date('Ymd') . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

            $setoran = Setoran::create([
                'nomor_setoran'  => $nomorSetoran,
                'user_id'        => $user->id,
                'tanggal_setor'  => now()->toDateString(),
                'total_setoran'  => $totalUang,
                'status_setoran' => 'menunggu',
                'catatan'        => $request->catatan ?? null,
            ]);

            foreach ($pembayarans as $p) {
                SetoranDetail::create([
                    'setoran_id'    => $setoran->id,
                    'pembayaran_id' => $p->id,
                ]);

                // Tandai pembayaran sudah masuk dalam bundle setoran
                $p->is_setor = true;
                $p->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Setoran berhasil disubmit ke bendahara.',
                'data'    => $setoran->load('details.pembayaran.tagihan.wajibRetribusi')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses setoran.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
