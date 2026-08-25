@extends('layouts.admin')

@section('title', 'Tambah Role Baru - Retribusi Sampah Kudus')
@section('page-title', 'Tambah Role Baru')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Nama Tampilan (Display Name) <span style="color:red;">*</span></label>
                <input type="text" name="display_name" value="{{ old('display_name') }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;" placeholder="Contoh: Petugas Penagih Lapangan">
                @error('display_name') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Kode Internal (Unique) <span style="color:red;">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;" placeholder="Contoh: petugas_lapangan">
                @error('name') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">Deskripsi</label>
            <textarea name="description" rows="2" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">{{ old('description') }}</textarea>
            @error('description') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <h4 style="font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e5e7eb;">Pengaturan Hak Akses (Permissions)</h4>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
            @foreach($permissions as $module => $modulePermissions)
                <div style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 15px; background: #f9fafb;">
                    <h5 style="font-size: 14px; font-weight: bold; margin-bottom: 10px; text-transform: uppercase; color: #4b5563;">Modul: {{ $module }}</h5>
                    
                    @foreach($modulePermissions as $permission)
                        <label style="display: flex; align-items: center; margin-bottom: 8px; cursor: pointer;">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                style="margin-right: 8px; width: 16px; height: 16px;">
                            <span style="font-size: 14px;">{{ $permission->display_name }}</span>
                        </label>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 10px;">
            <a href="{{ route('admin.roles.index') }}" style="background: #f3f4f6; color: #374151; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 500;">Batal</a>
            <button type="submit" style="background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 500; cursor: pointer;">Simpan Role Baru</button>
        </div>
    </form>
</div>
@endsection
