<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class DesaSeeder extends Seeder
{
    public function run(): void
    {
        $kudus = Kecamatan::where('kecamatan', 'Kudus')->first();
        $jati = Kecamatan::where('kecamatan', 'Jati')->first();
        $bae = Kecamatan::where('kecamatan', 'Bae')->first();

        $data = [
            [
                'kec_id' => $kudus->id,
                'desa' => 'Janggalan',
            ],
            [
                'kec_id' => $kudus->id,
                'desa' => 'Demangan',
            ],
            [
                'kec_id' => $kudus->id,
                'desa' => 'Mlati Lor',
            ],

            [
                'kec_id' => $jati->id,
                'desa' => 'Getaspejaten',
            ],
            [
                'kec_id' => $jati->id,
                'desa' => 'Jati Wetan',
            ],

            [
                'kec_id' => $bae->id,
                'desa' => 'Bacin',
            ],
            [
                'kec_id' => $bae->id,
                'desa' => 'Gondangmanis',
            ],
        ];

        foreach ($data as $item) {
            Desa::create($item);
        }
    }
}