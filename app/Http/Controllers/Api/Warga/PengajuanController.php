<?php

namespace App\Http\Controllers\Api\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanWajibRetribusi;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:16',
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            // kecamatan_id, desa_id, rt, rw sementara dihardcode atau dibuat opsional untuk demo
        ]);

        $user = $request->user();

        // Cek apakah sudah ada pengajuan yang belum selesai
        $existing = PengajuanWajibRetribusi::where('user_id', $user->id)
            ->whereIn('status_pengajuan', ['menunggu', 'perbaikan', 'survey'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda masih memiliki pengajuan aktif yang sedang diproses.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $noPengajuan = 'REQ-' . date('Ymd') . '-' . rand(1000, 9999);

            $pengajuan = PengajuanWajibRetribusi::create([
                'nomor_pengajuan' => $noPengajuan,
                'user_id' => $user->id,
                'jenis_retribusi_id' => 1, // Default sementara (karena foreign key restricted)
                'nik' => $request->nik,
                'nama_lengkap' => $request->nama_lengkap,
                'nama_usaha' => $request->nama_usaha ?? null,
                'kecamatan_id' => 1, // Default kecamatan
                'desa_id' => 1, // Default desa
                'alamat' => $request->alamat,
                'rt' => '001',
                'rw' => '001',
                'lat' => $request->lat ?? null,
                'lokasi_long' => $request->lokasi_long ?? null,
                'no_hp' => $user->no_hp ?? '08123456789',
                'status_pengajuan' => 'menunggu',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim. Menunggu verifikasi admin.',
                'data' => $pengajuan
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pengajuan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function status(Request $request)
    {
        $user = $request->user();
        
        $pengajuans = PengajuanWajibRetribusi::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pengajuans
        ]);
    }
}
