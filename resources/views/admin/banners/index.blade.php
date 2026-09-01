@extends('layouts.admin')

@section('title', 'Kelola Banner - Retribusi Sampah Kudus')
@section('page-title', 'Kelola Banner Slideshow')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Banner Beranda Mobile</h3>

        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('banner.create'))
        <a href="{{ route('admin.banners.create') }}" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
            + Tambah Banner
        </a>
        @endif
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
        <thead>
            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                <th style="padding: 12px; width: 50px;">No</th>
                <th style="padding: 12px; width: 140px;">Preview</th>
                <th style="padding: 12px;">Judul & Deskripsi</th>
                <th style="padding: 12px; width: 80px;">Urutan</th>
                <th style="padding: 12px; width: 100px;">Status</th>
                <th style="padding: 12px; width: 140px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($banners as $index => $banner)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $banners->firstItem() + $index }}</td>
                    <td style="padding: 12px;">
                        <img src="{{ $banner->gambar_url }}" alt="{{ $banner->judul }}" style="width: 120px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb;">
                    </td>
                    <td style="padding: 12px;">
                        <div style="font-weight: bold; color: #111827; font-size: 14px;">{{ $banner->judul }}</div>
                        @if($banner->deskripsi)
                            <div style="color: #6b7280; font-size: 12px; margin-top: 2px;">{{ Str::limit($banner->deskripsi, 60) }}</div>
                        @endif
                    </td>
                    <td style="padding: 12px; font-weight: 600;">{{ $banner->urutan }}</td>
                    <td style="padding: 12px;">
                        @if($banner->is_active)
                            <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">Aktif</span>
                        @else
                            <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">Nonaktif</span>
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('banner.update'))
                            <a href="{{ route('admin.banners.edit', $banner) }}" style="color: #d97706; text-decoration: none; margin-right: 12px; font-weight: 600; font-size: 14px;">
                                Edit
                            </a>
                        @endif

                        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('banner.delete'))
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 600; font-size: 14px;">
                                    Hapus
                                </button>
                            </form>
                        @endif

                        @if(!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('banner.update') && !auth()->user()->hasPermission('banner.delete'))
                            <span style="color: #9ca3af;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 20px; text-align: center; color: #6b7280;">
                        Belum ada banner slideshow yang diunggah.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $banners->links() }}
    </div>
</div>
@endsection
