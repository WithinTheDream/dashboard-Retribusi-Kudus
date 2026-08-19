@extends('layouts.admin')

@section('title', 'Jenis Retribusi')
@section('page-title', 'Jenis Retribusi')

@section('content')

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">
        <div>
            <h1>Jenis Retribusi</h1>
            <p style="color:#6b7280;">
                Kelola jenis objek retribusi.
            </p>
        </div>

        <a
            href="{{ route('admin.jenis-retribusi.create') }}"
            style="
                background:#2563eb;
                color:white;
                padding:10px 15px;
                text-decoration:none;
                border-radius:8px;
            "
        >
            + Tambah
        </a>
    </div>

    @if(session('success'))
        <div style="
            background:#dcfce7;
            padding:12px;
            margin-bottom:15px;
            border-radius:8px;
        ">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="
            background:#fee2e2;
            padding:12px;
            margin-bottom:15px;
            border-radius:8px;
        ">
            {{ session('error') }}
        </div>
    @endif

    <div style="
        background:white;
        border-radius:12px;
        overflow:hidden;
    ">

        <table width="100%" cellpadding="15">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($jenisRetribusi as $item)

                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $item->kode }}
                        </td>

                        <td>
                            {{ $item->nama }}
                        </td>

                        <td>

                            <a href="{{ route(
                                'admin.jenis-retribusi.edit',
                                $item
                            ) }}">
                                Edit
                            </a>

                            <form
                                action="{{ route(
                                    'admin.jenis-retribusi.destroy',
                                    $item
                                ) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm(
                                    'Hapus data ini?'
                                )"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit">
                                    Hapus
                                </button>
                            </form>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="4">
                            Belum ada data.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div style="margin-top:20px;">
        {{ $jenisRetribusi->links() }}
    </div>

@endsection