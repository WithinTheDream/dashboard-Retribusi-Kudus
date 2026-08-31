@extends('layouts.admin')

@section('title', 'Setoran Petugas - Retribusi Sampah Kudus')
@section('page-title', 'Kelola Setoran Petugas')

@section('content')
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h3 style="font-size: 18px; font-weight: bold; color: #1f2937;">Daftar Setoran Tunai Petugas</h3>
            <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Penerimaan dan verifikasi uang fisik retribusi dari petugas lapangan ke bendahara</p>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; border: 1px solid #a7f3d0;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <!-- Filter Tabs Status Setoran -->
    <div style="display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; flex-wrap: wrap;">
        <a href="{{ route('admin.setoran.index') }}" 
           style="padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; {{ empty($status) ? 'background: #2563eb; color: white;' : 'background: #f3f4f6; color: #4b5563;' }}">
            Semua
            <span style="background: {{ empty($status) ? 'rgba(255,255,255,0.3)' : '#e5e7eb' }}; padding: 2px 8px; border-radius: 12px; font-size: 11px;">
                {{ $counts['all'] ?? 0 }}
            </span>
        </a>

        <a href="{{ route('admin.setoran.index', ['status' => 'menunggu']) }}" 
           style="padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; {{ $status === 'menunggu' ? 'background: #d97706; color: white;' : 'background: #fef3c7; color: #b45309;' }}">
            ⏳ Menunggu Verifikasi
            <span style="background: {{ $status === 'menunggu' ? 'rgba(255,255,255,0.3)' : '#fde68a' }}; padding: 2px 8px; border-radius: 12px; font-size: 11px;">
                {{ $counts['menunggu'] ?? 0 }}
            </span>
        </a>

        <a href="{{ route('admin.setoran.index', ['status' => 'diterima']) }}" 
           style="padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; {{ $status === 'diterima' ? 'background: #059669; color: white;' : 'background: #d1fae5; color: #065f46;' }}">
            ✓ Diterima (Masuk Kas)
            <span style="background: {{ $status === 'diterima' ? 'rgba(255,255,255,0.3)' : '#a7f3d0' }}; padding: 2px 8px; border-radius: 12px; font-size: 11px;">
                {{ $counts['diterima'] ?? 0 }}
            </span>
        </a>

        <a href="{{ route('admin.setoran.index', ['status' => 'ditolak']) }}" 
           style="padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; {{ $status === 'ditolak' ? 'background: #dc2626; color: white;' : 'background: #fee2e2; color: #991b1b;' }}">
            ✕ Ditolak
            <span style="background: {{ $status === 'ditolak' ? 'rgba(255,255,255,0.3)' : '#fecaca' }}; padding: 2px 8px; border-radius: 12px; font-size: 11px;">
                {{ $counts['ditolak'] ?? 0 }}
            </span>
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; width: 50px;">No</th>
                    <th style="padding: 12px;">Nomor Setoran</th>
                    <th style="padding: 12px;">Petugas Penyetor</th>
                    <th style="padding: 12px;">Tanggal Setor</th>
                    <th style="padding: 12px;">Total Setoran</th>
                    <th style="padding: 12px;">Status & Verifikator</th>
                    <th style="padding: 12px; width: 220px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($setorans as $index => $item)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; vertical-align: top;">{{ $setorans->firstItem() + $index }}</td>
                        <td style="padding: 12px; vertical-align: top;">
                            <div style="font-weight: bold; color: #111827;">{{ $item->nomor_setoran }}</div>
                        </td>
                        <td style="padding: 12px; vertical-align: top;">
                            <div style="font-weight: 600; color: #1f2937;">{{ $item->petugas->nama_lengkap ?? '-' }}</div>
                            <small style="color: #6b7280;">{{ $item->petugas->no_hp ?? '' }}</small>
                        </td>
                        <td style="padding: 12px; vertical-align: top; color: #4b5563;">
                            {{ \Carbon\Carbon::parse($item->tanggal_setor)->format('d M Y') }}
                        </td>
                        <td style="padding: 12px; vertical-align: top; color: #059669; font-weight: bold; font-size: 15px;">
                            Rp {{ number_format($item->total_setoran, 0, ',', '.') }}
                        </td>
                        <td style="padding: 12px; vertical-align: top;">
                            @if($item->status_setoran == 'diterima')
                                <span style="background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; display: inline-block;">
                                    ✓ DITERIMA
                                </span>
                                @if($item->bendahara)
                                    <div style="font-size: 11px; color: #6b7280; margin-top: 4px;">
                                        Oleh: <strong>{{ $item->bendahara->nama_lengkap }}</strong>
                                    </div>
                                @endif
                            @elseif($item->status_setoran == 'ditolak')
                                <span style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; display: inline-block;">
                                    ✕ DITOLAK
                                </span>
                                @if($item->catatan)
                                    <div style="font-size: 11px; color: #dc2626; margin-top: 4px;">
                                        "{{ Str::limit($item->catatan, 30) }}"
                                    </div>
                                @endif
                            @else
                                <span style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; display: inline-block;">
                                    ⏳ MENUNGGU
                                </span>
                            @endif
                        </td>
                        <td style="padding: 12px; vertical-align: top; text-align: center;">
                            <div style="display: inline-flex; gap: 6px; align-items: center; justify-content: center; flex-wrap: wrap;">
                                <a href="{{ route('admin.setoran.show', $item) }}" style="background: #f3f4f6; color: #1f2937; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-weight: 500; font-size: 12px; border: 1px solid #d1d5db;">
                                    Rincian
                                </a>

                                @if($item->status_setoran == 'menunggu' && auth()->user()->hasPermission('setoran.update'))
                                    <!-- Tombol Cepat Terima -->
                                    <form action="{{ route('admin.setoran.verify', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Terima setoran uang fisik sebesar Rp {{ number_format($item->total_setoran, 0, ',', '.') }} dari {{ $item->petugas->nama_lengkap ?? "Petugas" }}?');">
                                        @csrf
                                        <input type="hidden" name="status_setoran" value="diterima">
                                        <button type="submit" style="background: #10b981; border: none; color: white; cursor: pointer; font-weight: 600; font-size: 12px; padding: 6px 12px; border-radius: 4px;">
                                            ✓ Terima Uang
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 24px; text-align: center; color: #6b7280;">
                            Tidak ada data setoran petugas pada kategori ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $setorans->links() }}
    </div>
</div>
@endsection
