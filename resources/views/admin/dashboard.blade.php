@extends('layouts.admin')

@section('title', 'Dashboard - Retribusi Kudus')
@section('page-title', 'Dashboard Ringkasan')

@section('content')

<!-- Header Sambutan Berdasarkan Role -->
<div style="background: white; padding: 20px 24px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); margin-bottom: 24px; border: 1px solid #f1f5f9;">
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

<!-- Kartu Ringkasan Metrik Sesuai Role (Grid Responsif 3 Kolom Rapi & Sejajar) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px;">

    <!-- 1. Wajib Retribusi Aktif (Admin Dinas, Pimpinan, Super Admin) -->
    @if(auth()->user()->hasPermission('wajib_retribusi.view') || auth()->user()->isPimpinan())
    <div style="background: white; padding: 20px 22px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; border-left: 4px solid #2563eb; display: flex; flex-direction: column; justify-content: space-between; min-height: 135px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 12px;">
            <span style="font-size: 13px; font-weight: 600; color: #64748b; line-height: 1.4;">Wajib Retribusi Aktif</span>
            <div style="background: #eff6ff; color: #2563eb; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>
        <div>
            <h2 style="font-size: 26px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0; line-height: 1.2;">{{ $totalWargaAktif }}</h2>
            <span style="font-size: 12px; color: #3b82f6; font-weight: 500;">Warga / Badan Usaha terdaftar</span>
        </div>
    </div>
    @endif

    <!-- 2. Pengajuan Menunggu (Admin Dinas, Super Admin) -->
    @if(auth()->user()->hasPermission('pengajuan.verify'))
    <div style="background: white; padding: 20px 22px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; border-left: 4px solid #f59e0b; display: flex; flex-direction: column; justify-content: space-between; min-height: 135px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 12px;">
            <span style="font-size: 13px; font-weight: 600; color: #64748b; line-height: 1.4;">Pengajuan Menunggu Approval</span>
            <div style="background: #fffbeb; color: #d97706; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
        </div>
        <div>
            <h2 style="font-size: 26px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0; line-height: 1.2;">{{ $totalPengajuan }}</h2>
            <span style="font-size: 12px; color: #d97706; font-weight: 500;">Perlu ditinjau</span>
        </div>
    </div>
    @endif

    <!-- 3. Setoran Petugas Menunggu Verifikasi (Bendahara, Admin Dinas, Super Admin) -->
    @if(auth()->user()->hasPermission('setoran.verify') || auth()->user()->isBendahara())
    <div style="background: white; padding: 20px 22px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; border-left: 4px solid #8b5cf6; display: flex; flex-direction: column; justify-content: space-between; min-height: 135px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 12px;">
            <span style="font-size: 13px; font-weight: 600; color: #64748b; line-height: 1.4;">Setoran Menunggu Verifikasi</span>
            <div style="background: #f5f3ff; color: #7c3aed; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                </svg>
            </div>
        </div>
        <div>
            <h2 style="font-size: 26px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0; line-height: 1.2;">{{ $totalSetoranMenunggu }}</h2>
            <span style="font-size: 12px; color: #7c3aed; font-weight: 500;">Penerimaan tunai petugas</span>
        </div>
    </div>
    @endif

    <!-- 4. Tagihan Belum Bayar (Semua role operasional/finansial) -->
    @if(auth()->user()->hasPermission('tagihan.view'))
    <div style="background: white; padding: 20px 22px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; border-left: 4px solid #ef4444; display: flex; flex-direction: column; justify-content: space-between; min-height: 135px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 12px;">
            <span style="font-size: 13px; font-weight: 600; color: #64748b; line-height: 1.4;">Tagihan Belum Lunas</span>
            <div style="background: #fef2f2; color: #dc2626; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
                </svg>
            </div>
        </div>
        <div>
            <h2 style="font-size: 26px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0; line-height: 1.2;">{{ $totalTagihanBelumBayar }}</h2>
            <span style="font-size: 12px; color: #ef4444; font-weight: 500;">Tagihan aktif berjalan</span>
        </div>
    </div>
    @endif

    <!-- 5. Total Pendapatan / Kas (Semua level) -->
    <div style="background: white; padding: 20px 22px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; border-left: 4px solid #10b981; display: flex; flex-direction: column; justify-content: space-between; min-height: 135px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 12px;">
            <span style="font-size: 13px; font-weight: 600; color: #64748b; line-height: 1.4;">Total Realisasi Pendapatan</span>
            <div style="background: #ecfdf5; color: #059669; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
        </div>
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: #065f46; margin: 0 0 4px 0; line-height: 1.2;">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </h2>
            <span style="font-size: 12px; color: #059669; font-weight: 500;">Pembayaran terlunasi</span>
        </div>
    </div>

    <!-- 6. Persentase Kepatuhan / Realisasi (Pimpinan, Bendahara, Super Admin) -->
    @if(auth()->user()->isPimpinan() || auth()->user()->isBendahara() || auth()->user()->isSuperAdmin())
    <div style="background: white; padding: 20px 22px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; border-left: 4px solid #06b6d4; display: flex; flex-direction: column; justify-content: space-between; min-height: 135px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 12px;">
            <span style="font-size: 13px; font-weight: 600; color: #64748b; line-height: 1.4;">Tingkat Kepatuhan Retribusi</span>
            <div style="background: #ecfeff; color: #0891b2; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
        </div>
        <div>
            <h2 style="font-size: 26px; font-weight: 700; color: #0e7490; margin: 0 0 4px 0; line-height: 1.2;">{{ $persenKepatuhan }}%</h2>
            <span style="font-size: 12px; color: #0891b2; font-weight: 500;">Realisasi vs Total Tagihan</span>
        </div>
    </div>
    @endif

</div>

<!-- Quick Link / Shortcut Aksi Cepat -->
<div style="background: white; padding: 22px 24px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #f1f5f9;">
    <h4 style="font-size: 15px; font-weight: 700; color: #334155; margin: 0 0 14px 0;">Menu Cepat</h4>
    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        @if(auth()->user()->hasPermission('pengajuan.view'))
            <a href="{{ route('admin.pengajuan.index') }}" style="background: #eff6ff; color: #1d4ed8; padding: 9px 16px; border-radius: 8px; text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #dbeafe;">
                📋 Kelola Pengajuan
            </a>
        @endif

        @if(auth()->user()->hasPermission('setoran.view'))
            <a href="{{ route('admin.setoran.index') }}" style="background: #f5f3ff; color: #6d28d9; padding: 9px 16px; border-radius: 8px; text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #ede9fe;">
                💰 Verifikasi Setoran Petugas
            </a>
        @endif

        @if(auth()->user()->hasPermission('laporan.view'))
            <a href="{{ route('admin.laporan.index') }}" style="background: #ecfdf5; color: #047857; padding: 9px 16px; border-radius: 8px; text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #d1fae5;">
                📊 Lihat & Cetak Laporan
            </a>
        @endif

        @if(auth()->user()->hasPermission('tarif.view'))
            <a href="{{ route('admin.tarif.index') }}" style="background: #fffbeb; color: #b45309; padding: 9px 16px; border-radius: 8px; text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #fef3c7;">
                🏷️ Kelola Tarif Retribusi
            </a>
        @endif
    </div>
</div>

@endsection
