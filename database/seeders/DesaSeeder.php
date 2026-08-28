<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class DesaSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatans = Kecamatan::pluck('id', 'kecamatan')->toArray();

        $desasByKecamatan = [
            'Bae' => [
                'Bacin', 'Gondangmanis', 'Bae', 'Dersalam', 'Karangbener',
                'Ngembalrejo', 'Panjang', 'Pedawang', 'Peganjaran', 'Purworejo'
            ],
            'Dawe' => [
                'Cendono', 'Colo', 'Cranggang', 'Dukuhwaringin', 'Glagah Kulon',
                'Japan', 'Kajar', 'Kandangmas', 'Kuwukan', 'Lau',
                'Margorejo', 'Piji', 'Puyoh', 'Rejosari', 'Samirejo',
                'Soco', 'Tergo', 'Ternadi'
            ],
            'Gebog' => [
                'Besito', 'Getasrabi', 'Gondosari', 'Gribig', 'Jurang',
                'Karangmalang', 'Kedungsari', 'Klumpit', 'Menawan', 'Padurenan', 'Rahtawu'
            ],
            'Jati' => [
                'Getaspejaten', 'Jati Wetan', 'Jati Kulon', 'Jepangpakis', 'Jetiskapuan',
                'Loram Kulon', 'Loram Wetan', 'Megawon', 'Ngembal Kulon', 'Pasuruhan Kidul',
                'Pasuruhan Lor', 'Ploso', 'Tanjungkarang', 'Tumpangkrasak'
            ],
            'Jekulo' => [
                'Bulung Kulon', 'Bulungcangkring', 'Gondoharum', 'Hadipolo', 'Honggosoco',
                'Jekulo', 'Klaling', 'Pladen', 'Sadang', 'Sidomulyo',
                'Tanjungrejo', 'Terban'
            ],
            'Kaliwungu' => [
                'Bakalankrapyak', 'Banget', 'Blimbing Kidul', 'Gamong', 'Garung Kidul',
                'Garung Lor', 'Kaliwungu', 'Karangampel', 'Kedungdowo', 'Mijen',
                'Papringan', 'Prambatan Kidul', 'Prambatan Lor', 'Setrokalangan', 'Sidorekso'
            ],
            'Kudus' => [
                'Janggalan', 'Demangan', 'Mlati Lor', 'Barongan', 'Burikan',
                'Damaran', 'Demaan', 'Glantengan', 'Kaliputu', 'Kauman',
                'Krandon', 'Langgardalem', 'Nganguk', 'Rendeng', 'Singocandi',
                'Kramat', 'Kajeksan', 'Kerjasan', 'Mlati Kidul', 'Mlati Norowito',
                'Panjunan', 'Purwosari', 'Sunggingan', 'Wergu Kulon', 'Wergu Wetan'
            ],
            'Mejobo' => [
                'Golantepus', 'Gulang', 'Hadiwarno', 'Jepang', 'Jojo',
                'Kesambi', 'Kirig', 'Mejobo', 'Payaman', 'Temulus', 'Tenggeles'
            ],
            'Undaan' => [
                'Glagahwaru', 'Kalirejo', 'Karangrowo', 'Kutuk', 'Lambangan',
                'Larikrejo', 'Medini', 'Ngemplak', 'Sambung', 'Terangmas',
                'Undaan Kidul', 'Undaan Lor', 'Undaan Tengah', 'Wates', 'Wonosoco', 'Berugenjang'
            ],
        ];

        foreach ($desasByKecamatan as $kecamatanNama => $desas) {
            $kecId = $kecamatans[$kecamatanNama] ?? null;
            if (!$kecId) {
                continue;
            }

            foreach ($desas as $desaNama) {
                Desa::firstOrCreate(
                    [
                        'kec_id' => $kecId,
                        'desa' => $desaNama,
                    ]
                );
            }
        }
    }
}