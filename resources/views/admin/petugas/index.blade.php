@extends('layouts.admin')

@section('title', 'Kelola Petugas Lapangan - Retribusi Sampah Kudus')
@section('page-title', 'Kelola Petugas Lapangan')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Petugas & Penugasan Wilayah</h3>
            <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                Kelola akun petugas pemungut retribusi lapangan dan hubungkan dengan wilayah tugasnya (Kecamatan, Desa, RW).
            </p>
        </div>

        @if(auth()->user()->hasPermission('users.create'))
            <a href="{{ route('admin.petugas.create') }}" style="background: #10b981; color: white; padding: 9px 18px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;">
                <span>+</span> Tambah Petugas & Wilayah
            </a>
        @endif
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; border: 1px solid #a7f3d0;">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; color: #991b1b; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; border: 1px solid #fecaca;">
            ✕ {{ session('error') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; width: 50px;">No</th>
                    <th style="padding: 12px;">Petugas</th>
                    <th style="padding: 12px;">Kontak & Login</th>
                    <th style="padding: 12px;">Wilayah Penugasan</th>
                    <th style="padding: 12px; width: 140px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($petugasList as $index => $item)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; vertical-align: top;">{{ $petugasList->firstItem() + $index }}</td>
                        <td style="padding: 12px; vertical-align: top;">
                            <div style="font-weight: bold; color: #111827; font-size: 14px;">{{ $item->nama_lengkap }}</div>
                            <span style="background: #ecfdf5; color: #047857; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block; margin-top: 4px;">
                                Petugas Lapangan
                            </span>
                        </td>
                        <td style="padding: 12px; vertical-align: top; font-size: 13px;">
                            <div><strong>Username:</strong> <code style="background: #f3f4f6; padding: 2px 6px; border-radius: 4px;">{{ $item->username }}</code></div>
                            <div style="color: #4b5563; margin-top: 3px;"><strong>Email:</strong> {{ $item->email }}</div>
                            <div style="color: #4b5563; margin-top: 3px;"><strong>No HP:</strong> {{ $item->no_hp ?? '-' }}</div>
                        </td>
                        <td style="padding: 12px; vertical-align: top;">
                            @if($item->penugasanWilayahs && $item->penugasanWilayahs->count() > 0)
                                @foreach($item->penugasanWilayahs as $penugasan)
                                    <div style="margin-bottom: 4px;">
                                        <span style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; display: inline-block;">
                                            📍 Kec. {{ $penugasan->kecamatan?->kecamatan ?? '-' }} &bull; Desa {{ $penugasan->desa?->desa ?? '-' }}
                                            @if($penugasan->rw)
                                                (RW {{ $penugasan->rw }})
                                            @else
                                                <span style="color: #6b7280; font-size: 11px;">(Semua RW)</span>
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <span style="background: #fef2f2; color: #b91c1c; border: 1px dashed #f87171; padding: 4px 10px; border-radius: 6px; font-size: 12px; display: inline-block;">
                                    ⚠️ Belum Diberi Penugasan
                                </span>
                            @endif
                        </td>
                        <td style="padding: 12px; text-align: center; vertical-align: top;">
                            @if(auth()->user()->hasPermission('users.update'))
                                <a href="{{ route('admin.petugas.edit', $item) }}" style="color: #2563eb; text-decoration: none; margin-right: 12px; font-weight: 500; font-size: 13px;">
                                    Edit
                                </a>
                            @endif

                            @if(auth()->user()->hasPermission('users.delete'))
                                <form action="{{ route('admin.petugas.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus petugas ini beserta seluruh penugasan wilayahnya?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 500; font-size: 13px;">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 24px; text-align: center; color: #6b7280;">
                            Belum ada data petugas lapangan. Klik tombol "+ Tambah Petugas & Wilayah" untuk membuat baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $petugasList->links() }}
    </div>
</div>
@endsection
