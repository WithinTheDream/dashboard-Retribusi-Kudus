@extends('layouts.admin')

@section('title', 'Edit Kecamatan - Retribusi Sampah Kudus')
@section('page-title', 'Edit Kecamatan')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 600px;">
    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 20px;">Form Edit Kecamatan</h3>

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
            Update
        </button>
        <a href="{{ route('admin.wilayah.index') }}" style="margin-left: 10px; color: #4b5563; text-decoration: none;">
            Batal
        </a>
    </form>
</div>
@endsection
