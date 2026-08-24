@extends('layouts.admin')

@section('title', 'Edit Pembayaran - Retribusi Kudus')
@section('page-title', 'Edit Data Pembayaran')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 800px;">
    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin: 0 0 20px 0;">Edit Data Pembayaran</h3>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pembayaran.update', $pembayaran) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nomor Pembayaran</label>
                <input type="text" name="nomor_pembayaran" value="{{ old('nomor_pembayaran', $pembayaran->nomor_pembayaran) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 14px;">
            </div>

            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nominal Bayar (Rp)</label>
                <input type="number" name="nominal_bayar" value="{{ old('nominal_bayar', $pembayaran->nominal_bayar) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 14px;">
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Tagihan</label>
            <select name="tagihan_id" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 14px;">
                @foreach($tagihans as $tagihan)
                    <option value="{{ $tagihan->id }}" {{ old('tagihan_id', $pembayaran->tagihan_id) == $tagihan->id ? 'selected' : '' }}>
                        {{ $tagihan->nomor_tagihan ?? 'TAG-' . $tagihan->id }} - {{ $tagihan->wajibRetribusi->nama_lengkap ?? 'Warga' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Metode Pembayaran</label>
                <select name="metode_pembayaran" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 14px;">
                    <option value="tunai" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="qris" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Waktu Bayar</label>
                @php
                    $formattedDate = $pembayaran->waktu_bayar ? \Carbon\Carbon::parse($pembayaran->waktu_bayar)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i');
                @endphp
                <input type="datetime-local" name="waktu_bayar" value="{{ old('waktu_bayar', $formattedDate) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 14px;">
            </div>
        </div>

        <div style="display: flex; gap: 12px; align-items: center;">
            <button type="submit" style="background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 14px; cursor: pointer;">
                Perbarui
            </button>
            <a href="{{ route('admin.pembayaran.index') }}" style="color: #4b5563; text-decoration: none; font-size: 14px; font-weight: 500;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
