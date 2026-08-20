@extends('layouts.admin')

@section('title', 'Edit Pembayaran - Retribusi Sampah Kudus')
@section('page-title', 'Edit Pembayaran Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 20px;">Form Edit Pembayaran</h3>

    <form action="{{ route('admin.pembayaran.update', $pembayaran) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nomor Pembayaran</label>
                <input type="text" name="nomor_pembayaran" value="{{ old('nomor_pembayaran', $pembayaran->nomor_pembayaran) }}" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('nomor_pembayaran')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nominal Bayar (Rp)</label>
                <input type="number" name="nominal_bayar" value="{{ old('nominal_bayar', $pembayaran->nominal_bayar) }}" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('nominal_bayar')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; margin-bottom: 5px; display: block;">Tagihan</label>
            <select name="tagihan_id" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                <option value="">-- Pilih Tagihan --</option>
                @foreach($tagihans as $tagihan)
                    <option value="{{ $tagihan->id }}" {{ old('tagihan_id', $pembayaran->tagihan_id) == $tagihan->id ? 'selected' : '' }}>
                        {{ $tagihan->nomor_tagihan }} - {{ $tagihan->wajibRetribusi->nama_lengkap ?? '-' }}
                    </option>
                @endforeach
            </select>
            @error('tagihan_id')<small style="color: red;">{{ $message }}</small>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Metode Pembayaran</label>
                <select name="metode_pembayaran" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                    @foreach(['tunai', 'qris', 'transfer'] as $metode)
                        <option value="{{ $metode }}" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == $metode ? 'selected' : '' }}>
                            {{ ucfirst($metode) }}
                        </option>
                    @endforeach
                </select>
                @error('metode_pembayaran')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Waktu Bayar</label>
                <input type="datetime-local" name="waktu_bayar" value="{{ old('waktu_bayar', $pembayaran->waktu_bayar ? $pembayaran->waktu_bayar->format('Y-m-d\TH:i') : '') }}" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('waktu_bayar')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
            Update
        </button>
        <a href="{{ route('admin.pembayaran.index') }}" style="margin-left: 10px; color: #4b5563; text-decoration: none;">
            Batal
        </a>
    </form>
</div>
@endsection