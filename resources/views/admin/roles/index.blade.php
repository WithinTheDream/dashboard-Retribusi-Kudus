@extends('layouts.admin')

@section('title', 'Manajemen Role & Hak Akses')
@section('page-title', 'Kelola Role & Hak Akses')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Role Sistem</h3>

        @if(auth()->user()->hasPermission('roles.update'))
        <a href="{{ route('admin.roles.create') }}" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
            + Tambah Role Baru
        </a>
        @endif
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
                <th style="padding: 12px;">Nama Tampilan</th>
                <th style="padding: 12px;">Kode Internal</th>
                <th style="padding: 12px;">Jumlah Pengguna</th>
                <th style="padding: 12px;">Jumlah Hak Akses</th>
                <th style="padding: 12px; width: 160px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $index + 1 }}</td>
                    <td style="padding: 12px; font-weight: bold;">
                        {{ $item->display_name }}
                        @if($item->is_system)
                        <span style="background: #e5e7eb; color: #4b5563; font-size: 11px; padding: 2px 6px; border-radius: 4px; margin-left: 5px;">Sistem</span>
                        @endif
                    </td>
                    <td style="padding: 12px; color: #6b7280;">{{ $item->name }}</td>
                    <td style="padding: 12px;">{{ $item->users_count }} User</td>
                    <td style="padding: 12px;">{{ $item->permissions_count }} Akses</td>
                    <td style="padding: 12px; text-align: center;">
                        @if(auth()->user()->hasPermission('roles.update'))
                        <a href="{{ route('admin.roles.edit', $item->id) }}" style="color: #d97706; text-decoration: none; margin-right: 12px; font-weight: 500;">Edit Akses</a>
                        
                        @if(!$item->is_system)
                        <form action="{{ route('admin.roles.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus role ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 500; font-size: 14px;">Hapus</button>
                        </form>
                        @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
