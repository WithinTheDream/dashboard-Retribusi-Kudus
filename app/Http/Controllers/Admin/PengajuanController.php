<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanWajibRetribusi;
use App\Models\WajibRetribusi;
use App\Models\JenisRetribusi;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = PengajuanWajibRetribusi::with([
            'kecamatan', 'desa', 'jenisRetribusi', 'user'
        ])->latest()->paginate(10);

        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    public function create()
    {
        $jenisRetribusis = JenisRetribusi::all();
        $kecamatans = Kecamatan::all();
        $desas = collect();

        return view('admin.pengajuan.create', compact(
            'jenisRetribusis', 'kecamatans', 'desas'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_pengajuan' => [
                'required', 'string', 'max:255',
                'unique:pengajuan_wajib_retribusis,nomor_pengajuan',
            ],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16'],
            'nama_usaha' => ['nullable', 'string', 'max:255'],
            'jenis_retribusi_id' => ['required', 'exists:jenis_retribusis,id'],
            'kecamatan_id' => ['required', 'exists:kecamatans,id'],
            'desa_id' => ['required', 'exists:desas,id'],
            'alamat' => ['required', 'string'],
            'rt' => ['required', 'string', 'max:3'],
            'rw' => ['required', 'string', 'max:3'],
            'lat' => ['nullable', 'string'],
            'lokasi_long' => ['nullable', 'string'],
            'no_hp' => ['required', 'string', 'max:20'],
            'status_pengajuan' => [
                'required', 'in:menunggu,perbaikan,survey,ditolak,disetujui',
            ],
        ]);

        $validated['user_id'] = auth()->id() ?? 1;

        DB::transaction(function () use ($validated) {
            $pengajuan = PengajuanWajibRetribusi::create($validated);

            // Jika status langsung 'disetujui', buat entri Wajib Retribusi
            if ($validated['status_pengajuan'] === 'disetujui') {
                $kodeWR = 'WR-' . date('Ym') . '-' . str_pad($pengajuan->id, 4, '0', STR_PAD_LEFT);
                WajibRetribusi::firstOrCreate(
                    ['pengajuan_id' => $pengajuan->id],
                    [
                        'kode'               => $kodeWR,
                        'user_id'            => $pengajuan->user_id,
                        'jenis_retribusi_id' => $pengajuan->jenis_retribusi_id,
                        'nik'                => $pengajuan->nik,
                        'nama_lengkap'       => $pengajuan->nama_lengkap,
                        'nama_usaha'         => $pengajuan->nama_usaha,
                        'kecamatan_id'       => $pengajuan->kecamatan_id,
                        'desa_id'            => $pengajuan->desa_id,
                        'alamat'             => $pengajuan->alamat,
                        'rt'                 => $pengajuan->rt,
                        'rw'                 => $pengajuan->rw,
                        'no_hp'              => $pengajuan->no_hp,
                        'status_aktif'       => true,
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', 'Data pengajuan berhasil ditambahkan.');
    }

    public function show(PengajuanWajibRetribusi $pengajuan)
    {
        // Telah disesuaikan tanpa relasi dokumenPengajuans yang belum ada
        $pengajuan->load(['kecamatan', 'desa', 'jenisRetribusi', 'user']);

        return view('admin.pengajuan.show', compact('pengajuan'));
    }

    public function edit(PengajuanWajibRetribusi $pengajuan)
    {
        $jenisRetribusis = JenisRetribusi::all();
        $kecamatans = Kecamatan::all();
        $desas = Desa::where('kec_id', $pengajuan->kecamatan_id)->get();

        return view('admin.pengajuan.edit', compact(
            'pengajuan', 'jenisRetribusis', 'kecamatans', 'desas'
        ));
    }

    public function update(Request $request, PengajuanWajibRetribusi $pengajuan)
    {
        $validated = $request->validate([
            'nomor_pengajuan' => [
                'required', 'string', 'max:255',
                Rule::unique('pengajuan_wajib_retribusis', 'nomor_pengajuan')
                    ->ignore($pengajuan->id),
            ],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16'],
            'nama_usaha' => ['nullable', 'string', 'max:255'],
            'jenis_retribusi_id' => ['required', 'exists:jenis_retribusis,id'],
            'kecamatan_id' => ['required', 'exists:kecamatans,id'],
            'desa_id' => ['required', 'exists:desas,id'],
            'alamat' => ['required', 'string'],
            'rt' => ['required', 'string', 'max:3'],
            'rw' => ['required', 'string', 'max:3'],
            'lat' => ['nullable', 'string'],
            'lokasi_long' => ['nullable', 'string'],
            'no_hp' => ['required', 'string', 'max:20'],
            'status_pengajuan' => [
                'required', 'in:menunggu,perbaikan,survey,ditolak,disetujui',
            ],
            'catatan_admin' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $pengajuan) {
            $pengajuan->update($validated);

            // Jika status diubah menjadi 'disetujui', buat record Wajib Retribusi otomatis
            if ($validated['status_pengajuan'] === 'disetujui') {
                $kodeWR = 'WR-' . date('Ym') . '-' . str_pad($pengajuan->id, 4, '0', STR_PAD_LEFT);
                WajibRetribusi::firstOrCreate(
                    ['pengajuan_id' => $pengajuan->id],
                    [
                        'kode'               => $kodeWR,
                        'user_id'            => $pengajuan->user_id,
                        'jenis_retribusi_id' => $pengajuan->jenis_retribusi_id,
                        'nik'                => $pengajuan->nik,
                        'nama_lengkap'       => $pengajuan->nama_lengkap,
                        'nama_usaha'         => $pengajuan->nama_usaha,
                        'kecamatan_id'       => $pengajuan->kecamatan_id,
                        'desa_id'            => $pengajuan->desa_id,
                        'alamat'             => $pengajuan->alamat,
                        'rt'                 => $pengajuan->rt,
                        'rw'                 => $pengajuan->rw,
                        'no_hp'              => $pengajuan->no_hp,
                        'status_aktif'       => true,
                    ]
                );
            }
        });

        // Jika disetujui, otomatis buat data Wajib Retribusi jika belum ada
        if ($validated['status_pengajuan'] === 'disetujui') {
            \App\Models\WajibRetribusi::firstOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                [
                    'kode' => 'WR-' . date('Ym') . str_pad($pengajuan->id, 4, '0', STR_PAD_LEFT),
                    'user_id' => $pengajuan->user_id,
                    'nik' => $pengajuan->nik,
                    'nama_lengkap' => $pengajuan->nama_lengkap,
                    'nama_usaha' => $pengajuan->nama_usaha,
                    'kecamatan_id' => $pengajuan->kecamatan_id,
                    'desa_id' => $pengajuan->desa_id,
                    'alamat' => $pengajuan->alamat,
                    'rt' => $pengajuan->rt,
                    'rw' => $pengajuan->rw,
                    'lokasi_long' => $pengajuan->lokasi_long,
                    'lat' => $pengajuan->lat,
                    'no_hp' => $pengajuan->no_hp,
                    'jenis_retribusi_id' => $pengajuan->jenis_retribusi_id,
                    'status_aktif' => true,
                ]
            );
        }

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', 'Data pengajuan berhasil diperbarui.');
    }

    public function destroy(PengajuanWajibRetribusi $pengajuan)
    {
        if ($pengajuan->wajibRetribusi()->exists()) {
            return back()->with(
                'error',
                'Pengajuan tidak dapat dihapus karena sudah menjadi wajib retribusi.'
            );
        }

        $pengajuan->delete();

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', 'Data pengajuan berhasil dihapus.');
    }
}
