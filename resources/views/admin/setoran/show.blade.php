@extends('layouts.admin')

@section('title', 'Detail Setoran - Retribusi Sampah Kudus')
@section('page-title', 'Detail Setoran Petugas')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    
    <div style="margin-bottom: 20px; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
        <div>
            <h3 style="font-size: 20px; font-weight: bold; color: #1f2937;">Nomor Setoran: {{ $setoran->nomor_setoran }}</h3>
            <p style="color: #6b7280; font-size: 14px; margin-top: 4px;">
                Penyetor: <strong>{{ $setoran->petugas->nama_lengkap ?? '-' }}</strong> ({{ $setoran->petugas->no_hp ?? '-' }}) &bull; Diserahkan pada {{ \Carbon\Carbon::parse($setoran->tanggal_setor)->format('d F Y') }}
            </p>
        </div>

        <div>
            @if($setoran->status_setoran == 'diterima')
                <span style="background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; padding: 6px 14px; border-radius: 6px; font-size: 14px; font-weight: bold; text-transform: uppercase; display: inline-block;">
                    ✓ DITERIMA MASUK KAS
                </span>
            @elseif($setoran->status_setoran == 'ditolak')
                <span style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: 6px 14px; border-radius: 6px; font-size: 14px; font-weight: bold; text-transform: uppercase; display: inline-block;">
                    ✕ DITOLAK
                </span>
            @else
                <span style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a; padding: 6px 14px; border-radius: 6px; font-size: 14px; font-weight: bold; text-transform: uppercase; display: inline-block;">
                    ⏳ MENUNGGU VERIFIKASI BENDAHARA
                </span>
            @endif
        </div>
    </div>

    <!-- Info Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px;">
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 16px; border-radius: 8px;">
            <p style="font-size: 13px; color: #166534; font-weight: 500;">Total Uang Tunai Disetor</p>
            <p style="font-size: 24px; font-weight: bold; color: #15803d; margin-top: 4px;">Rp {{ number_format($setoran->total_setoran, 0, ',', '.') }}</p>
        </div>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 16px; border-radius: 8px;">
            <p style="font-size: 13px; color: #1e40af; font-weight: 500;">Jumlah Transaksi Tunai</p>
            <p style="font-size: 24px; font-weight: bold; color: #1d4ed8; margin-top: 4px;">{{ $setoran->details->count() }} Transaksi</p>
        </div>

        @if($setoran->status_setoran !== 'menunggu')
            <div style="background: #f9fafb; border: 1px solid #e5e7eb; padding: 16px; border-radius: 8px;">
                <p style="font-size: 13px; color: #4b5563; font-weight: 500;">Diverifikasi Oleh</p>
                <p style="font-size: 16px; font-weight: bold; color: #111827; margin-top: 4px;">{{ $setoran->bendahara->nama_lengkap ?? '-' }}</p>
                <small style="color: #6b7280;">{{ $setoran->waktu_verifikasi ? \Carbon\Carbon::parse($setoran->waktu_verifikasi)->format('d M Y H:i') : '' }}</small>
            </div>
        @endif
    </div>

    @if($setoran->catatan)
        <div style="background: #fffbeb; border: 1px solid #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 6px; margin-bottom: 25px; font-size: 14px;">
            <strong>Catatan Bendahara:</strong> {{ $setoran->catatan }}
        </div>
    @endif

    <!-- Form Verifikasi Jika Menunggu -->
    @if($setoran->status_setoran == 'menunggu' && auth()->user()->hasPermission('setoran.update'))
        <div style="background: #f9fafb; padding: 20px; border-radius: 8px; border: 1px dashed #d1d5db; margin-bottom: 30px;">
            <h4 style="font-size: 16px; font-weight: bold; color: #111827; margin-bottom: 12px;">Formulir Verifikasi Penerimaan Uang Fisik</h4>
            <p style="font-size: 13px; color: #4b5563; margin-bottom: 16px;">Pastikan jumlah uang tunai fisik yang diserahkan petugas sama persis dengan total nominal <strong>Rp {{ number_format($setoran->total_setoran, 0, ',', '.') }}</strong>.</p>
            
            <form action="{{ route('admin.setoran.verify', $setoran) }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Keputusan</label>
                        <select name="status_setoran" style="padding: 10px; width: 100%; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                            <option value="diterima">✓ Terima Setoran (Uang Fisik Sesuai)</option>
                            <option value="ditolak">✕ Tolak Setoran (Ada Masalah / Selisih)</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Catatan (Wajib jika ditolak)</label>
                        <input type="text" name="catatan" placeholder="Contoh: Uang fisik kurang Rp 20.000 atau nominal cocok..." style="padding: 10px; width: 100%; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>
                <button type="submit" style="background: #2563eb; color: white; border: none; padding: 10px 24px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px;">
                    Simpan Verifikasi
                </button>
            </form>
        </div>
    @endif

    <!-- Rincian Transaksi Pembayaran dalam Setoran Ini -->
    <h4 style="font-size: 16px; font-weight: bold; color: #1f2937; margin-bottom: 12px;">Rincian Pembayaran Tunai yang Disetor</h4>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 10px; width: 40px;">No</th>
                    <th style="padding: 10px;">Nomor Pembayaran</th>
                    <th style="padding: 10px;">Wajib Retribusi</th>
                    <th style="padding: 10px;">Wilayah</th>
                    <th style="padding: 10px;">Periode Tagihan</th>
                    <th style="padding: 10px;">Nominal (Rp)</th>
                    <th style="padding: 10px;">Waktu Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($setoran->details as $idx => $detail)
                    @php
                        $pembayaran = $detail->pembayaran;
                        $tagihan = $pembayaran?->tagihan;
                        $wr = $tagihan?->wajibRetribusi;
                    @endphp
                    <tr style="border-bottom: 1px solid #e5e7eb; font-size: 13px;">
                        <td style="padding: 10px;">{{ $idx + 1 }}</td>
                        <td style="padding: 10px; font-weight: 600;">{{ $pembayaran->nomor_pembayaran ?? '-' }}</td>
                        <td style="padding: 10px;">
                            <div style="font-weight: 600; color: #111827;">{{ $wr->nama_lengkap ?? '-' }}</div>
                            <small style="color: #6b7280;">NIK: {{ $wr->nik ?? '-' }}</small>
                        </td>
                        <td style="padding: 10px;">
                            Desa {{ $wr->desa->desa ?? '-' }} (RW {{ $wr->rw ?? '-' }})
                        </td>
                        <td style="padding: 10px;">
                            Bulan {{ $tagihan->bulan ?? '-' }}/{{ $tagihan->tahun ?? '-' }}
                        </td>
                        <td style="padding: 10px; color: #059669; font-weight: bold;">
                            Rp {{ number_format($pembayaran->nominal_bayar ?? 0, 0, ',', '.') }}
                        </td>
                        <td style="padding: 10px; color: #4b5563;">
                            {{ $pembayaran->waktu_bayar ? \Carbon\Carbon::parse($pembayaran->waktu_bayar)->format('d/m/Y H:i') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 16px; text-align: center; color: #6b7280;">
                            Tidak ada rincian transaksi pembayaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px;">
        <a href="{{ route('admin.setoran.index') }}" style="color: #4b5563; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
            &larr; Kembali ke Daftar Setoran
        </a>
    </div>

</div>
@endsection
