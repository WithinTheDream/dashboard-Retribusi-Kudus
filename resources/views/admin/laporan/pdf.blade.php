<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Retribusi - {{ $namaBulan[$bulan] ?? $bulan }} {{ $tahun }}</title>
    <style>
        @page {
            margin: 20px 25px 25px 25px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
        }

        /* HEADER KOP SURAT */
        .kop-header {
            text-align: center;
            border-bottom: 2.5px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .kop-header h4 {
            font-size: 11px;
            font-weight: 600;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #334155;
        }
        .kop-header h2 {
            font-size: 14px;
            font-weight: 800;
            margin: 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #0f172a;
        }
        .kop-header p {
            font-size: 9.5px;
            margin: 0;
            color: #64748b;
        }

        .title-doc {
            text-align: center;
            margin-bottom: 15px;
        }
        .title-doc h3 {
            font-size: 13px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            text-decoration: underline;
            color: #0f172a;
        }
        .title-doc span {
            font-size: 10px;
            color: #475569;
            font-weight: 600;
        }

        /* SUMMARY BOXES */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .summary-box {
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            border-radius: 4px;
        }
        .summary-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }
        .summary-val {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 2px;
        }

        /* DATA TABLE */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background: #0f172a;
            color: #ffffff;
            font-size: 9.5px;
            font-weight: 700;
            padding: 6px 8px;
            text-align: left;
            text-transform: uppercase;
            border: 1px solid #0f172a;
        }
        .data-table td {
            padding: 5px 8px;
            font-size: 9.5px;
            border: 1px solid #cbd5e1;
            color: #334155;
        }
        .data-table tr:nth-child(even) {
            background: #f8fafc;
        }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .font-bold { font-weight: bold !important; }

        /* SIGNATURE SECTION */
        .signature-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }
        .signature-box {
            width: 45%;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop-header">
        <h4>Pemerintah Kabupaten Kudus</h4>
        <h2>Dinas Perumahan, Kawasan Permukiman dan Lingkungan Hidup</h2>
        <p>Jl. Mejobo No. 45 Kudus, Jawa Tengah • Telp. (0291) 438157 • Email: pkplh@kuduskab.go.id</p>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="title-doc">
        <h3>Laporan Rekapitulasi Pembayaran Retribusi Persampahan</h3>
        <span>Periode: {{ $namaBulan[$bulan] ?? $bulan }} {{ $tahun }}</span>
    </div>

    <!-- REKAPITULASI SUMMARY -->
    <table class="summary-table">
        <tr>
            <td style="width: 25%; padding-right: 5px;">
                <div class="summary-box">
                    <div class="summary-label">Total Tagihan</div>
                    <div class="summary-val">{{ number_format($rekap['total_tagihan'], 0, ',', '.') }} Tagihan</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 5px;">
                <div class="summary-box">
                    <div class="summary-label">Tagihan Lunas</div>
                    <div class="summary-val" style="color: #16a34a;">{{ number_format($rekap['total_tagihan_lunas'], 0, ',', '.') }} Lunas</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 5px;">
                <div class="summary-box">
                    <div class="summary-label">Total Pembayaran</div>
                    <div class="summary-val">{{ number_format($rekap['total_pembayaran'], 0, ',', '.') }} Transaksi</div>
                </div>
            </td>
            <td style="width: 25%; padding-left: 5px;">
                <div class="summary-box" style="background: #ecfdf5; border-color: #a7f3d0;">
                    <div class="summary-label" style="color: #047857;">Total Retribusi Masuk</div>
                    <div class="summary-val" style="color: #047857;">Rp {{ number_format($rekap['total_nominal_bayar'], 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- TABEL TRANSAKSI -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 24px;">No</th>
                <th style="width: 95px;">No. Pembayaran</th>
                <th style="width: 85px;">No. Tagihan</th>
                <th>Nama Wajib Retribusi</th>
                <th>Wilayah</th>
                <th class="text-right" style="width: 75px;">Nominal (Rp)</th>
                <th class="text-center" style="width: 55px;">Metode</th>
                <th class="text-center" style="width: 75px;">Tgl Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembayarans as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $item->nomor_pembayaran }}</td>
                    <td>{{ $item->tagihan->nomor_tagihan ?? '-' }}</td>
                    <td>{{ $item->tagihan->wajibRetribusi->nama_lengkap ?? '-' }}</td>
                    <td>
                        {{ $item->tagihan->wajibRetribusi->desa->desa ?? '-' }}, 
                        {{ $item->tagihan->wajibRetribusi->kecamatan->kecamatan ?? '-' }}
                    </td>
                    <td class="text-right font-bold" style="color: #047857;">
                        Rp {{ number_format($item->nominal_bayar, 0, ',', '.') }}
                    </td>
                    <td class="text-center" style="text-transform: capitalize;">{{ $item->metode_pembayaran }}</td>
                    <td class="text-center">
                        {{ $item->waktu_bayar ? \Carbon\Carbon::parse($item->waktu_bayar)->format('d/m/Y H:i') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 15px; color: #64748b;">
                        Tidak ada catatan pembayaran retribusi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background: #f1f5f9; font-weight: bold;">
                <td colspan="5" class="text-right" style="padding: 6px 8px;">TOTAL PENERIMAAN:</td>
                <td class="text-right font-bold" style="color: #047857;">
                    Rp {{ number_format($rekap['total_nominal_bayar'], 0, ',', '.') }}
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <!-- TANDA TANGAN -->
    <table class="signature-table">
        <tr>
            <td class="signature-box" style="vertical-align: top;">
                <p style="margin-bottom: 50px;">
                    Mengetahui,<br>
                    <strong>Kepala Dinas PKPLH Kab. Kudus</strong>
                </p>
                <p>
                    <strong><u>_________________________</u></strong><br>
                    NIP. ........................................
                </p>
            </td>
            <td style="width: 10%;"></td>
            <td class="signature-box" style="vertical-align: top;">
                <p style="margin-bottom: 50px;">
                    Kudus, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    <strong>Bendahara Penerimaan</strong>
                </p>
                <p>
                    <strong><u>{{ auth()->user()->nama_lengkap }}</u></strong><br>
                    NIP. ........................................
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
