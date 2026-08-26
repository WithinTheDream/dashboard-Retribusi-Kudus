<?php

namespace App\Http\Controllers\Api\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanWajibRetribusi;
use App\Models\DokumenPengajuan;
use App\Models\HistoriPengajuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'                => ['required', 'string', 'size:16'],
            'nama_lengkap'       => ['required', 'string', 'max:255'],
            'nama_usaha'         => ['nullable', 'string', 'max:255'],
            'jenis_retribusi_id' => ['required', 'exists:jenis_retribusis,id'],
            'kecamatan_id'       => ['required', 'exists:kecamatans,id'],
            'desa_id'            => ['required', 'exists:desas,id'],
            'alamat'             => ['required', 'string'],
            'rt'                 => ['required', 'string', 'max:3'],
            'rw'                 => ['required', 'string', 'max:3'],
            'lat'                => ['nullable', 'string'],
            'lokasi_long'        => ['nullable', 'string'],
            'no_hp'              => ['nullable', 'string', 'max:20'],
            'dokumen'            => ['nullable', 'array'],
            'dokumen.*'          => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $user = $request->user();

        // Cek apakah ada pengajuan aktif yang belum diproses
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

            $countToday = PengajuanWajibRetribusi::whereDate('created_at', today())->count() + 1;
            $noPengajuan = 'REG-' . date('Ymd') . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

            $pengajuan = PengajuanWajibRetribusi::create([
                'nomor_pengajuan'    => $noPengajuan,
                'user_id'            => $user->id,
                'jenis_retribusi_id' => $validated['jenis_retribusi_id'],
                'nik'                => $validated['nik'],
                'nama_lengkap'       => $validated['nama_lengkap'],
                'nama_usaha'         => $validated['nama_usaha'] ?? null,
                'kecamatan_id'       => $validated['kecamatan_id'],
                'desa_id'            => $validated['desa_id'],
                'alamat'             => $validated['alamat'],
                'rt'                 => $validated['rt'],
                'rw'                 => $validated['rw'],
                'lat'                => $validated['lat'] ?? null,
                'lokasi_long'        => $validated['lokasi_long'] ?? null,
                'no_hp'              => $validated['no_hp'] ?? $user->no_hp ?? '',
                'status_pengajuan'   => 'menunggu',
            ]);

            // Handle upload dokumen (KTP, NIB, foto lokasi, dsb)
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $jenis => $file) {
                    $path = $file->store('dokumen_pengajuan', 'public');
                    DokumenPengajuan::create([
                        'pengajuan_id'  => $pengajuan->id,
                        'jenis_dokumen' => is_string($jenis) ? $jenis : 'LAMPIRAN',
                        'file_path'     => Storage::url($path),
                    ]);
                }
            }

            // Catat log histori pengajuan
            HistoriPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'status'       => 'menunggu',
                'catatan'      => 'Pengajuan baru dikirim oleh Warga',
                'user_id'      => $user->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim. Menunggu verifikasi admin.',
                'data'    => $pengajuan->load(['kecamatan', 'desa', 'jenisRetribusi', 'dokumen'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pengajuan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function status(Request $request)
    {
        $user = $request->user();
        
        $pengajuans = PengajuanWajibRetribusi::with(['jenisRetribusi', 'kecamatan', 'desa', 'dokumen', 'histori'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $pengajuans
        ]);
    }
}
