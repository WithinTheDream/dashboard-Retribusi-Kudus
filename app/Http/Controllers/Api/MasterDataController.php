<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\JenisRetribusi;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function kecamatan()
    {
        $kecamatan = Kecamatan::with('desas')->get();

        return response()->json([
            'success' => true,
            'data' => $kecamatan,
        ]);
    }

    public function desaByKecamatan($kecamatanId)
    {
        $desas = Desa::where('kec_id', $kecamatanId)->get();

        return response()->json([
            'success' => true,
            'data' => $desas,
        ]);
    }

    public function jenisRetribusi()
    {
        $jenisRetribusi = JenisRetribusi::with(['tarifs' => function ($query) {
            $query->where('is_aktif', true);
        }])->get();

        return response()->json([
            'success' => true,
            'data' => $jenisRetribusi,
        ]);
    }
}

