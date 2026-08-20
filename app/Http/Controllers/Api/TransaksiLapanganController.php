<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\KunjunganPenagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiLapanganController extends Controller
{
    // 1. Mengambil daftar tagihan yang belum dibayar (untuk ditarik ke Aplikasi Android Petugas)
    public function getTagihan(Request $request)
    {
        $query = Tagihan::with(['wajibRetribusi.kecamatan', 'wajibRetribusi.desa', 'wajibRetribusi.jenisRetribusi'])
                        ->where('status', 'belum_bayar');

        // Jika petugas memfilter berdasarkan wilayah atau ID tertentu
        if ($request->has('wajib_retribusi_id')) {
            $query->where('wajib_retribusi_id', $request->wajib_retribusi_id);
        }

        $tagihans = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'List tagihan siap tagih',
            'data' => $tagihans
        ], 200);
    }

    // 2. Mencatat Kunjungan Lapangan (Rumah kosong, warga menolak, belum bisa bayar, dll)
    public function storeKunjungan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // ID Petugas
            'wajib_retribusi_id' => 'required|exists:wajib_retribusis,id',
            'hasil_kunjungan' => 'required|string', // bayar, rumah_kosong, menolak, belum_bisa_bayar
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $kunjungan = KunjunganPenagihan::create([
            'user_id' => $request->user_id,
            'wajib_retribusi_id' => $request->wajib_retribusi_id,
            'waktu_kunjungan' => now(),
            'hasil_kunjungan' => $request->hasil_kunjungan,
            'catatan' => $request->catatan,
            'lat' => $request->lat,
            'long' => $request->long,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Log kunjungan lapangan berhasil dicatat',
            'data' => $kunjungan
        ], 201);
    }

    // 3. Memproses Pembayaran dari Warga (Tunai / QRIS + Audit GPS + Status Offline Sync)
    public function storePembayaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tagihan_id' => 'required|exists:tagihans,id',
            'user_id' => 'required|exists:users,id', // Petugas penerima
            'nominal_bayar' => 'required|numeric',
            'metode_pembayaran' => 'required|in:tunai,qris',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Cek apakah tagihan sudah lunas sebelumnya
            $tagihan = Tagihan::findOrFail($request->tagihan_id);
            if ($tagihan->status === 'lunas') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tagihan ini sudah lunas sebelumnya.'
                ], 400);
            }

            // Generate Nomor Pembayaran (Contoh: BYR-20260820-0001)
            $tanggal = date('Ymd');
            $count = Pembayaran::whereDate('created_at', today())->count() + 1;
            $nomorPembayaran = 'BYR-' . $tanggal . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            // Simpan Pembayaran
            $pembayaran = Pembayaran::create([
                'nomor_pembayaran' => $nomorPembayaran,
                'tagihan_id' => $request->tagihan_id,
                'user_id' => $request->user_id,
                'nominal_bayar' => $request->nominal_bayar,
                'metode_pembayaran' => $request->metode_pembayaran,
                'waktu_bayar' => now(),
                'lat' => $request->lat,
                'long' => $request->long,
                'status_sync' => true, // Set true jika langsung masuk server
                'is_setor' => false,   // Belum disetor ke bendahara
            ]);

            // Ubah status tagihan menjadi lunas
            $tagihan->update([
                'status' => 'lunas'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dicatat dan tagihan telah lunas.',
                'data' => $pembayaran
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
