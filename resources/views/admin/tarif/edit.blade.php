@extends('layouts.admin')

@section('title', 'Edit Tarif - Retribusi Sampah Kudus')
@section('page-title', 'Edit Tarif Retribusi')

@section('content')

    <h1>Edit Data Tarif</h1>

    <form
        action="{{ route('admin.tarif.update', $tarif) }}"
        method="POST"
        style="margin-top:20px;"
    >
        @csrf
        @method('PUT')

        <div style="margin-bottom:15px;">
            <label style="font-weight: bold; margin-bottom: 5px; display: block;">Jenis Retribusi</label>
            <select
                name="jenis_retribusi_id"
                required
                style="display:block; width:100%; padding:10px; border: 1px solid #d1d5db; border-radius: 6px; background: white;"
            >
                <option value="">-- Pilih Jenis Retribusi --</option>
                @foreach($jenisRetribusis as $jenis)
                    <option value="{{ $jenis->id }}" {{ old('jenis_retribusi_id', $tarif->jenis_retribusi_id) == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->kode }} - {{ $jenis->nama }}
                    </option>
                @endforeach
            </select>

            @error('jenis_retribusi_id')
                <small style="color:red;">{{ $message }}</small>
            @enderror
        </div>

        <div style="margin-bottom:15px;">
            <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nominal Tarif (Rp)</label>
            <input
                type="number"
                name="nominal"
                value="{{ old('nominal', $tarif->nominal) }}"
                required
                style="display:block; width:100%; padding:10px; border: 1px solid #d1d5db; border-radius: 6px;"
            >

            @error('nominal')
                <small style="color:red;">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">
            Update
        </button>

        <a href="{{ route('admin.tarif.index') }}" style="margin-left: 10px; color: #4b5563; text-decoration: none;">
            Batal
        </a>

    </form>

@endsection
