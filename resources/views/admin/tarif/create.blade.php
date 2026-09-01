@extends('layouts.admin')

@section('title', 'Tambah Tarif - Retribusi Sampah Kudus')
@section('page-title', 'Tambah Tarif Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 650px;">
    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 20px;">Form Tambah Tarif</h3>

    <form action="{{ route('admin.tarif.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; margin-bottom: 5px; display: block; font-size: 14px;">Jenis Retribusi <span style="color:red;">*</span></label>
            <select
                name="jenis_retribusi_id"
                required
                style="display:block; width:100%; padding:10px; border: 1px solid #d1d5db; border-radius: 6px; background: white; font-size: 14px;"
            >
                <option value="">-- Pilih Jenis Retribusi --</option>
                @foreach($jenisRetribusis as $jenis)
                    <option value="{{ $jenis->id }}" {{ old('jenis_retribusi_id') == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->kode }} - {{ $jenis->nama }}
                    </option>
                @endforeach
            </select>

            @error('jenis_retribusi_id')
                <small style="color:red;">{{ $message }}</small>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; margin-bottom: 5px; display: block; font-size: 14px;">Nominal Tarif (Rp) <span style="color:red;">*</span></label>
            <input
                type="number"
                name="nominal"
                value="{{ old('nominal') }}"
                required
                placeholder="Contoh: 15000"
                min="0"
                style="display:block; width:100%; padding:10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
            >

            @error('nominal')
                <small style="color:red;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block; font-size: 14px;">Satuan Hitung</label>
                <input
                    type="text"
                    name="satuan"
                    value="{{ old('satuan', 'Bulan') }}"
                    placeholder="Contoh: Bulan, Hari, m3"
                    style="display:block; width:100%; padding:10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                >
                @error('satuan')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block; font-size: 14px;">Tahun Periode</label>
                <input
                    type="number"
                    name="periode"
                    value="{{ old('periode', date('Y')) }}"
                    min="2000"
                    max="2100"
                    style="display:block; width:100%; padding:10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                >
                @error('periode')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;">
                <input
                    type="checkbox"
                    name="is_aktif"
                    value="1"
                    {{ old('is_aktif', '1') == '1' ? 'checked' : '' }}
                    style="width: 16px; height: 16px;"
                >
                <span style="font-weight: 500; color: #1f2937;">Jadikan Tarif Aktif Saat Ini</span>
            </label>
        </div>

        <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 14px;">
            Simpan Tarif
        </button>

        <a href="{{ route('admin.tarif.index') }}" style="margin-left: 10px; color: #4b5563; text-decoration: none; font-size: 14px;">
            Batal
        </a>

    </form>
</div>
@endsection
