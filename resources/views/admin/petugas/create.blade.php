@extends('layouts.admin')

@section('title', 'Tambah Petugas Lapangan - Retribusi Sampah Kudus')
@section('page-title', 'Tambah Petugas & Penugasan')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 900px;">
    <div style="margin-bottom: 20px; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Form Tambah Petugas & Penugasan Wilayah</h3>
        <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">
            Daftarkan akun login petugas baru dan tentukan wilayah penugasan (Kecamatan & Desa) agar tagihan wilayah tersebut muncul di aplikasi petugas.
        </p>
    </div>

    <form action="{{ route('admin.petugas.store') }}" method="POST">
        @csrf

        <!-- BAGIAN 1: AKUN LOGIN -->
        <h4 style="font-size: 15px; font-weight: bold; color: #10b981; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
            <span>👤</span> 1. Informasi Akun Petugas
        </h4>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 5px; display: block;">Nama Lengkap Petugas *</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Budi Santoso" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                @error('nama_lengkap')<small style="color: #ef4444; font-size: 12px;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 5px; display: block;">Username Login *</label>
                <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: petugas_bae" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                @error('username')<small style="color: #ef4444; font-size: 12px;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 5px; display: block;">Email Petugas *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: petugas.bae@retribusikudus.test" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                @error('email')<small style="color: #ef4444; font-size: 12px;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 5px; display: block;">Nomor HP / WhatsApp *</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="Contoh: 081234567890" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                @error('no_hp')<small style="color: #ef4444; font-size: 12px;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="margin-bottom: 25px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 5px; display: block;">Password Login *</label>
            <input type="password" name="password" required placeholder="Minimal 6 karakter" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
            @error('password')<small style="color: #ef4444; font-size: 12px;">{{ $message }}</small>@enderror
        </div>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 25px 0;">

        <!-- BAGIAN 2: PENUGASAN WILAYAH -->
        <h4 style="font-size: 15px; font-weight: bold; color: #2563eb; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
            <span>📍</span> 2. Wilayah Penugasan Penagihan
        </h4>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 13px; color: #1e40af;">
            💡 <strong>Petunjuk:</strong> Petugas ini akan otomatis dapat melihat, mencari, dan menagih tagihan warga yang terdaftar di Kecamatan dan Desa yang Anda pilih di bawah ini pada aplikasi mobile.
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 5px; display: block;">Kecamatan Penugasan *</label>
                <select name="kecamatan_id" id="kecamatan_id" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: white;">
                    <option value="">-- Pilih Kecamatan --</option>
                    @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                            {{ $kecamatan->kecamatan }}
                        </option>
                    @endforeach
                </select>
                @error('kecamatan_id')<small style="color: #ef4444; font-size: 12px;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 5px; display: block;">Desa / Kelurahan Penugasan *</label>
                <select name="desa_id" id="desa_id" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: white;">
                    <option value="">-- Pilih Desa Terlebih Dahulu --</option>
                </select>
                @error('desa_id')<small style="color: #ef4444; font-size: 12px;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="margin-bottom: 25px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 5px; display: block;">
                RW Spesifik (Opsional)
            </label>
            <input type="text" name="rw" value="{{ old('rw') }}" maxlength="3" placeholder="Contoh: 001 (Kosongkan jika bertugas untuk 1 desa penuh)" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
            <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">
                Jika dikosongkan, petugas bertanggung jawab menagih seluruh RW di desa tersebut.
            </small>
            @error('rw')<small style="color: #ef4444; font-size: 12px;">{{ $message }}</small>@enderror
        </div>

        <div style="display: flex; gap: 10px; align-items: center; margin-top: 20px;">
            <button type="submit" style="background: #10b981; color: white; padding: 11px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px;">
                Simpan & Tugaskan Petugas
            </button>
            <a href="{{ route('admin.petugas.index') }}" style="color: #4b5563; text-decoration: none; font-size: 14px; padding: 10px 16px;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('kecamatan_id').addEventListener('change', function() {
        const kecamatanId = this.value;
        const desaSelect = document.getElementById('desa_id');

        desaSelect.innerHTML = '<option value="">Memuat daftar desa...</option>';

        if (!kecamatanId) {
            desaSelect.innerHTML = '<option value="">-- Pilih Desa Terlebih Dahulu --</option>';
            return;
        }

        fetch('{{ route("admin.api.desa-by-kecamatan", ":id") }}'.replace(':id', kecamatanId))
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Pilih Desa --</option>';
                data.forEach(desa => {
                    options += `<option value="${desa.id}">${desa.desa}</option>`;
                });
                desaSelect.innerHTML = options;
            })
            .catch(() => {
                desaSelect.innerHTML = '<option value="">Gagal memuat desa</option>';
            });
    });
</script>
@endpush
