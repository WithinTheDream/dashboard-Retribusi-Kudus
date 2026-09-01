@extends('layouts.admin')

@section('title', 'Wajib Retribusi - Retribusi Sampah Kudus')
@section('page-title', 'Kelola Wajib Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Wajib Retribusi</h3>
        @if(auth()->user()->hasPermission('wajib_retribusi.create'))
        <a href="{{ route('admin.wajib-retribusi.create') }}" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
            + Tambah
        </a>
        @endif
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
        <thead>
            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                <th style="padding: 12px; width: 60px;">No</th>
                <th style="padding: 12px;">Kode</th>
                <th style="padding: 12px;">Nama Lengkap</th>
                <th style="padding: 12px;">NIK</th>
                <th style="padding: 12px;">Nama Usaha</th>
                <th style="padding: 12px;">Jenis Retribusi</th>
                <th style="padding: 12px;">Status</th>
                <th style="padding: 12px; width: 160px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($wajibRetribusis as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ ($wajibRetribusis->currentPage() - 1) * $wajibRetribusis->perPage() + $index + 1 }}</td>
                    <td style="padding: 12px; font-weight: bold; color: #1e293b;">{{ $item->kode }}</td>
                    <td style="padding: 12px;">{{ $item->nama_lengkap }}</td>
                    <td style="padding: 12px; color: #475569;">{{ $item->nik }}</td>
                    <td style="padding: 12px; color: #475569;">{{ $item->nama_usaha ?? '-' }}</td>
                    <td style="padding: 12px; color: #475569;">{{ $item->jenisRetribusi->nama ?? '-' }}</td>
                    <td style="padding: 12px;">
                        @if($item->status_aktif)
                            <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">Aktif</span>
                        @else
                            <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">Nonaktif</span>
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        @if(auth()->user()->hasPermission('wajib_retribusi.view'))
                        <a href="{{ route('admin.wajib-retribusi.show', $item) }}" style="color: #2563eb; text-decoration: none; margin-right: 10px; font-weight: 600;">Detail</a>
                        @endif

                        @if(auth()->user()->hasPermission('wajib_retribusi.update'))
                        <a href="{{ route('admin.wajib-retribusi.edit', $item) }}" style="color: #d97706; text-decoration: none; margin-right: 10px; font-weight: 600;">Edit</a>
                        @endif

                        @if(auth()->user()->hasPermission('wajib_retribusi.delete'))
                        <form action="{{ route('admin.wajib-retribusi.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data wajib retribusi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 600; font-size: 14px;">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="padding: 20px; text-align: center; color: #6b7280;">Belum ada data wajib retribusi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $wajibRetribusis->links() }}
    </div>
</div>
@endsection
