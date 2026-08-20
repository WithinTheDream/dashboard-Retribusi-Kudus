@extends('layouts.admin')

@section('title', 'Tagihan - Retribusi Sampah Kudus')
@section('page-title', 'Kelola Tagihan Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Tagihan Retribusi</h3>

        <a href="{{ route('admin.tagihan.create') }}" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
            + Tambah
        </a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; color: #991b1b; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
            {{ session('error') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
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
                    <td style="padding: 12px; font-weight: bold;">{{ $item->nomor_tagihan }}</td>
                    <td style="padding: 12px;">{{ $item->wajibRetribusi->nama_lengkap ?? '-' }}</td>
                    <td style="padding: 12px;">{{ $item->bulan }}/{{ $item->tahun }}</td>
                    <td style="padding: 12px; color: #059669; font-weight: bold;">
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                    <td style="padding: 12px;">
                        @if($item->status == 'lunas')
                            <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: capitalize;">{{ $item->status }}</span>
                        @elseif($item->status == 'dibatalkan')
                            <span style="background: #f3f4f6; color: #4b5563; padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: capitalize;">{{ $item->status }}</span>
                        @else
                            <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: capitalize;">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('admin.tagihan.edit', $item) }}" style="color: #d97706; text-decoration: none; margin-right: 12px; font-weight: 500;">
                            Edit
                        </a>

                        <form action="{{ route('admin.tagihan.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tagihan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 500; font-size: 14px;">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 12px; text-align: center; color: #6b7280;">
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