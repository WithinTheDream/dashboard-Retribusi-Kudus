@extends('layouts.admin')

@section('title', 'Data Tarif - Retribusi Sampah Kudus')
@section('page-title', 'Kelola Tarif Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Tarif Retribusi</h3>
            <p style="color: #6b7280; font-size: 13px; margin: 4px 0 0 0;">Pengaturan besaran tarif per jenis retribusi dan periode tahun.</p>
        </div>
        @if(auth()->user()->hasPermission('tarif.create'))
        <a href="{{ route('admin.tarif.create') }}" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
            + Tambah Tarif
        </a>
        @endif
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
        <thead>
            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                <th style="padding: 12px; width: 50px;">No</th>
                <th style="padding: 12px;">Jenis Retribusi</th>
                <th style="padding: 12px;">Nominal (Rp)</th>
                <th style="padding: 12px;">Satuan</th>
                <th style="padding: 12px;">Periode</th>
                <th style="padding: 12px; text-align: center;">Status</th>
                <th style="padding: 12px; width: 140px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tarifs as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $index + 1 }}</td>
                    <td style="padding: 12px; font-weight: 600; color: #1e293b;">
                        {{ $item->jenisRetribusi ? $item->jenisRetribusi->kode . ' - ' . $item->jenisRetribusi->nama : 'Tidak diketahui' }}
                    </td>
                    <td style="padding: 12px; color: #059669; font-weight: 700;">
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                    <td style="padding: 12px; color: #475569;">
                        {{ $item->satuan ?? 'Bulan' }}
                    </td>
                    <td style="padding: 12px; color: #475569;">
                        {{ $item->periode ?? '-' }}
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        @if($item->is_aktif)
                            <span style="background: #dcfce7; color: #15803d; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                Aktif
                            </span>
                        @else
                            <span style="background: #f1f5f9; color: #64748b; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                Non-Aktif
                            </span>
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        @if(auth()->user()->hasPermission('tarif.update'))
                        <a href="{{ route('admin.tarif.edit', $item) }}" style="color: #d97706; text-decoration: none; margin-right: 10px; font-weight: 600;">
                            Edit
                        </a>
                        @endif

                        @if(auth()->user()->hasPermission('tarif.delete'))
                        <form action="{{ route('admin.tarif.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus tarif ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 600; font-size: 14px;">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 20px; text-align: center; color: #6b7280;">Belum ada data tarif retribusi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
