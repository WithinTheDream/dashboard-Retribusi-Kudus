@extends('layouts.admin')

@section('title', 'Tambah Banner - Retribusi Sampah Kudus')
@section('page-title', 'Tambah Banner Slideshow')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 700px;">
    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 20px;">Form Tambah Banner Mobile</h3>

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; margin-bottom: 5px; display: block;">Judul Banner</label>
            <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Jadwal Pengambilan Sampah Baru" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
            @error('judul')<small style="color: red;">{{ $message }}</small>@enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; margin-bottom: 5px; display: block;">Deskripsi / Pesan Singkat (Opsional)</label>
            <textarea name="deskripsi" rows="3" placeholder="Deskripsi informasi banner..." style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')<small style="color: red;">{{ $message }}</small>@enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; margin-bottom: 5px; display: block;">Gambar Banner (Disarankan Rasio 16:9 atau 2:1, Maks 2MB)</label>
            <input type="file" name="gambar" accept="image/*" required style="display: block; width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;">
            @error('gambar')<small style="color: red;">{{ $message }}</small>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Urutan Tampilan</label>
                <input type="number" name="urutan" value="{{ old('urutan', 0) }}" min="0" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('urutan')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div style="display: flex; align-items: center; padding-top: 25px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 500;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 18px; height: 18px;">
                    Aktifkan Banner Ini
                </label>
            </div>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                Simpan Banner
            </button>
            <a href="{{ route('admin.banners.index') }}" style="color: #4b5563; padding: 10px 16px; text-decoration: none; border: 1px solid #d1d5db; border-radius: 6px;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
