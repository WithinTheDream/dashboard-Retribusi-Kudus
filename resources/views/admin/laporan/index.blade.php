@extends('layouts.admin')

@section('title', 'Laporan - Retribusi Sampah Kudus')
@section('page-title', 'Laporan Rekap Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 20px;">Rekap Tagihan & Pembayaran</h3>

    <form action="{{ route('admin.laporan.index') }}" method="GET" style="display: flex; align-items: flex-end; gap: 15px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
        <div>
            <label style="font-weight: bold; margin-bottom: 5px; display: block;">Bulan</label>
            <select name="bulan" style="display: block; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                        {{ $i }} - {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>

        <div>
            <label style="font-weight: bold; margin-bottom: 5px; display: block;">Tahun</label>
            <input type="number" name="tahun" min="2020" max="2099" value="{{ $tahun }}" style="display: block; width: 120px; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
        </div>

        <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
            Tampilkan
        </button>
    </form>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 25px;">
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 15px; border-radius: 8px;">
            <div style="font-size: 13px; color: #1d4ed8; font-weight: bold;">Total Tagihan</div>
            <div style="font-size: 22px; font-weight: bold; color: #1e3a8a; margin-top: 5px;">{{ $rekap['total_tagihan'] }}</div>
        </div>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 15px; border-radius: 8px;">
            <div style="font-size: 13px; color: #1d4ed8; font-weight: bold;">Total Nominal Tagihan</div>
            <div style="font-size: 22px; font-weight: bold; color: #1e3a8a; margin-top: 5px;">Rp {{ number_format($rekap['total_nominal_tagihan'], 0, ',', '.') }}</div>
        </div>

        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; padding: 15px; border-radius: 8px;">
            <div style="font-size: 13px; color: #047857; font-weight: bold;">Pembayaran Masuk</div>
            <div style="font-size: 22px; font-weight: bold; color: #064e3b; margin-top: 5px;">{{ $rekap['total_pembayaran'] }}</div>
        </div>

        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; padding: 15px; border-radius: 8px;">
            <div style="font-size: 13px; color: #047857; font-weight: bold;">Total Nominal Dibayar</div>
            <div style="font-size: 22px; font-weight: bold; color: #064e3b; margin-top: 5px;">Rp {{ number_format($rekap['total_nominal_bayar'], 0, ',', '.') }}</div>
        </div>
    </div>

    <h4 style="font-size: 15px; font-weight: bold; color: #1f2937; margin-bottom: 10px;">Rincian Pembayaran Periode {{ $bulan }}/{{ $tahun }}</h4>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                <th style="padding: 12px; width: 60px;">No</th>
                <th style="padding: 12px;">Nomor Pembayaran</th>
                <th style="padding: 12px;">Nomor Tagihan</th>
                <th style="padding: 12px;">Wajib Retribusi</th>
                <th style="padding: 12px;">Nominal (Rp)</th>
                <th style="padding: 12px;">Metode</th>
                <th style="padding: 12px;">Waktu Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembayarans as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $index + 1 }}</td>
                    <td style="padding: 12px; font-weight: bold;">{{ $item->nomor_pembayaran }}</td>
                    <td style="padding: 12px;">{{ $item->tagihan->nomor_tagihan ?? '-' }}</td>
                    <td style="padding: 12px;">{{ $item->tagihan->wajibRetribusi->nama_lengkap ?? '-' }}</td>
                    <td style="padding: 12px; color: #059669; font-weight: bold;">
                        Rp {{ number_format($item->nominal_bayar, 0, ',', '.') }}
                    </td>
                    <td style="padding: 12px; text-transform: capitalize;">{{ $item->metode_pembayaran }}</td>
                    <td style="padding: 12px;">{{ $item->waktu_bayar->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 12px; text-align: center; color: #6b7280;">
                        Belum ada pembayaran pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $pembayarans->appends(['bulan' => $bulan, 'tahun' => $tahun])->links() }}
    </div>
</div>
@endsection