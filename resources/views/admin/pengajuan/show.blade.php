@extends('layouts.admin')

@section('title', 'Detail Pengajuan - Retribusi Kudus')
@section('page-title', 'Detail Permohonan Pengajuan')

@section('content')
<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">

    <!-- Informasi Detail Permohonan -->
    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">
            <div>
                <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin: 0 0 5px 0;">Pengajuan #{{ $pengajuan->nomor_pengajuan }}</h3>
                <p style="font-size: 13px; color: #6b7280; margin: 0;">Diajukan pada: {{ $pengajuan->created_at ? $pengajuan->created_at->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                @php
                    $badgeStyles = [
                        'menunggu'   => 'background: #fef3c7; color: #92400e;',
                        'survey'     => 'background: #e0e7ff; color: #3730a3;',
                        'perbaikan'  => 'background: #ffedd5; color: #9a3412;',
                        'disetujui'  => 'background: #d1fae5; color: #065f46;',
                        'ditolak'    => 'background: #fee2e2; color: #991b1b;',
                    ];
                    $style = $badgeStyles[$pengajuan->status_pengajuan] ?? 'background: #f3f4f6; color: #374151;';
                @endphp
                <span style="{{ $style }} padding: 5px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: capitalize;">
                    {{ $pengajuan->status_pengajuan }}
                </span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; font-size: 14px;">
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block;">Nama Pemohon</span>
                <strong style="color: #1f2937;">{{ $pengajuan->nama_lengkap }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block;">NIK</span>
                <strong style="color: #1f2937;">{{ $pengajuan->nik }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block;">Nama Usaha</span>
                <strong style="color: #1f2937;">{{ $pengajuan->nama_usaha ?? '-' }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block;">No. Telepon / WhatsApp</span>
                <strong style="color: #1f2937;">{{ $pengajuan->no_hp }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block;">Jenis Retribusi</span>
                <strong style="color: #1f2937;">{{ $pengajuan->jenisRetribusi->nama ?? '-' }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block;">Wilayah</span>
                <strong style="color: #1f2937;">{{ $pengajuan->desa->desa ?? '-' }}, Kec. {{ $pengajuan->kecamatan->kecamatan ?? '-' }}</strong>
            </div>
            <div style="grid-column: 1 / -1;">
                <span style="font-size: 12px; color: #6b7280; display: block;">Alamat Lengkap</span>
                <strong style="color: #1f2937;">{{ $pengajuan->alamat }} (RT {{ $pengajuan->rt }} / RW {{ $pengajuan->rw }})</strong>
            </div>
            @if($pengajuan->catatan_admin)
                <div style="grid-column: 1 / -1; background: #f9fafb; padding: 12px; border-radius: 6px; border: 1px solid #e5e7eb;">
                    <span style="font-size: 12px; color: #6b7280; display: block;">Catatan Verifikator:</span>
                    <p style="margin: 4px 0 0 0; color: #374151;">{{ $pengajuan->catatan_admin }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tombol Kembali & Aksi Edit -->
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('admin.pengajuan.index') }}" style="color: #4b5563; text-decoration: none; font-size: 14px; font-weight: 500;">
            &larr; Kembali ke Daftar Pengajuan
        </a>
        <a href="{{ route('admin.pengajuan.edit', $pengajuan) }}" style="background: #d97706; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
            Ubah Status / Edit Data
        </a>
    </div>

</div>
@endsection
