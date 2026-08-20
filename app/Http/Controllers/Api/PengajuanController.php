<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengajuanWajibRetribusi;
use App\Models\DokumenPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PengajuanController extends Controller
{
    // 1. Menampilkan daftar pengajuan (untuk Admin atau riwayat Warga)
    public function index(Request $request)
    {
        $query = PengajuanWajibRetribusi::with(['jenisRetribusi', 'kecamatan', 'desa', 'dokumen']);

        // Jika diakses oleh warga, filter berdasarkan user_id mereka sendiri
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $data = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List data pengajuan retribusi',
            'data' => $data
        ], 200);
    }

    // 2. Menyimpan pengajuan baru dari warga
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'jenis_retribusi_id' => 'required|exists:jenis_retribusis,id',
            'nik' => 'required|string|max:16',
            'nama_lengkap' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'desa_id' => 'required|exists:desas,id',
            'alamat' => 'required|string',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'no_hp' => 'required|string',
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
            // Generate nomor pengajuan otomatis (Contoh: REG-202608-0001)
            $tanggal = date('Ymd');
            $count = PengajuanWajibRetribusi::whereDate('created_at', today())->count() + 1;
            $nomorPengajuan = 'REG-' . $tanggal . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $pengajuan = PengajuanWajibRetribusi::create([
                'nomor_pengajuan' => $nomorPengajuan,
                'user_id' => $request->user_id,
                'jenis_retribusi_id' => $request->jenis_retribusi_id,
                'nik' => $request->nik,
                'nama_lengkap' => $request->nama_lengkap,
                'nama_usaha' => $request->nama_usaha,
                'kecamatan_id' => $request->kecamatan_id,
                'desa_id' => $request->desa_id,
                'alamat' => $request->alamat,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'lokasi_long' => $request->lokasi_long,
                'lat' => $request->lat,
                'no_hp' => $request->no_hp,
                'status_pengajuan' => 'menunggu',
            ]);

            // Handle Upload Dokumen jika ada (KTP, NIB, dll)
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $jenis => $file) {
                    $path = $file->store('public/dokumen_pengajuan');
                    $url = str_replace('public/', 'storage/', $path);

                    DokumenPengajuan::create([
                        'pengajuan_id' => $pengajuan->id,
                        'jenis_dokumen' => $jenis, // Misal: KTP / NIB
                        'file_path' => $url,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim dan sedang menunggu verifikasi admin.',
                'data' => $pengajuan->load('dokumen')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
