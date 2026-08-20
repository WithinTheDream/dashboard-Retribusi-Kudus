@extends('layouts.admin')

@section('title', 'Pengguna - Retribusi Sampah Kudus')
@section('page-title', 'Kelola Pengguna')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Pengguna</h3>

        <a href="{{ route('admin.pengguna.create') }}" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
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
                <th style="padding: 12px;">Nama Lengkap</th>
                <th style="padding: 12px;">Username</th>
                <th style="padding: 12px;">Email</th>
                <th style="padding: 12px;">No HP</th>
                <th style="padding: 12px;">Role</th>
                <th style="padding: 12px; width: 160px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penggunas as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $index + 1 }}</td>
                    <td style="padding: 12px; font-weight: bold;">{{ $item->nama_lengkap }}</td>
                    <td style="padding: 12px;">{{ $item->username }}</td>
                    <td style="padding: 12px;">{{ $item->email }}</td>
                    <td style="padding: 12px;">{{ $item->no_hp }}</td>
                    <td style="padding: 12px;">
                        <span style="background: #e0e7ff; color: #3730a3; padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: capitalize;">
                            {{ $item->role }}
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('admin.pengguna.edit', $item) }}" style="color: #d97706; text-decoration: none; margin-right: 12px; font-weight: 500;">
                            Edit
                        </a>

                        <form action="{{ route('admin.pengguna.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
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
                        Belum ada data pengguna.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $penggunas->links() }}
    </div>
</div>
@endsection