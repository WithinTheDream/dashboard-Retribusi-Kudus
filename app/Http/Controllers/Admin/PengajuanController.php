<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanWajibRetribusi;
use App\Models\JenisRetribusi;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\HistoriPengajuan;
use App\Models\WajibRetribusi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('pengajuan.view'), 403);

        $pengajuans = PengajuanWajibRetribusi::with([
            'kecamatan', 'desa', 'jenisRetribusi', 'user'
        ])->latest()->paginate(10);

        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('pengajuan.create'), 403);

        $jenisRetribusis = JenisRetribusi::all();
        $kecamatans = Kecamatan::all();
        $desas = collect();

        return view('admin.pengajuan.create', compact(
            'jenisRetribusis', 'kecamatans', 'desas'
        ));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('pengajuan.create'), 403);

        if ($request->filled('koordinat') && (!$request->filled('lat') || !$request->filled('lokasi_long'))) {
            $parts = preg_split('/[,\s]+/', trim($request->input('koordinat')));
            if (count($parts) >= 2) {
                $request->merge([
                    'lat' => $parts[0],
                    'lokasi_long' => $parts[1],
                ]);
            }
        }

        $validated = $request->validate([
            'nomor_pengajuan' => [
                'required', 'string', 'max:255',
                'unique:pengajuan_wajib_retribusis,nomor_pengajuan',
            ],
            'nama_lengkap'       => ['required', 'string', 'max:255'],
            'nik'                => ['required', 'string', 'size:16'],
            'nama_usaha'         => ['nullable', 'string', 'max:255'],
            'npwp'               => ['nullable', 'string', 'max:50'],
            'nib'                => ['nullable', 'string', 'max:50'],
            'jenis_retribusi_id' => ['required', 'exists:jenis_retribusis,id'],
            'kecamatan_id'       => ['required', 'exists:kecamatans,id'],
            'desa_id'            => ['required', 'exists:desas,id'],
            'alamat'             => ['required', 'string'],
            'rt'                 => ['required', 'string', 'max:3'],
            'rw'                 => ['required', 'string', 'max:3'],
            'lat'                => ['nullable', 'numeric', 'between:-90,90'],
            'lokasi_long'        => ['nullable', 'numeric', 'between:-180,180'],
            'no_hp'              => ['required', 'string', 'max:20'],
            'status_pengajuan'   => [
                'required', 'in:menunggu,perbaikan,survey,ditolak,disetujui',
            ],
        ]);

        $validated['user_id'] = auth()->id() ?? 1;

        DB::transaction(function () use ($validated) {
            $pengajuan = PengajuanWajibRetribusi::create($validated);

            HistoriPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'status'       => $pengajuan->status_pengajuan,
                'catatan'      => 'Pengajuan dibuat oleh admin',
                'user_id'      => auth()->id(),
            ]);

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
                        'npwp'               => $pengajuan->npwp,
                        'nib'                => $pengajuan->nib,
                        'kecamatan_id'       => $pengajuan->kecamatan_id,
                        'desa_id'            => $pengajuan->desa_id,
                        'alamat'             => $pengajuan->alamat,
                        'rt'                 => $pengajuan->rt,
                        'rw'                 => $pengajuan->rw,
                        'lat'                => $pengajuan->lat,
                        'lokasi_long'        => $pengajuan->lokasi_long,
                        'latitude'           => $pengajuan->lat ?? null,
                        'longitude'          => $pengajuan->lokasi_long ?? null,
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
        abort_if(!auth()->user()->hasPermission('pengajuan.view'), 403);

        $pengajuan->load(['kecamatan', 'desa', 'jenisRetribusi', 'user', 'dokumen', 'histori.user']);

        return view('admin.pengajuan.show', compact('pengajuan'));
    }

    public function edit(PengajuanWajibRetribusi $pengajuan)
    {
        abort_if(!auth()->user()->hasPermission('pengajuan.update') && !auth()->user()->hasPermission('pengajuan.verify'), 403);

        $jenisRetribusis = JenisRetribusi::all();
        $kecamatans = Kecamatan::all();
        $desas = Desa::where('kec_id', $pengajuan->kecamatan_id)->get();

        return view('admin.pengajuan.edit', compact(
            'pengajuan', 'jenisRetribusis', 'kecamatans', 'desas'
        ));
    }

    public function update(Request $request, PengajuanWajibRetribusi $pengajuan)
    {
        abort_if(!auth()->user()->hasPermission('pengajuan.update') && !auth()->user()->hasPermission('pengajuan.verify'), 403);

        if ($request->filled('koordinat') && (!$request->filled('lat') || !$request->filled('lokasi_long'))) {
            $parts = preg_split('/[,\s]+/', trim($request->input('koordinat')));
            if (count($parts) >= 2) {
                $request->merge([
                    'lat' => $parts[0],
                    'lokasi_long' => $parts[1],
                ]);
            }
        }

        $validated = $request->validate([
            'nomor_pengajuan' => [
                'required', 'string', 'max:255',
                Rule::unique('pengajuan_wajib_retribusis', 'nomor_pengajuan')
                    ->ignore($pengajuan->id),
            ],
            'nama_lengkap'       => ['required', 'string', 'max:255'],
            'nik'                => ['required', 'string', 'size:16'],
            'nama_usaha'         => ['nullable', 'string', 'max:255'],
            'npwp'               => ['nullable', 'string', 'max:50'],
            'nib'                => ['nullable', 'string', 'max:50'],
            'jenis_retribusi_id' => ['required', 'exists:jenis_retribusis,id'],
            'kecamatan_id'       => ['required', 'exists:kecamatans,id'],
            'desa_id'            => ['required', 'exists:desas,id'],
            'alamat'             => ['required', 'string'],
            'rt'                 => ['required', 'string', 'max:3'],
            'rw'                 => ['required', 'string', 'max:3'],
            'lat'                => ['nullable', 'numeric', 'between:-90,90'],
            'lokasi_long'        => ['nullable', 'numeric', 'between:-180,180'],
            'no_hp'              => ['required', 'string', 'max:20'],
            'status_pengajuan'   => [
                'required', 'in:menunggu,perbaikan,survey,ditolak,disetujui',
            ],
            'catatan_admin'      => ['nullable', 'string'],
        ]);

        $statusChanged = $pengajuan->status_pengajuan !== $validated['status_pengajuan'];

        DB::transaction(function () use ($validated, $pengajuan, $statusChanged) {
            $pengajuan->update($validated);

            if ($statusChanged || !empty($validated['catatan_admin'])) {
                HistoriPengajuan::create([
                    'pengajuan_id' => $pengajuan->id,
                    'status'       => $pengajuan->status_pengajuan,
                    'catatan'      => $validated['catatan_admin'] ?? 'Status diubah menjadi ' . $pengajuan->status_pengajuan,
                    'user_id'      => auth()->id(),
                ]);
            }

            // Jika disetujui, otomatis buat data Wajib Retribusi jika belum ada
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
                        'npwp'               => $pengajuan->npwp,
                        'nib'                => $pengajuan->nib,
                        'kecamatan_id'       => $pengajuan->kecamatan_id,
                        'desa_id'            => $pengajuan->desa_id,
                        'alamat'             => $pengajuan->alamat,
                        'rt'                 => $pengajuan->rt,
                        'rw'                 => $pengajuan->rw,
                        'lokasi_long'        => $pengajuan->lokasi_long,
                        'lat'                => $pengajuan->lat,
                        'latitude'           => $pengajuan->lat ?? null,
                        'longitude'          => $pengajuan->lokasi_long ?? null,
                        'no_hp'              => $pengajuan->no_hp,
                        'status_aktif'       => true,
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', 'Data pengajuan berhasil diperbarui.');
    }

    public function destroy(PengajuanWajibRetribusi $pengajuan)
    {
        abort_if(!auth()->user()->hasPermission('pengajuan.delete'), 403);

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