@extends('layouts.admin')

@section('title', 'Tambah Wilayah')
@section('page-title', 'Tambah Wilayah (Kecamatan)')

@section('content')

    <h1>Tambah Data Kecamatan</h1>

    <form
        action="{{ route('admin.wilayah.store') }}"
        method="POST"
        style="margin-top:20px;"
    >

        @csrf

        <div style="margin-bottom:15px;">
            <label>Nama Kecamatan</label>

            <input
                type="text"
                name="nama"
                value="{{ old('nama') }}"
                required
                placeholder="Contoh: Kudus, Bae, Jati..."
                style="display:block; width:100%; padding:10px; border: 1px solid #d1d5db; border-radius: 6px;"
            >

            @error('nama')
                <small style="color:red;">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">
            Simpan
        </button>

        <a href="{{ route('admin.wilayah.index') }}" style="margin-left: 10px; color: #4b5563; text-decoration: none;">
            Batal
        </a>

    </form>

@endsection
