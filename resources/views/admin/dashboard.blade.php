@extends('layouts.admin')

@section('title', 'Dashboard - Retribusi Kudus')
@section('page-title', 'Dashboard Ringkasan')

@section('content')

<!-- Header Sambutan Berdasarkan Role -->
<div style="background: white; padding: 22px 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 25px;">
    <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
        Selamat Datang, {{ auth()->user()->nama_lengkap }}!
    </h3>
    <p style="color: #64748b; margin: 0; font-size: 14px; line-height: 1.5;">
        @if(auth()->user()->isSuperAdmin())
            Anda memiliki <strong>Akses Penuh (Super Administrator)</strong> untuk mengelola master data, operasional, laporan keuangan, dan hak akses sistem.
        @elseif(auth()->user()->hasRole('admin_dinas'))
            Panel <strong>Admin Dinas</strong> untuk mengontrol data master tarif/wilayah, memverifikasi pengajuan warga baru, dan mengelola tagihan.
        @elseif(auth()->user()->isBendahara())
            Panel <strong>Bendahara</strong> untuk memverifikasi setoran tunai petugas lapangan dan pembukuan realisasi kas retribusi.
        @elseif(auth()->user()->isPimpinan())
            Panel <strong>Monitoring Eksekutif</strong> untuk memantau performa penerimaan retribusi daerah Kabupaten Kudus secara real-time.
        @else
            Panel Administrasi Pengelolaan Retribusi Pelayanan Persampahan Kabupaten Kudus.
        @endif
    </p>
</div>

<!-- Kartu Ringkasan Metrik Sesuai Role -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px;">

    <!-- 1. Wajib Retribusi Aktif (Admin Dinas, Pimpinan, Super Admin) -->
    @if(auth()->user()->hasPermission('wajib_retribusi.view') || auth()->user()->isPimpinan())
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-left: 4px solid #2563eb;">
        <p style="font-size: 13px; color: #64748b; margin: 0 0 6px 0; font-weight: 600;">Wajib Retribusi Aktif</p>
        <h2 style="font-size: 26px; font-weight: 700; color: #1e293b; margin: 0;">{{ $totalWargaAktif }}</h2>
        <span style="font-size: 12px; color: #3b82f6;">Warga / Badan Usaha terdaftar</span>
    </div>
    @endif

    <!-- 2. Pengajuan Menunggu (Admin Dinas, Super Admin) -->
    @if(auth()->user()->hasPermission('pengajuan.verify'))
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-left: 4px solid #f59e0b;">
        <p style="font-size: 13px; color: #64748b; margin: 0 0 6px 0; font-weight: 600;">Pengajuan Menunggu Approval</p>
        <h2 style="font-size: 26px; font-weight: 700; color: #1e293b; margin: 0;">{{ $totalPengajuan }}</h2>
        <span style="font-size: 12px; color: #d97706;">Perlu ditinjau</span>
    </div>
    @endif

    <!-- 3. Setoran Petugas Menunggu Verifikasi (Bendahara, Admin Dinas, Super Admin) -->
    @if(auth()->user()->hasPermission('setoran.verify') || auth()->user()->isBendahara())
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-left: 4px solid #8b5cf6;">
        <p style="font-size: 13px; color: #64748b; margin: 0 0 6px 0; font-weight: 600;">Setoran Menunggu Verifikasi</p>
        <h2 style="font-size: 26px; font-weight: 700; color: #1e293b; margin: 0;">{{ $totalSetoranMenunggu }}</h2>
        <span style="font-size: 12px; color: #7c3aed;">Penerimaan tunai petugas</span>
    </div>
    @endif

    <!-- 4. Tagihan Belum Bayar (Semua role operasional/finansial) -->
    @if(auth()->user()->hasPermission('tagihan.view'))
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-left: 4px solid #ef4444;">
        <p style="font-size: 13px; color: #64748b; margin: 0 0 6px 0; font-weight: 600;">Tagihan Belum Lunas</p>
        <h2 style="font-size: 26px; font-weight: 700; color: #1e293b; margin: 0;">{{ $totalTagihanBelumBayar }}</h2>
        <span style="font-size: 12px; color: #ef4444;">Tagihan aktif berjalan</span>
    </div>
    @endif

    <!-- 5. Total Pendapatan / Kas (Semua level) -->
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-left: 4px solid #10b981;">
        <p style="font-size: 13px; color: #64748b; margin: 0 0 6px 0; font-weight: 600;">Total Realisasi Pendapatan</p>
        <h2 style="font-size: 22px; font-weight: 700; color: #065f46; margin: 0;">
            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </h2>
        <span style="font-size: 12px; color: #059669;">Pembayaran terlunasi</span>
    </div>

    <!-- 6. Persentase Kepatuhan / Realisasi (Pimpinan, Bendahara, Super Admin) -->
    @if(auth()->user()->isPimpinan() || auth()->user()->isBendahara() || auth()->user()->isSuperAdmin())
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-left: 4px solid #06b6d4;">
        <p style="font-size: 13px; color: #64748b; margin: 0 0 6px 0; font-weight: 600;">Tingkat Kepatuhan Retribusi</p>
        <h2 style="font-size: 26px; font-weight: 700; color: #0e7490; margin: 0;">{{ $persenKepatuhan }}%</h2>
        <span style="font-size: 12px; color: #0891b2;">Realisasi vs Total Tagihan</span>
    </div>
    @endif

</div>

<!-- Quick Link / Shortcut Aksi Cepat -->
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
    <h4 style="font-size: 15px; font-weight: 700; color: #334155; margin: 0 0 15px 0;">Menu Cepat</h4>
    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        @if(auth()->user()->hasPermission('pengajuan.view'))
            <a href="{{ route('admin.pengajuan.index') }}" style="background: #eff6ff; color: #1d4ed8; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600;">
                📋 Kelola Pengajuan
            </a>
        @endif

        @if(auth()->user()->hasPermission('setoran.view'))
            <a href="{{ route('admin.setoran.index') }}" style="background: #f5f3ff; color: #6d28d9; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600;">
                💰 Verifikasi Setoran Petugas
            </a>
        @endif

        @if(auth()->user()->hasPermission('laporan.view'))
            <a href="{{ route('admin.laporan.index') }}" style="background: #ecfdf5; color: #047857; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600;">
                📊 Lihat & Cetak Laporan
            </a>
        @endif

        @if(auth()->user()->hasPermission('tarif.view'))
            <a href="{{ route('admin.tarif.index') }}" style="background: #fffbeb; color: #b45309; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600;">
                🏷️ Kelola Tarif Retribusi
            </a>
        @endif
    </div>
</div>

@endsection
