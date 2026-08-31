<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'judul' => 'Bayar Retribusi Tepat Waktu',
                'deskripsi' => 'Dukung kebersihan Kabupaten Kudus dengan membayar retribusi sampah tepat waktu setiap bulan.',
                'gambar' => 'banners/banner1.jpg',
                'urutan' => 1,
                'is_active' => true,
            ],
            [
                'judul' => 'Kudus Bersih & Hijau',
                'deskripsi' => 'Pilah sampah dari rumah untuk lingkungan yang lebih sehat dan asri bagi generasi mendatang.',
                'gambar' => 'banners/banner2.jpg',
                'urutan' => 2,
                'is_active' => true,
            ],
            [
                'judul' => 'Kemudahan Pembayaran Digital',
                'deskripsi' => 'Kini pembayaran retribusi sampah dapat dilakukan langsung lewat petugas atau QRIS.',
                'gambar' => 'banners/banner3.jpg',
                'urutan' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $b) {
            Banner::updateOrCreate(['judul' => $b['judul']], $b);
        }
    }
}
