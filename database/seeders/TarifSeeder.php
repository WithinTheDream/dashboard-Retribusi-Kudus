<?php

namespace Database\Seeders;

use App\Models\JenisRetribusi;
use App\Models\Tarif;
use Illuminate\Database\Seeder;

class TarifSeeder extends Seeder
{
    public function run(): void
    {
        $rumahTinggal = JenisRetribusi::where('kode', 'RT')->first();

        if ($rumahTinggal) {
            Tarif::create([
                'jenis_retribusi_id' => $rumahTinggal->id,
                'nominal' => 15000,
                'satuan' => 'bulan',
                'periode' => 2026,
                'is_aktif' => true,
            ]);
        }
    }
}