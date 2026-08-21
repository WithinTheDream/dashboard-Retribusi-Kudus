<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WajibRetribusi;
use App\Models\Tagihan;
use Carbon\Carbon;

class GenerateTagihanBulanan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagihan:generate {--month=} {--year=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tagihan bulanan untuk semua wajib retribusi yang aktif';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bulan = $this->option('month') ?? Carbon::now()->month;
        $tahun = $this->option('year') ?? Carbon::now()->year;

        $this->info("Memulai proses generate tagihan untuk Bulan: {$bulan}, Tahun: {$tahun}");

        // Ambil wajib retribusi yang aktif
        $wajibRetribusis = WajibRetribusi::with('jenisRetribusi.tarifs')->where('status_aktif', true)->get();

        if ($wajibRetribusis->isEmpty()) {
            $this->warn("Tidak ada data wajib retribusi yang aktif.");
            return;
        }

        $countCreated = 0;
        $countSkipped = 0;

        foreach ($wajibRetribusis as $wr) {
            // Cek apakah tagihan untuk bulan dan tahun ini sudah ada
            $exists = Tagihan::where('wajib_retribusi_id', $wr->id)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->exists();

            if ($exists) {
                $countSkipped++;
                continue;
            }

            // Dapatkan nominal tarif yang aktif saat ini (Asumsikan tarif pertama atau terbaru)
            // Di sistem nyata mungkin ada logic tanggal_mulai/tanggal_selesai
            $tarif = $wr->jenisRetribusi->tarifs->first();
            
            if (!$tarif) {
                $this->error("Wajib Retribusi ID {$wr->id} ({$wr->nama_lengkap}) tidak memiliki tarif valid pada jenis retribusinya. Melewati...");
                continue;
            }

            // Generate nomor tagihan unik
            $nomorTagihan = 'INV-' . $tahun . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($wr->id, 6, '0', STR_PAD_LEFT);

            Tagihan::create([
                'nomor_tagihan' => $nomorTagihan,
                'wajib_retribusi_id' => $wr->id,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'nominal' => $tarif->nominal,
                'status' => 'belum_bayar',
            ]);

            $countCreated++;
        }

        $this->info("Proses selesai.");
        $this->info("Tagihan berhasil dibuat: {$countCreated}");
        $this->info("Tagihan dilewati (sudah ada): {$countSkipped}");
    }
}
