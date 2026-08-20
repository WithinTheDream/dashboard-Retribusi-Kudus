@extends('layouts.admin')

@section('title', 'Dashboard - Retribusi Kudus')
@section('page-title', 'Dashboard Utama')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px;">
    <!-- Pengajuan Menunggu -->
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #f59e0b;">
        <p style="font-size: 13px; color: #6b7280; margin: 0 0 5px 0;">Pengajuan Menunggu</p>
        <h2 style="font-size: 24px; font-weight: bold; color: #111827; margin: 0;">{{ $totalPengajuan }}</h2>
    </div>

    <!-- Total Wajib Retribusi -->
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #2563eb;">
        <p style="font-size: 13px; color: #6b7280; margin: 0 0 5px 0;">Wajib Retribusi Aktif</p>
        <h2 style="font-size: 24px; font-weight: bold; color: #111827; margin: 0;">{{ $totalWargaAktif }}</h2>
    </div>

    <!-- Tagihan Belum Bayar -->
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #ef4444;">
        <p style="font-size: 13px; color: #6b7280; margin: 0 0 5px 0;">Tagihan Belum Bayar</p>
        <h2 style="font-size: 24px; font-weight: bold; color: #111827; margin: 0;">{{ $totalTagihanBelumBayar }}</h2>
    </div>

    <!-- Total Realisasi Pendapatan -->
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">
        <p style="font-size: 13px; color: #6b7280; margin: 0 0 5px 0;">Total Pendapatan</p>
        <h2 style="font-size: 22px; font-weight: bold; color: #111827; margin: 0;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
    </div>
</div>

<!-- Banner Selamat Datang / Ringkasan -->
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 8px;">Selamat Datang di Panel Retribusi Sampah Kudus</h3>
    <p style="color: #4b5563; margin: 0; font-size: 14px; line-height: 1.5;">
        Kelola data pengajuan, tarif, wajib retribusi, dan verifikasi pembayaran melalui menu navigasi di bilah sebelah kiri.
    </p>
</div>
@endsection
