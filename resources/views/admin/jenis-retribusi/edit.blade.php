@extends('layouts.admin')

@section('title', 'Edit Jenis Retribusi')
@section('page-title', 'Edit Jenis Retribusi')

@section('content')

    <h1>Edit Jenis Retribusi</h1>

    <form
        action="{{ route(
            'admin.jenis-retribusi.update',
            $jenisRetribusi
        ) }}"
        method="POST"
        style="margin-top:20px;"
    >

        @csrf
        @method('PUT')

        <div style="margin-bottom:15px;">
            <label>Kode</label>

            <input
                type="text"
                name="kode"
                value="{{ old(
                    'kode',
                    $jenisRetribusi->kode
                ) }}"
                required
                style="display:block; width:100%; padding:10px;"
            >

            @error('kode')
                <small style="color:red;">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div style="margin-bottom:15px;">
            <label>Nama</label>

            <input
                type="text"
                name="nama"
                value="{{ old(
                    'nama',
                    $jenisRetribusi->nama
                ) }}"
                required
                style="display:block; width:100%; padding:10px;"
            >

            @error('nama')
                <small style="color:red;">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <button type="submit">
            Update
        </button>

        <a href="{{ route('admin.jenis-retribusi.index') }}">
            Batal
        </a>

    </form>

@endsection