@extends('layouts.admin')

@section('title', 'Detail Wajib Retribusi - Retribusi Sampah Kudus')
@section('page-title', 'Detail Wajib Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Detail Wajib Retribusi</h3>

        <div>
            <a href="{{ route('admin.wajib-retribusi.edit', $wajibRetribusi) }}" style="background: #d97706; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                Edit
            </a>
            <a href="{{ route('admin.wajib-retribusi.index') }}" style="color: #4b5563; text-decoration: none; margin-left: 10px;">
                Kembali
            </a>
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; width: 200px; font-weight: bold; color: #374151;">Kode</td>
            <td style="padding: 12px; color: #1f2937;">{{ $wajibRetribusi->kode }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; font-weight: bold; color: #374151;">Nama Lengkap</td>
            <td style="padding: 12px; color: #1f2937;">{{ $wajibRetribusi->nama_lengkap }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; font-weight: bold; color: #374151;">NIK</td>
            <td style="padding: 12px; color: #1f2937;">{{ $wajibRetribusi->nik }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; font-weight: bold; color: #374151;">Nama Usaha</td>
            <td style="padding: 12px; color: #1f2937;">{{ $wajibRetribusi->nama_usaha ?? '-' }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; font-weight: bold; color: #374151;">Jenis Retribusi</td>
            <td style="padding: 12px; color: #1f2937;">{{ $wajibRetribusi->jenisRetribusi->kode ?? '-' }} - {{ $wajibRetribusi->jenisRetribusi->nama ?? '-' }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; font-weight: bold; color: #374151;">Kecamatan</td>
            <td style="padding: 12px; color: #1f2937;">{{ $wajibRetribusi->kecamatan->kecamatan ?? '-' }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; font-weight: bold; color: #374151;">Desa</td>
            <td style="padding: 12px; color: #1f2937;">{{ $wajibRetribusi->desa->desa ?? '-' }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; font-weight: bold; color: #374151;">Alamat</td>
            <td style="padding: 12px; color: #1f2937;">{{ $wajibRetribusi->alamat }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; font-weight: bold; color: #374151;">RT / RW</td>
            <td style="padding: 12px; color: #1f2937;">{{ $wajibRetribusi->rt }} / {{ $wajibRetribusi->rw }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; font-weight: bold; color: #374151;">No. HP</td>
            <td style="padding: 12px; color: #1f2937;">{{ $wajibRetribusi->no_hp }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 12px; font-weight: bold; color: #374151;">Status</td>
            <td style="padding: 12px;">
                @if($wajibRetribusi->status_aktif)
                    <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Aktif</span>
                @else
                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Nonaktif</span>
                @endif
            </td>
        </tr>
    </table>
</div>
@endsection
