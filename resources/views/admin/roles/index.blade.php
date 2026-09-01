@extends('layouts.admin')

@section('title', 'Manajemen Role & Hak Akses')
@section('page-title', 'Kelola Role & Hak Akses')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Role Sistem</h3>

        @if(auth()->user()->hasPermission('roles.update'))
        <a href="{{ route('admin.roles.create') }}" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
            + Tambah Role Baru
        </a>
        @endif
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
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
                    <td style="padding: 12px; font-weight: bold; color: #1e293b;">
                        {{ $item->display_name }}
                        @if($item->is_system)
                        <span style="background: #e2e8f0; color: #475569; font-size: 11px; padding: 2px 8px; border-radius: 12px; margin-left: 5px; font-weight: 600;">Sistem</span>
                        @endif
                    </td>
                    <td style="padding: 12px; color: #64748b;"><code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">{{ $item->name }}</code></td>
                    <td style="padding: 12px; color: #334155;">{{ $item->users_count }} User</td>
                    <td style="padding: 12px; color: #334155;">{{ $item->permissions_count }} Akses</td>
                    <td style="padding: 12px; text-align: center;">
                        @if(auth()->user()->hasPermission('roles.update'))
                        <a href="{{ route('admin.roles.edit', $item->id) }}" style="color: #d97706; text-decoration: none; margin-right: 12px; font-weight: 600;">Edit Akses</a>
                        
                        @if(!$item->is_system)
                        <form action="{{ route('admin.roles.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus role ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 600; font-size: 14px;">Hapus</button>
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
