@extends('layouts.admin')

@section('title', 'Edit Tagihan - Retribusi Sampah Kudus')
@section('page-title', 'Edit Tagihan Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 20px;">Form Edit Tagihan</h3>

    <form action="{{ route('admin.tagihan.update', $tagihan) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nomor Tagihan</label>
                <input type="text" name="nomor_tagihan" value="{{ old('nomor_tagihan', $tagihan->nomor_tagihan) }}" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('nomor_tagihan')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Wajib Retribusi</label>
                <select name="wajib_retribusi_id" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                    <option value="">-- Pilih Wajib Retribusi --</option>
                    @foreach($wajibRetribusis as $wajib)
                        <option value="{{ $wajib->id }}" {{ old('wajib_retribusi_id', $tagihan->wajib_retribusi_id) == $wajib->id ? 'selected' : '' }}>
                            {{ $wajib->kode }} - {{ $wajib->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
                @error('wajib_retribusi_id')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Bulan</label>
                <input type="number" name="bulan" min="1" max="12" value="{{ old('bulan', $tagihan->bulan) }}" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('bulan')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Tahun</label>
                <input type="number" name="tahun" min="2020" max="2099" value="{{ old('tahun', $tagihan->tahun) }}" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('tahun')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nominal Tagihan (Rp)</label>
                <input type="number" name="nominal" value="{{ old('nominal', $tagihan->nominal) }}" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('nominal')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Status</label>
                <select name="status" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                    @foreach(['belum_bayar', 'lunas', 'dibatalkan'] as $status)
                        <option value="{{ $status }}" {{ old('status', $tagihan->status) == $status ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
                @error('status')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
            Update
        </button>
        <a href="{{ route('admin.tagihan.index') }}" style="margin-left: 10px; color: #4b5563; text-decoration: none;">
            Batal
        </a>
    </form>
</div>
@endsection