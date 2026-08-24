@extends('layouts.admin')

@section('title', 'Tambah Pembayaran - Retribusi Sampah Kudus')
@section('page-title', 'Tambah Pembayaran Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 800px;">
    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin: 0 0 20px 0;">Form Tambah Pembayaran</h3>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pembayaran.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nomor Pembayaran</label>
                <input type="text" name="nomor_pembayaran" value="{{ old('nomor_pembayaran', 'BYR-' . date('YmdHis')) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 14px;">
            </div>

            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Nominal Bayar (Rp)</label>
                <input type="number" id="nominal_bayar" name="nominal_bayar" value="{{ old('nominal_bayar') }}" required placeholder="Contoh: 50000" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 14px;">
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Pilih Tagihan</label>
            <select name="tagihan_id" id="tagihan_select" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 14px;">
                <option value="">-- Pilih Tagihan Belum Lunas --</option>
                @foreach($tagihans as $tagihan)
                    @php
                        $nominal = $tagihan->total_tagihan ?? $tagihan->nominal_tagihan ?? $tagihan->nominal ?? 0;
                    @endphp
                    <option value="{{ $tagihan->id }}" data-nominal="{{ $nominal }}" {{ old('tagihan_id') == $tagihan->id ? 'selected' : '' }}>
                        {{ $tagihan->nomor_tagihan ?? 'TAG-' . $tagihan->id }} - {{ $tagihan->wajibRetribusi->nama_lengkap ?? 'Warga' }} (Rp {{ number_format($nominal, 0, ',', '.') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Metode Pembayaran</label>
                <select name="metode_pembayaran" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 14px;">
                    <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Waktu Bayar</label>
                <input type="datetime-local" name="waktu_bayar" value="{{ old('waktu_bayar', now()->format('Y-m-d\TH:i')) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 14px;">
            </div>
        </div>

        <div style="display: flex; gap: 12px; align-items: center;">
            <button type="submit" style="background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 14px; cursor: pointer;">
                Simpan
            </button>
            <a href="{{ route('admin.pembayaran.index') }}" style="color: #4b5563; text-decoration: none; font-size: 14px; font-weight: 500;">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    // Otomatis isi Nominal Bayar saat Tagihan dipilih
    document.getElementById('tagihan_select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const nominal = selectedOption.getAttribute('data-nominal');
        if (nominal) {
            document.getElementById('nominal_bayar').value = nominal;
        }
    });
</script>
@endsection
