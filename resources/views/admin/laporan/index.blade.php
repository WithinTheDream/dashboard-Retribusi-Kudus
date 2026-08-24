@extends('layouts.admin')

@section('title', 'Laporan Rekap Retribusi - Kudus')
@section('page-title', 'Laporan Rekap Retribusi')

@section('content')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printable-area, #printable-area * {
            visibility: visible;
        }
        #printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 20px;
            box-shadow: none !important;
            border: none !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<div id="printable-area" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

    <!-- Header Form Filter & Tombol Cetak -->
    <div style="margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin: 0;">Rekap Tagihan & Pembayaran</h3>
            <button type="button" onclick="window.print()" class="no-print" style="background: #10b981; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                🖨️ Cetak Laporan
            </button>
        </div>

        <form method="GET" action="{{ route('admin.laporan.index') }}" class="no-print" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb;">
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #4b5563; margin-bottom: 4px;">Bulan</label>
                <select name="bulan" style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: white;">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ $m }} - {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #4b5563; margin-bottom: 4px;">Tahun</label>
                <input type="number" name="tahun" value="{{ $tahun }}" style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; width: 100px; background: white;">
            </div>

            <button type="submit" style="background: #2563eb; color: white; border: none; padding: 9px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">
                Tampilkan
            </button>
        </form>
    </div>

    @php
        $sisaTunggakan = max(0, $rekap['total_nominal_tagihan'] - $rekap['total_nominal_bayar']);
        $persenRealisasi = $rekap['total_nominal_tagihan'] > 0
            ? round(($rekap['total_nominal_bayar'] / $rekap['total_nominal_tagihan']) * 100, 1)
            : 0;
    @endphp

    <!-- 4 Kartu Metrik Ringkasan -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px;">
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 15px; border-radius: 8px;">
            <span style="font-size: 12px; color: #1e40af; font-weight: 600;">Total Tagihan</span>
            <h4 style="font-size: 22px; font-weight: bold; color: #1e3a8a; margin: 4px 0 0 0;">{{ $rekap['total_tagihan'] }}</h4>
            <span style="font-size: 13px; color: #3b82f6; font-weight: 500;">Rp {{ number_format($rekap['total_nominal_tagihan'], 0, ',', '.') }}</span>
        </div>

        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; padding: 15px; border-radius: 8px;">
            <span style="font-size: 12px; color: #065f46; font-weight: 600;">Pembayaran Masuk</span>
            <h4 style="font-size: 22px; font-weight: bold; color: #064e3b; margin: 4px 0 0 0;">{{ $rekap['total_pembayaran'] }}</h4>
            <span style="font-size: 13px; color: #10b981; font-weight: 600;">Rp {{ number_format($rekap['total_nominal_bayar'], 0, ',', '.') }}</span>
        </div>

        <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 15px; border-radius: 8px;">
            <span style="font-size: 12px; color: #991b1b; font-weight: 600;">Sisa Tunggakan</span>
            <h4 style="font-size: 22px; font-weight: bold; color: #7f1d1d; margin: 4px 0 0 0;">
                Rp {{ number_format($sisaTunggakan, 0, ',', '.') }}
            </h4>
            <span style="font-size: 12px; color: #ef4444;">Belum terlunasi</span>
        </div>

        <div style="background: #faf5ff; border: 1px solid #e9d5ff; padding: 15px; border-radius: 8px;">
            <span style="font-size: 12px; color: #6b21a8; font-weight: 600;">Realisasi Penerimaan</span>
            <h4 style="font-size: 22px; font-weight: bold; color: #581c87; margin: 4px 0 0 0;">{{ $persenRealisasi }}%</h4>
            <span style="font-size: 12px; color: #8b5cf6;">Tingkat pelunasan</span>
        </div>
    </div>

    <!-- Tabel Rincian Data -->
    <h4 style="font-size: 15px; font-weight: bold; color: #374151; margin: 0 0 12px 0;">
        Rincian Pembayaran Periode {{ $bulan }}/{{ $tahun }}
    </h4>

    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
        <thead>
            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb; color: #4b5563;">
                <th style="padding: 10px; width: 40px;">No</th>
                <th style="padding: 10px;">Nomor Pembayaran</th>
                <th style="padding: 10px;">Nomor Tagihan</th>
                <th style="padding: 10px;">Wajib Retribusi</th>
                <th style="padding: 10px; text-align: right;">Nominal (Rp)</th>
                <th style="padding: 10px; text-align: center;">Metode</th>
                <th style="padding: 10px;">Waktu Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembayarans as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 10px;">{{ $pembayarans->firstItem() + $index }}</td>
                    <td style="padding: 10px; font-weight: bold; font-family: monospace;">{{ $item->nomor_pembayaran }}</td>
                    <td style="padding: 10px; font-family: monospace;">{{ $item->tagihan->nomor_tagihan ?? '-' }}</td>
                    <td style="padding: 10px;">{{ $item->tagihan->wajibRetribusi->nama_lengkap ?? '-' }}</td>
                    <td style="padding: 10px; text-align: right; color: #059669; font-weight: bold;">
                        Rp {{ number_format($item->nominal_bayar, 0, ',', '.') }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        <span style="background: {{ $item->metode_pembayaran === 'tunai' ? '#d1fae5; color: #065f46;' : '#e0e7ff; color: #3730a3;' }} padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase;">
                            {{ $item->metode_pembayaran }}
                        </span>
                    </td>
                    <td style="padding: 10px;">
                        {{ $item->waktu_bayar ? \Carbon\Carbon::parse($item->waktu_bayar)->format('d/m/Y H:i') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 20px; text-align: center; color: #6b7280;">
                        Tidak ada transaksi pembayaran pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($pembayarans->count() > 0)
            <tfoot>
                <tr style="background: #f9fafb; border-top: 2px solid #e5e7eb; font-weight: bold; color: #111827;">
                    <td colspan="4" style="padding: 12px; text-align: right;">TOTAL DIBAYAR (PERIODE INI):</td>
                    <td style="padding: 12px; text-align: right; color: #059669; font-size: 14px;">
                        Rp {{ number_format($rekap['total_nominal_bayar'], 0, ',', '.') }}
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="no-print" style="margin-top: 20px;">
        {{ $pembayarans->withQueryString()->links() }}
    </div>
</div>
@endsection
