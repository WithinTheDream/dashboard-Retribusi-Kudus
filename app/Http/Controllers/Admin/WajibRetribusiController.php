<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WajibRetribusi;
use App\Models\JenisRetribusi;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\PengajuanWajibRetribusi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WajibRetribusiController extends Controller
{
    public function index()
    {
        $wajibRetribusis = WajibRetribusi::with(['kecamatan', 'desa', 'jenisRetribusi'])
            ->latest()
            ->paginate(10);

        return view('admin.wajib-retribusi.index', compact('wajibRetribusis'));
    }

    public function create()
    {
        $jenisRetribusis = JenisRetribusi::all();
        $kecamatans = Kecamatan::all();
        $desas = collect();

        return view('admin.wajib-retribusi.create', compact(
            'jenisRetribusis', 'kecamatans', 'desas'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:255', 'unique:wajib_retribusis,kode'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16'],
            'nama_usaha' => ['nullable', 'string', 'max:255'],
            'jenis_retribusi_id' => ['required', 'exists:jenis_retribusis,id'],
            'kecamatan_id' => ['required', 'exists:kecamatans,id'],
            'desa_id' => ['required', 'exists:desas,id'],
            'alamat' => ['required', 'string'],
            'rt' => ['required', 'string', 'max:3'],
            'rw' => ['required', 'string', 'max:3'],
            'no_hp' => ['required', 'string', 'max:20'],
            'status_aktif' => ['boolean'],
        ]);

        $validated['status_aktif'] = $request->boolean('status_aktif');

        // 1. Dapatkan user_id (Gunakan user yang login atau fallback ke user pertama)
        $userId = auth()->id() ?? User::first()->id ?? 1;
        $validated['user_id'] = $userId;

        // 2. Buat entri record pengajuan otomatis agar foreign key pengajuan_id terpenuhi
        $pengajuan = PengajuanWajibRetribusi::create([
            'nomor_pengajuan'    => 'REG-ADM-' . date('YmdHis'),
            'user_id'            => $userId,
            'jenis_retribusi_id' => $validated['jenis_retribusi_id'],
            'nik'                => $validated['nik'],
            'nama_lengkap'       => $validated['nama_lengkap'],
            'nama_usaha'         => $validated['nama_usaha'] ?? null,
            'kecamatan_id'       => $validated['kecamatan_id'],
            'desa_id'            => $validated['desa_id'],
            'alamat'             => $validated['alamat'],
            'rt'                 => $validated['rt'],
            'rw'                 => $validated['rw'],
            'no_hp'              => $validated['no_hp'],
            'status_pengajuan'   => 'disetujui',
            'catatan_admin'      => 'Didaftarkan manual oleh Admin',
        ]);

        $validated['pengajuan_id'] = $pengajuan->id;

        // 3. Simpan data wajib retribusi
        WajibRetribusi::create($validated);

        return redirect()
            ->route('admin.wajib-retribusi.index')
            ->with('success', 'Data wajib retribusi berhasil ditambahkan.');
    }

    public function show(WajibRetribusi $wajibRetribusi)
    {
        $wajibRetribusi->load(['kecamatan', 'desa', 'jenisRetribusi', 'tagihans', 'pengajuan']);

        return view('admin.wajib-retribusi.show', compact('wajibRetribusi'));
    }

    public function edit(WajibRetribusi $wajibRetribusi)
    {
        $jenisRetribusis = JenisRetribusi::all();
        $kecamatans = Kecamatan::all();
        $desas = Desa::where('kec_id', $wajibRetribusi->kecamatan_id)->get();

        return view('admin.wajib-retribusi.edit', compact(
            'wajibRetribusi', 'jenisRetribusis', 'kecamatans', 'desas'
        ));
    }

    public function update(Request $request, WajibRetribusi $wajibRetribusi)
    {
        $validated = $request->validate([
            'kode' => [
                'required', 'string', 'max:255',
                Rule::unique('wajib_retribusis', 'kode')
                    ->ignore($wajibRetribusi->id),
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
            'no_hp' => ['required', 'string', 'max:20'],
            'status_aktif' => ['boolean'],
        ]);

        $validated['status_aktif'] = $request->boolean('status_aktif');

        $wajibRetribusi->update($validated);

        return redirect()
            ->route('admin.wajib-retribusi.index')
            ->with('success', 'Data wajib retribusi berhasil diperbarui.');
    }

    public function destroy(WajibRetribusi $wajibRetribusi)
    {
        if ($wajibRetribusi->tagihans()->exists()) {
            return back()->with(
                'error',
                'Wajib retribusi tidak dapat dihapus karena sudah memiliki tagihan.'
            );
        }

        $wajibRetribusi->delete();

        return redirect()
            ->route('admin.wajib-retribusi.index')
            ->with('success', 'Data wajib retribusi berhasil dihapus.');
    }

    public function getDesaByKecamatan(Kecamatan $kecamatan)
    {
        $desas = $kecamatan->desas()->select('id', 'desa')->get();

        return response()->json($desas);
    }
}
