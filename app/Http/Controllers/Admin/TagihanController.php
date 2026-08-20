<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\WajibRetribusi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagihanController extends Controller
{
    public function index()
    {
        $tagihans = Tagihan::with('wajibRetribusi')
            ->latest()
            ->paginate(10);

        return view('admin.tagihan.index', compact('tagihans'));
    }

    public function create()
    {
        $wajibRetribusis = WajibRetribusi::all();

        return view('admin.tagihan.create', compact('wajibRetribusis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_tagihan' => [
                'required', 'string', 'max:255',
                'unique:tagihans,nomor_tagihan',
            ],
            'wajib_retribusi_id' => ['required', 'exists:wajib_retribusis,id'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:2099'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:belum_bayar,lunas,dibatalkan'],
        ]);

        Tagihan::create($validated);

        return redirect()
            ->route('admin.tagihan.index')
            ->with('success', 'Data tagihan berhasil ditambahkan.');
    }

    public function edit(Tagihan $tagihan)
    {
        $wajibRetribusis = WajibRetribusi::all();

        return view('admin.tagihan.edit', compact(
            'tagihan', 'wajibRetribusis'
        ));
    }

    public function update(Request $request, Tagihan $tagihan)
    {
        $validated = $request->validate([
            'nomor_tagihan' => [
                'required', 'string', 'max:255',
                Rule::unique('tagihans', 'nomor_tagihan')
                    ->ignore($tagihan->id),
            ],
            'wajib_retribusi_id' => ['required', 'exists:wajib_retribusis,id'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:2099'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:belum_bayar,lunas,dibatalkan'],
        ]);

        $tagihan->update($validated);

        return redirect()
            ->route('admin.tagihan.index')
            ->with('success', 'Data tagihan berhasil diperbarui.');
    }

    public function destroy(Tagihan $tagihan)
    {
        if ($tagihan->pembayaran()->exists()) {
            return back()->with(
                'error',
                'Tagihan tidak dapat dihapus karena sudah memiliki pembayaran.'
            );
        }

        $tagihan->delete();

        return redirect()
            ->route('admin.tagihan.index')
            ->with('success', 'Data tagihan berhasil dihapus.');
    }
}