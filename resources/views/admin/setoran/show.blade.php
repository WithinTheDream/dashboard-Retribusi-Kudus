@extends('layouts.admin')

@section('title', 'Detail Setoran - Retribusi Sampah Kudus')
@section('page-title', 'Detail Setoran Petugas')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    
    <div style="margin-bottom: 20px; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Nomor Setoran: {{ $setoran->nomor_setoran }}</h3>
        <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">Diserahkan oleh: <strong>{{ $setoran->petugas->nama_lengkap ?? '-' }}</strong> pada {{ \Carbon\Carbon::parse($setoran->tanggal_setor)->format('d F Y') }}</p>
    </div>

    <div style="display: flex; gap: 40px; margin-bottom: 30px;">
        <div>
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 3px;">Total Uang Tunai</p>
            <p style="font-size: 24px; font-weight: bold; color: #059669;">Rp {{ number_format($setoran->total_setoran, 0, ',', '.') }}</p>
        </div>
        <div>
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 3px;">Status Verifikasi</p>
            @if($setoran->status_setoran == 'diterima')
                <span style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 4px; font-size: 14px; font-weight: bold; text-transform: uppercase;">DITERIMA</span>
                <p style="font-size: 12px; color: #6b7280; margin-top: 5px;">Oleh: {{ $setoran->bendahara->nama_lengkap ?? '-' }}</p>
            @elseif($setoran->status_setoran == 'ditolak')
                <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 4px; font-size: 14px; font-weight: bold; text-transform: uppercase;">DITOLAK</span>
            @else
                <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 4px; font-size: 14px; font-weight: bold; text-transform: uppercase;">MENUNGGU DIVERIFIKASI</span>
            @endif
        </div>
    </div>

    @if($setoran->status_setoran == 'menunggu' && auth()->user()->hasPermission('setoran.update'))
    <div style="background: #f9fafb; padding: 20px; border-radius: 6px; border: 1px dashed #d1d5db;">
        <h4 style="font-size: 15px; font-weight: bold; margin-bottom: 15px;">Verifikasi Setoran Ini</h4>
        <form action="{{ route('admin.setoran.verify', $setoran) }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 14px; margin-bottom: 5px;">Pilih Aksi</label>
                <select name="status_setoran" style="padding: 10px; width: 100%; border: 1px solid #d1d5db; border-radius: 6px;">
                    <option value="diterima">Terima Setoran (Uang Sesuai)</option>
                    <option value="ditolak">Tolak Setoran (Ada Masalah)</option>
                </select>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 14px; margin-bottom: 5px;">Catatan (Opsional)</label>
                <input type="text" name="catatan" placeholder="Masukkan catatan jika ditolak..." style="padding: 10px; width: 100%; border: 1px solid #d1d5db; border-radius: 6px;">
            </div>
            <button type="submit" style="background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold;">Proses Verifikasi</button>
        </form>
    </div>
    @endif

    <div style="margin-top: 30px;">
        <a href="{{ route('admin.setoran.index') }}" style="color: #4b5563; text-decoration: none; font-size: 14px;">&larr; Kembali ke Daftar Setoran</a>
    </div>

</div>
@endsection
