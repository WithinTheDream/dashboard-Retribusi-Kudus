@extends('layouts.admin')

@section('title', 'Tagihan - Retribusi Sampah Kudus')
@section('page-title', 'Kelola Tagihan Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Tagihan Retribusi</h3>
        
        <div>
            @if(auth()->user()->hasPermission('tagihan.create'))
            <form action="{{ route('admin.tagihan.generate') }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin meng-generate tagihan bulan ini untuk semua pelanggan aktif?');">
                @csrf
                <button type="submit" style="background: #10b981; color: white; padding: 8px 16px; border-radius: 6px; border: none; font-size: 14px; cursor: pointer; margin-right: 10px; font-weight: 500;">
                    Generate Tagihan Bulanan
                </button>
            </form>

            <a href="{{ route('admin.tagihan.create') }}" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
                + Tambah
            </a>
            @endif
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
        <thead>
            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                <th style="padding: 12px; width: 60px;">No</th>
                <th style="padding: 12px;">Nomor Tagihan</th>
                <th style="padding: 12px;">Wajib Retribusi</th>
                <th style="padding: 12px;">Periode</th>
                <th style="padding: 12px;">Nominal (Rp)</th>
                <th style="padding: 12px;">Status</th>
                <th style="padding: 12px; width: 160px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tagihans as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $index + 1 }}</td>
                    <td style="padding: 12px; font-weight: bold; color: #1e293b;">{{ $item->nomor_tagihan }}</td>
                    <td style="padding: 12px;">{{ $item->wajibRetribusi->nama_lengkap ?? '-' }}</td>
                    <td style="padding: 12px; color: #475569;">{{ $item->bulan }}/{{ $item->tahun }}</td>
                    <td style="padding: 12px; color: #059669; font-weight: bold;">
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                    <td style="padding: 12px;">
                        @if($item->status == 'lunas')
                            <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 12px; font-size: 12px; text-transform: capitalize; font-weight: 600;">{{ $item->status }}</span>
                        @elseif($item->status == 'dibatalkan')
                            <span style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 12px; font-size: 12px; text-transform: capitalize; font-weight: 600;">{{ $item->status }}</span>
                        @else
                            <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 12px; font-size: 12px; text-transform: capitalize; font-weight: 600;">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        @if(auth()->user()->hasPermission('tagihan.update'))
                        <a href="{{ route('admin.tagihan.edit', $item) }}" style="color: #d97706; text-decoration: none; margin-right: 12px; font-weight: 600;">
                            Edit
                        </a>
                        @endif

                        @if(auth()->user()->hasPermission('tagihan.delete'))
                        <form action="{{ route('admin.tagihan.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tagihan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 600; font-size: 14px;">
                                Hapus
                            </button>
                        </form>
                        @endif

                        @if(!auth()->user()->hasPermission('tagihan.update') && !auth()->user()->hasPermission('tagihan.delete'))
                            <span style="color: #9ca3af;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 20px; text-align: center; color: #6b7280;">
                        Belum ada data tagihan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $tagihans->links() }}
    </div>
</div>
@endsection