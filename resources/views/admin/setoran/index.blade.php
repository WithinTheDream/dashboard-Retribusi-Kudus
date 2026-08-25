@extends('layouts.admin')

@section('title', 'Setoran Petugas - Retribusi Sampah Kudus')
@section('page-title', 'Kelola Setoran Petugas')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Setoran Petugas</h3>
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
                <th style="padding: 12px;">Nomor Setoran</th>
                <th style="padding: 12px;">Petugas Penyetor</th>
                <th style="padding: 12px;">Tanggal</th>
                <th style="padding: 12px;">Total (Rp)</th>
                <th style="padding: 12px;">Status</th>
                <th style="padding: 12px; width: 180px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($setorans as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $index + 1 }}</td>
                    <td style="padding: 12px; font-weight: bold;">{{ $item->nomor_setoran }}</td>
                    <td style="padding: 12px;">{{ $item->petugas->nama_lengkap ?? '-' }}</td>
                    <td style="padding: 12px;">{{ \Carbon\Carbon::parse($item->tanggal_setor)->format('d M Y') }}</td>
                    <td style="padding: 12px; color: #059669; font-weight: bold;">
                        Rp {{ number_format($item->total_setoran, 0, ',', '.') }}
                    </td>
                    <td style="padding: 12px;">
                        @if($item->status_setoran == 'diterima')
                            <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: capitalize;">{{ $item->status_setoran }}</span>
                        @elseif($item->status_setoran == 'ditolak')
                            <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: capitalize;">{{ $item->status_setoran }}</span>
                        @else
                            <span style="background: #fef3c7; color: #b45309; padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: capitalize;">{{ $item->status_setoran }}</span>
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('admin.setoran.show', $item) }}" style="color: #2563eb; text-decoration: none; margin-right: 12px; font-weight: 500;">Detail</a>
                        
                        @if($item->status_setoran == 'menunggu' && auth()->user()->hasPermission('setoran.update'))
                        <form action="{{ route('admin.setoran.verify', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Terima setoran uang sejumlah Rp {{ number_format($item->total_setoran, 0, ',', '.') }} dari {{ $item->petugas->nama_lengkap ?? "Petugas" }}?');">
                            @csrf
                            <input type="hidden" name="status_setoran" value="diterima">
                            <button type="submit" style="background: #10b981; border: none; color: white; cursor: pointer; font-weight: 500; font-size: 12px; padding: 5px 10px; border-radius: 4px;">Terima Uang</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 12px; text-align: center; color: #6b7280;">
                        Belum ada data setoran dari petugas lapangan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $setorans->links() }}
    </div>
</div>
@endsection
