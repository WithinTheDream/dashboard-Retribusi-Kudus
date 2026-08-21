@extends('layouts.admin')

@section('title', 'Data Tarif - Retribusi Sampah Kudus')
@section('page-title', 'Kelola Tarif Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Tarif Berdasarkan Jenis Objek</h3>
        @if(auth()->user()->hasPermission('tarif.create'))
        <a href="{{ route('admin.tarif.create') }}" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
            + Tambah Tarif
        </a>
        @endif
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                <th style="padding: 12px; width: 60px;">No</th>
                <th style="padding: 12px;">Jenis Retribusi</th>
                <th style="padding: 12px;">Nominal (Rp)</th>
                <th style="padding: 12px; width: 160px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tarifs as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $index + 1 }}</td>
                    <td style="padding: 12px; font-weight: bold;">
                        {{ $item->jenisRetribusi ? $item->jenisRetribusi->nama : 'Tidak diketahui' }}
                    </td>
                    <td style="padding: 12px; color: #059669; font-weight: bold;">
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        @if(auth()->user()->hasPermission('tarif.update'))
                        <a href="{{ route('admin.tarif.edit', $item) }}" style="color: #d97706; text-decoration: none; margin-right: 12px; font-weight: 500;">
                            Edit
                        </a>
                        @endif

                        @if(auth()->user()->hasPermission('tarif.delete'))
                        <form action="{{ route('admin.tarif.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus tarif ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 500; font-size: 14px;">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
