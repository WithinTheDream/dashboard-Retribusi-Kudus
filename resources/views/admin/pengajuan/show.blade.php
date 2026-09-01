@extends('layouts.admin')

@section('title', 'Detail Pengajuan - Retribusi Kudus')
@section('page-title', 'Detail Permohonan Pengajuan')

@section('content')
<div style="max-width: 960px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">

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
                <span style="{{ $style }} padding: 6px 14px; border-radius: 9999px; font-size: 13px; font-weight: 600; text-transform: capitalize;">
                    {{ $pengajuan->status_pengajuan }}
                </span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; font-size: 14px;">
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block; margin-bottom: 2px;">Nama Pemohon</span>
                <strong style="color: #1f2937; font-size: 15px;">{{ $pengajuan->nama_lengkap }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block; margin-bottom: 2px;">NIK</span>
                <strong style="color: #1f2937; font-size: 15px;">{{ $pengajuan->nik }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block; margin-bottom: 2px;">Nama Usaha</span>
                <strong style="color: #1f2937; font-size: 15px;">{{ $pengajuan->nama_usaha ?? '-' }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block; margin-bottom: 2px;">No. Telepon / WhatsApp</span>
                <strong style="color: #1f2937; font-size: 15px;">{{ $pengajuan->no_hp }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block; margin-bottom: 2px;">Jenis Retribusi</span>
                <strong style="color: #1f2937; font-size: 15px;">{{ $pengajuan->jenisRetribusi->nama ?? '-' }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block; margin-bottom: 2px;">Wilayah</span>
                <strong style="color: #1f2937; font-size: 15px;">{{ $pengajuan->desa->desa ?? '-' }}, Kec. {{ $pengajuan->kecamatan->kecamatan ?? '-' }}</strong>
            </div>
            <div style="grid-column: 1 / -1;">
                <span style="font-size: 12px; color: #6b7280; display: block; margin-bottom: 2px;">Alamat Lengkap</span>
                <strong style="color: #1f2937; font-size: 15px;">{{ $pengajuan->alamat }} (RT {{ $pengajuan->rt }} / RW {{ $pengajuan->rw }})</strong>
            </div>

            <!-- Koordinat & Lokasi Peta -->
            <div style="grid-column: 1 / -1; background: #f8fafc; padding: 12px 16px; border-radius: 6px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 12px; color: #64748b; display: block;">Titik Koordinat Lokasi:</span>
                    <strong style="color: #334155;">{{ $pengajuan->lat ?? '-' }}, {{ $pengajuan->lokasi_long ?? '-' }}</strong>
                </div>
                @if($pengajuan->lat && $pengajuan->lokasi_long)
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $pengajuan->lat }},{{ $pengajuan->lokasi_long }}" target="_blank" style="background: #2563eb; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 500;">
                        📍 Buka di Google Maps
                    </a>
                @endif
            </div>

            @if($pengajuan->catatan_admin)
                <div style="grid-column: 1 / -1; background: #fffbeb; padding: 14px 16px; border-radius: 6px; border: 1px solid #fef3c7;">
                    <span style="font-size: 12px; color: #92400e; font-weight: bold; display: block;">Catatan Verifikator:</span>
                    <p style="margin: 4px 0 0 0; color: #78350f;">{{ $pengajuan->catatan_admin }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Dokumen Lampiran -->
    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h4 style="font-size: 16px; font-weight: bold; color: #1f2937; margin: 0 0 15px 0;">Dokumen Lampiran Persyaratan</h4>
        @if($pengajuan->dokumen && $pengajuan->dokumen->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                @foreach($pengajuan->dokumen as $dok)
                    <div style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; text-align: center;">
                        <span style="font-size: 12px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 8px;">{{ $dok->jenis_dokumen }}</span>
                        @if(Str::endsWith($dok->file_path, ['.jpg', '.jpeg', '.png']))
                            <a href="{{ asset($dok->file_path) }}" target="_blank">
                                <img src="{{ asset($dok->file_path) }}" alt="{{ $dok->jenis_dokumen }}" style="max-width: 100%; max-height: 120px; object-fit: contain; border-radius: 4px;">
                            </a>
                        @else
                            <a href="{{ asset($dok->file_path) }}" target="_blank" style="display: inline-block; padding: 8px 14px; background: #f3f4f6; color: #2563eb; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 500;">
                                📄 Lihat Dokumen (PDF)
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: #9ca3af; font-size: 14px; margin: 0;">Tidak ada dokumen lampiran yang diunggah.</p>
        @endif
    </div>

    <!-- Riwayat Histori Perjalanan Pengajuan -->
    @if($pengajuan->histori && $pengajuan->histori->count() > 0)
    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h4 style="font-size: 16px; font-weight: bold; color: #1f2937; margin: 0 0 15px 0;">Riwayat Status Pengajuan</h4>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            @foreach($pengajuan->histori as $h)
                <div style="display: flex; gap: 12px; font-size: 14px; border-left: 2px solid #3b82f6; padding-left: 14px;">
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between;">
                            <strong style="text-transform: capitalize; color: #1f2937;">Status: {{ $h->status }}</strong>
                            <span style="font-size: 12px; color: #9ca3af;">{{ $h->created_at ? $h->created_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                        <p style="color: #4b5563; margin: 4px 0 0 0; font-size: 13px;">{{ $h->catatan ?? '-' }}</p>
                        @if($h->user)
                            <span style="font-size: 12px; color: #6b7280;">Diproses oleh: {{ $h->user->nama_lengkap }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tombol Navigasi & Aksi -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
        <a href="{{ route('admin.pengajuan.index') }}" style="color: #4b5563; text-decoration: none; font-size: 14px; font-weight: 500;">
            &larr; Kembali ke Daftar Pengajuan
        </a>
        @if(auth()->user()->hasPermission('pengajuan.update') || auth()->user()->hasPermission('pengajuan.verify'))
        <a href="{{ route('admin.pengajuan.edit', $pengajuan) }}" style="background: #d97706; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600;">
            ✏️ Ubah Status / Verifikasi Pengajuan
        </a>
        @endif
    </div>

</div>
@endsection
