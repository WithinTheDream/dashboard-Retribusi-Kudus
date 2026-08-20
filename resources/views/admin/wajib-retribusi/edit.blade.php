@extends('layouts.admin')

@section('title', 'Edit Wajib Retribusi - Retribusi Sampah Kudus')
@section('page-title', 'Edit Wajib Retribusi')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 20px;">Form Edit Wajib Retribusi</h3>

    <form action="{{ route('admin.wajib-retribusi.update', $wajibRetribusi) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Kode Wajib Retribusi</label>
                <input type="text" name="kode" value="{{ old('kode', $wajibRetribusi->kode) }}" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('kode')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Jenis Retribusi</label>
                <select name="jenis_retribusi_id" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                    <option value="">-- Pilih Jenis Retribusi --</option>
                    @foreach($jenisRetribusis as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_retribusi_id', $wajibRetribusi->jenis_retribusi_id) == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->kode }} - {{ $jenis->nama }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_retribusi_id')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $wajibRetribusi->nama_lengkap) }}" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('nama_lengkap')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">NIK</label>
                <input type="text" name="nik" value="{{ old('nik', $wajibRetribusi->nik) }}" maxlength="16" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('nik')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nama Usaha (Opsional)</label>
                <input type="text" name="nama_usaha" value="{{ old('nama_usaha', $wajibRetribusi->nama_usaha) }}" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('nama_usaha')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nomor HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $wajibRetribusi->no_hp) }}" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('no_hp')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Kecamatan</label>
                <select name="kecamatan_id" id="kecamatan_id" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                    <option value="">-- Pilih Kecamatan --</option>
                    @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $wajibRetribusi->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>
                            {{ $kecamatan->kecamatan }}
                        </option>
                    @endforeach
                </select>
                @error('kecamatan_id')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">Desa</label>
                <select name="desa_id" id="desa_id" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                    <option value="">-- Pilih Desa --</option>
                    @foreach($desas as $desa)
                        <option value="{{ $desa->id }}" {{ old('desa_id', $wajibRetribusi->desa_id) == $desa->id ? 'selected' : '' }}>
                            {{ $desa->desa }}
                        </option>
                    @endforeach
                </select>
                @error('desa_id')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; margin-bottom: 5px; display: block;">Alamat Lengkap</label>
            <textarea name="alamat" required rows="3" style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">{{ old('alamat', $wajibRetribusi->alamat) }}</textarea>
            @error('alamat')<small style="color: red;">{{ $message }}</small>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">RT</label>
                <input type="text" name="rt" value="{{ old('rt', $wajibRetribusi->rt) }}" maxlength="3" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('rt')<small style="color: red;">{{ $message }}</small>@enderror
            </div>

            <div>
                <label style="font-weight: bold; margin-bottom: 5px; display: block;">RW</label>
                <input type="text" name="rw" value="{{ old('rw', $wajibRetribusi->rw) }}" maxlength="3" required style="display: block; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('rw')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="hidden" name="status_aktif" value="0">
                <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', $wajibRetribusi->status_aktif) ? 'checked' : '' }}>
                <span style="font-weight: bold;">Aktif</span>
            </label>
        </div>

        <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
            Update
        </button>
        <a href="{{ route('admin.wajib-retribusi.index') }}" style="margin-left: 10px; color: #4b5563; text-decoration: none;">
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
            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
            return;
        }

        fetch('{{ route("admin.api.desa-by-kecamatan", ":id") }}'.replace(':id', kecamatanId))
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Pilih Desa --</option>';
                data.forEach(desa => {
                    const selected = desa.id == {{ old('desa_id', $wajibRetribusi->desa_id) }} ? 'selected' : '';
                    options += `<option value="${desa.id}" ${selected}>${desa.desa}</option>`;
                });
                desaSelect.innerHTML = options;
            })
            .catch(() => {
                desaSelect.innerHTML = '<option value="">Gagal memuat desa</option>';
            });
    });
</script>
@endpush
