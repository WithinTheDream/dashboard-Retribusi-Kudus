<?php

namespace App\Http\Controllers\Api\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KunjunganPenagihan;

class KunjunganController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wajib_retribusi_id' => ['required', 'exists:wajib_retribusis,id'],
            'hasil_kunjungan'    => ['required', 'string', 'max:255'], // contoh: bayar, rumah_kosong, menolak, belum_bisa_bayar
            'catatan'            => ['nullable', 'string'],
            'lat'                => ['nullable', 'string'],
            'long'               => ['nullable', 'string'],
        ]);

        $user = $request->user();

        $kunjungan = KunjunganPenagihan::create([
            'user_id'            => $user->id,
            'wajib_retribusi_id' => $validated['wajib_retribusi_id'],
            'waktu_kunjungan'    => now(),
            'hasil_kunjungan'    => $validated['hasil_kunjungan'],
            'catatan'            => $validated['catatan'] ?? null,
            'lat'                => $validated['lat'] ?? null,
            'long'               => $validated['long'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Log kunjungan lapangan berhasil dicatat.',
            'data'    => $kunjungan->load('wajibRetribusi')
        ], 201);
    }
}

