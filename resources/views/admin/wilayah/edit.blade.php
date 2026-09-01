@extends('layouts.admin')

@section('title', 'Edit Kecamatan & Kelola Desa - Retribusi Sampah Kudus')
@section('page-title', 'Edit Kecamatan & Kelola Desa')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: start;">

    <!-- Form Edit Kecamatan -->
    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 20px;">Edit Nama Kecamatan</h3>

        <form action="{{ route('admin.wilayah.update', $kecamatan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nama Kecamatan</label>
                <input type="text" name="kecamatan" value="{{ old('kecamatan', $kecamatan->kecamatan) }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('kecamatan')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                Update Kecamatan
            </button>
            <a href="{{ route('admin.wilayah.index') }}" style="margin-left: 10px; color: #4b5563; text-decoration: none;">
                Kembali
            </a>
        </form>
    </div>

    <!-- Kelola Daftar Desa / Kelurahan -->
    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">
            Daftar Desa di Kecamatan {{ $kecamatan->kecamatan }}
        </h3>

        <!-- Form Tambah Desa -->
        @if(auth()->user()->hasPermission('wilayah.create'))
        <form action="{{ route('admin.wilayah.desa.store', $kecamatan->id) }}" method="POST" style="display: flex; gap: 8px; margin-bottom: 20px;">
            @csrf
            <input type="text" name="desa" placeholder="Nama Desa / Kelurahan baru" required style="flex: 1; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
            <button type="submit" style="background: #10b981; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                + Tambah
            </button>
        </form>
        @endif

        <!-- List Desa -->
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 10px; width: 40px;">No</th>
                    <th style="padding: 10px;">Nama Desa / Kelurahan</th>
                    <th style="padding: 10px; width: 80px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kecamatan->desas as $idx => $d)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 10px;">{{ $idx + 1 }}</td>
                        <td style="padding: 10px; font-weight: 500;">{{ $d->desa }}</td>
                        <td style="padding: 10px; text-align: center;">
                            @if(auth()->user()->hasPermission('wilayah.delete'))
                            <form action="{{ route('admin.wilayah.desa.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Hapus desa {{ $d->desa }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px; font-weight: 600;">Hapus</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding: 15px; text-align: center; color: #6b7280;">Belum ada desa terdaftar pada kecamatan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
