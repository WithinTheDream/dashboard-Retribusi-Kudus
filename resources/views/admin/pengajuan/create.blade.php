@extends('layouts.admin')

@section('title', 'Tambah Pengajuan - Retribusi Sampah Kudus')
@section('page-title', 'Tambah Pengajuan Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 20px;">Form Tambah Pengajuan</h3>

    <form action="{{ route('admin.pengajuan.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nomor Pengajuan</label>
                <input type="text" name="nomor_pengajuan" value="{{ old('nomor_pengajuan') }}" required placeholder="Contoh: REG-2026-001" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('nomor_pengajuan')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Jenis Retribusi</label>
                <select name="jenis_retribusi_id" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                    <option value="">-- Pilih Jenis Retribusi --</option>
                    @foreach($jenisRetribusis as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_retribusi_id') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->kode }} - {{ $jenis->nama }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_retribusi_id')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nama Lengkap Pemohon</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('nama_lengkap')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">NIK</label>
                <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" required placeholder="16 digit NIK" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('nik')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nama Usaha (Opsional)</label>
                <input type="text" name="nama_usaha" value="{{ old('nama_usaha') }}" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('nama_usaha')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nomor HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="Contoh: 08123456789" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('no_hp')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Kecamatan</label>
                <select name="kecamatan_id" id="kecamatan_id" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                    <option value="">-- Pilih Kecamatan --</option>
                    @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                            {{ $kecamatan->kecamatan }}
                        </option>
                    @endforeach
                </select>
                @error('kecamatan_id')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Desa</label>
                <select name="desa_id" id="desa_id" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                    <option value="">-- Pilih Desa Terlebih Dahulu --</option>
                </select>
                @error('desa_id')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; margin-bottom: 5px; display: block;">Alamat Lengkap</label>
            <textarea name="alamat" required rows="3" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">{{ old('alamat') }}</textarea>
            @error('alamat')<small style="color: red;">{{ $message }}</small>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">RT</label>
                <input type="text" name="rt" value="{{ old('rt') }}" maxlength="3" required placeholder="Contoh: 001" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('rt')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">RW</label>
                <input type="text" name="rw" value="{{ old('rw') }}" maxlength="3" required placeholder="Contoh: 005" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('rw')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Latitude (Wajib Titik Koordinat)</label>
                <input type="number" step="any" name="lat" value="{{ old('lat') }}" required placeholder="Contoh: -6.8048123" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('lat')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Longitude (Wajib Titik Koordinat)</label>
                <input type="number" step="any" name="lokasi_long" value="{{ old('lokasi_long') }}" required placeholder="Contoh: 110.8405123" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('lokasi_long')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="font-weight: bold; margin-bottom: 5px; display: block;">Status Pengajuan</label>
            <select name="status_pengajuan" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @foreach(['menunggu', 'perbaikan', 'survey', 'ditolak', 'disetujui'] as $status)
                    <option value="{{ $status }}" {{ old('status_pengajuan', 'menunggu') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            @error('status_pengajuan')<small style="color: red;">{{ $message }}</small>@enderror
        </div>

        <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
            Simpan
        </button>
        <a href="{{ route('admin.pengajuan.index') }}" style="margin-left: 10px; color: #4b5563; text-decoration: none;">
            Batal
        </a>
    </form>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('kecamatan_id').addEventListener('change', function() {
        const kecamatanId = this.value;
        const desaSelect = document.getElementById('desa_id');

        desaSelect.innerHTML = '<option value="">Memuat desa...</option>';

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