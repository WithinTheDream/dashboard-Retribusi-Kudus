@extends('layouts.admin')

@section('title', 'Detail Pembayaran - Retribusi Kudus')
@section('page-title', 'Detail Transaksi Pembayaran')

@section('content')
<div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">
    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">
            <div>
                <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin: 0 0 5px 0;">No. {{ $pembayaran->nomor_pembayaran }}</h3>
                <p style="font-size: 13px; color: #6b7280; margin: 0;">Waktu Bayar: {{ \Carbon\Carbon::parse($pembayaran->waktu_bayar)->format('d M Y H:i') }}</p>
            </div>
            <span style="background: #d1fae5; color: #065f46; padding: 5px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                {{ $pembayaran->metode_pembayaran }}
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; font-size: 14px;">
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block;">Wajib Retribusi</span>
                <strong style="color: #1f2937;">{{ $pembayaran->tagihan->wajibRetribusi->nama_lengkap ?? '-' }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block;">Nomor Tagihan</span>
                <strong style="color: #1f2937; font-family: monospace;">{{ $pembayaran->tagihan->nomor_tagihan ?? '-' }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block;">Jumlah Pembayaran</span>
                <strong style="color: #10b981; font-size: 16px;">Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #6b7280; display: block;">Petugas Penerima</span>
                <strong style="color: #1f2937;">{{ $pembayaran->petugas->name ?? 'Admin / Sistem' }}</strong>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('admin.pembayaran.index') }}" style="color: #4b5563; text-decoration: none; font-size: 14px; font-weight: 500;">
            &larr; Kembali ke Daftar Pembayaran
        </a>
        <a href="{{ route('admin.pembayaran.edit', $pembayaran) }}" style="background: #d97706; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
            Edit Pembayaran
        </a>
    </div>
</div>
@endsection
