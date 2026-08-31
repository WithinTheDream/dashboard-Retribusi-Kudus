<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerApiController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->ordered()->get()->map(function ($banner) {
            return [
                'id' => $banner->id,
                'judul' => $banner->judul,
                'deskripsi' => $banner->deskripsi,
                'gambar' => $banner->gambar,
                'gambar_url' => $banner->gambar_url,
                'urutan' => $banner->urutan,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $banners
        ]);
    }
}
