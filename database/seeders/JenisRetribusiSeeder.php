<?php

namespace Database\Seeders;

use App\Models\JenisRetribusi;
use Illuminate\Database\Seeder;

class JenisRetribusiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => 'RT', 'nama' => 'Rumah Tinggal'],
            ['kode' => 'ML', 'nama' => 'Mal/Swalayan/Supermarket'],
            ['kode' => 'MM', 'nama' => 'Mini Market'],
            ['kode' => 'RUKO', 'nama' => 'Ruko/Rukan/Kantor'],
            ['kode' => 'TOKO', 'nama' => 'Toko'],
            ['kode' => 'GUDANG', 'nama' => 'Gudang'],
            ['kode' => 'BANK', 'nama' => 'Bank'],
            ['kode' => 'HOTEL', 'nama' => 'Hotel/Penginapan'],
            ['kode' => 'KOST', 'nama' => 'Rumah Kost'],
            ['kode' => 'RESTO', 'nama' => 'Restoran'],
            ['kode' => 'RS', 'nama' => 'Rumah Sakit'],
            ['kode' => 'PASAR', 'nama' => 'Pasar'],
            ['kode' => 'PKL', 'nama' => 'Pedagang'],
            ['kode' => 'PEND', 'nama' => 'Pendidikan'],
            ['kode' => 'TPS', 'nama' => 'Buang sendiri ke TPS/TPS3R/TPST'],
            ['kode' => 'TPA', 'nama' => 'Buang sendiri ke TPA'],
            ['kode' => 'KONT', 'nama' => 'Pengambilan Kontainer'],
            ['kode' => 'SEWA', 'nama' => 'Sewa Kontainer'],
        ];

        foreach ($data as $item) {
            JenisRetribusi::create($item);
        }
    }
}