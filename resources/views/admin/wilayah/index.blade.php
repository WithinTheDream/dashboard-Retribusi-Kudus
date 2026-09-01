@extends('layouts.admin')

@section('title', 'Data Wilayah - Retribusi Sampah Kudus')
@section('page-title', 'Kelola Wilayah (Kecamatan & Desa)')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Kecamatan dan Desa di Kabupaten Kudus</h3>
        @if(auth()->user()->hasPermission('wilayah.create'))
        <a href="{{ route('admin.wilayah.create') }}" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
            + Tambah Kecamatan
        </a>
        @endif
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
        <thead>
            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                <th style="padding: 12px; width: 60px;">No</th>
                <th style="padding: 12px;">Nama Kecamatan</th>
                <th style="padding: 12px;">Daftar Desa / Kelurahan</th>
                <th style="padding: 12px; width: 160px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kecamatan as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $index + 1 }}</td>
                    <td style="padding: 12px; font-weight: bold;">
                        {{ $item->kecamatan }}
                    </td>
                    <td style="padding: 12px; color: #4b5563;">
                        @if($item->desas && $item->desas->count() > 0)
                            {{ $item->desas->pluck('desa')->implode(', ') }}
                        @else
                            <em style="color: #9ca3af;">Belum ada data desa</em>
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        @if(auth()->user()->hasPermission('wilayah.update'))
                        <a href="{{ route('admin.wilayah.edit', $item->id) }}" style="color: #d97706; text-decoration: none; margin-right: 12px; font-weight: 500;">Edit</a>
                        @endif
                        @if(auth()->user()->hasPermission('wilayah.delete'))
                        <form action="{{ route('admin.wilayah.destroy', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus kecamatan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 500; padding: 0;">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="padding: 20px; text-align: center; color: #6b7280;">Belum ada data wilayah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
