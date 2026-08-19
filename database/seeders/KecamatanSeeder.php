<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Bae',
            'Dawe',
            'Gebog',
            'Jati',
            'Jekulo',
            'Kaliwungu',
            'Kudus',
            'Mejobo',
            'Undaan',
        ];

        foreach ($data as $nama) {
            Kecamatan::create([
                'kecamatan' => $nama,
            ]);
        }
    }
}