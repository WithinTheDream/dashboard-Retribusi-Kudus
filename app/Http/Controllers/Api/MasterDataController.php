<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisRetribusi;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function jenisRetribusi()
    {
        $data = JenisRetribusi::all();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function kecamatan()
    {
        $data = Kecamatan::all();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function desa($kecamatanId)
    {
        $data = Desa::where('kec_id', $kecamatanId)->get();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
