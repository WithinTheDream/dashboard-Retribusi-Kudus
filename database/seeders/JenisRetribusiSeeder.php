<?php

namespace Database\Seeders;

use App\Models\JenisRetribusi;
use Illuminate\Database\Seeder;

class JenisRetribusiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode' => 'RT',
                'nama' => 'Rumah Tinggal',
            ],
            [
                'kode' => 'TOKO',
                'nama' => 'Toko',
            ],
            [
                'kode' => 'RESTORAN',
                'nama' => 'Restoran',
            ],
            [
                'kode' => 'HOTEL',
                'nama' => 'Hotel/Penginapan',
            ],
            [
                'kode' => 'PASAR',
                'nama' => 'Pasar',
            ],
            [
                'kode' => 'INDUSTRI',
                'nama' => 'Industri/Perusahaan',
            ],
        ];

        foreach ($data as $item) {
            JenisRetribusi::create($item);
        }
    }
}