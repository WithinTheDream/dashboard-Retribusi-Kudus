@extends('layouts.admin')

@section('title', 'Pengajuan - Retribusi Sampah Kudus')
@section('page-title', 'Kelola Pengajuan Wajib Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin: 0;">Daftar Pengajuan Wajib Retribusi</h3>

        @if(auth()->user()->hasPermission('pengajuan.create'))
        <a href="{{ route('admin.pengajuan.create') }}" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
            + Tambah
        </a>
        @endif
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
        <thead>
            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                <th style="padding: 12px; width: 50px;">No</th>
                <th style="padding: 12px;">Nomor Pengajuan</th>
                <th style="padding: 12px;">Nama Pemohon</th>
                <th style="padding: 12px;">NIK</th>
                <th style="padding: 12px;">Nama Usaha</th>
                <th style="padding: 12px;">Jenis Retribusi</th>
                <th style="padding: 12px; text-align: center;">Status</th>
                <th style="padding: 12px; width: 180px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $pengajuans->firstItem() + $index }}</td>
                    <td style="padding: 12px; font-weight: bold; font-family: monospace; color: #2563eb;">{{ $item->nomor_pengajuan }}</td>
                    <td style="padding: 12px; font-weight: 600; color: #1e293b;">{{ $item->nama_lengkap }}</td>
                    <td style="padding: 12px; color: #475569;">{{ $item->nik }}</td>
                    <td style="padding: 12px; color: #475569;">{{ $item->nama_usaha ?? '-' }}</td>
                    <td style="padding: 12px; color: #475569;">{{ $item->jenisRetribusi->nama ?? '-' }}</td>
                    <td style="padding: 12px; text-align: center;">
                        @php
                            $badgeStyles = [
                                'menunggu'   => 'background: #fef3c7; color: #92400e;',
                                'survey'     => 'background: #e0e7ff; color: #3730a3;',
                                'perbaikan'  => 'background: #ffedd5; color: #9a3412;',
                                'disetujui'  => 'background: #d1fae5; color: #065f46;',
                                'ditolak'    => 'background: #fee2e2; color: #991b1b;',
                            ];
                            $style = $badgeStyles[$item->status_pengajuan] ?? 'background: #f3f4f6; color: #374151;';
                        @endphp
                        <span style="{{ $style }} padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: capitalize; display: inline-block;">
                            {{ $item->status_pengajuan }}
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center; white-space: nowrap; font-size: 14px;">
                        <a href="{{ route('admin.pengajuan.show', $item) }}" style="color: #2563eb; text-decoration: none; margin-right: 12px; font-weight: 600; font-size: 14px;">
                            Lihat
                        </a>
                        @if(auth()->user()->hasPermission('pengajuan.update') || auth()->user()->hasPermission('pengajuan.verify'))
                        <a href="{{ route('admin.pengajuan.edit', $item) }}" style="color: #d97706; text-decoration: none; margin-right: 12px; font-weight: 600; font-size: 14px;">
                            Edit
                        </a>
                        @endif
                        @if(auth()->user()->hasPermission('pengajuan.delete'))
                        <form action="{{ route('admin.pengajuan.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 600; font-size: 14px; padding: 0; font-family: inherit;">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="padding: 20px; text-align: center; color: #6b7280;">
                        Belum ada data pengajuan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $pengajuans->links() }}
    </div>
</div>
@endsection
