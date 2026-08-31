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

        $userId = $request->user()?->id ?? $request->user_id;
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $data = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List data pengajuan retribusi',
            'data' => $data
        ], 200);
    }

    // 2. Status pengajuan warga (endpoint khusus warga)
    public function status(Request $request)
    {
        $userId = $request->user()?->id ?? $request->user_id;

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi'
            ], 401);
        }

        $data = PengajuanWajibRetribusi::with(['jenisRetribusi', 'kecamatan', 'desa', 'dokumen'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    // 3. Menyimpan pengajuan baru dari warga
    public function store(Request $request)
    {
        $userId = $request->user()?->id ?? $request->user_id;

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi'
            ], 401);
        }

        if ($request->filled('koordinat') && (!$request->filled('lat') || !$request->filled('lokasi_long'))) {
            $parts = preg_split('/[,\s]+/', trim($request->input('koordinat')));
            if (count($parts) >= 2) {
                $request->merge([
                    'lat' => $parts[0],
                    'lokasi_long' => $parts[1],
                ]);
            }
        }

        $validator = Validator::make($request->all(), [
            'jenis_retribusi_id' => 'required|exists:jenis_retribusis,id',
            'nik' => 'required|string|size:16',
            'nama_lengkap' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'desa_id' => 'required|exists:desas,id',
            'alamat' => 'required|string',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'no_hp' => 'required|string',
            'lat' => 'required|numeric',
            'lokasi_long' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek apakah masih ada pengajuan aktif
        $existing = PengajuanWajibRetribusi::where('user_id', $userId)
            ->whereIn('status_pengajuan', ['menunggu', 'perbaikan', 'survey'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda masih memiliki pengajuan aktif yang sedang diproses.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $tanggal = date('Ymd');
            $count = PengajuanWajibRetribusi::whereDate('created_at', today())->count() + 1;
            $nomorPengajuan = 'REG-' . $tanggal . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $pengajuan = PengajuanWajibRetribusi::create([
                'nomor_pengajuan' => $nomorPengajuan,
                'user_id' => $userId,
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
