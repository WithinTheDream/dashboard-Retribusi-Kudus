<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with(['tagihan.wajibRetribusi', 'petugas'])
            ->latest()
            ->paginate(10);

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function create()
    {
        $tagihans = Tagihan::where('status', '!=', 'lunas')
            ->orWhereNull('status')
            ->with('wajibRetribusi')
            ->latest()
            ->get();

        return view('admin.pembayaran.create', compact('tagihans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_pembayaran' => [
                'required', 'string', 'max:255',
                'unique:pembayarans,nomor_pembayaran',
            ],
            'tagihan_id'        => ['required', 'exists:tagihans,id'],
            'nominal_bayar'     => ['required', 'numeric', 'min:0'],
            'metode_pembayaran' => ['required', 'string', 'in:tunai,qris'],
            'waktu_bayar'       => ['required', 'date'],
        ]);

        $validated['user_id'] = auth()->id() ?? 1;

        $pembayaran = Pembayaran::create($validated);

        Tagihan::where('id', $request->tagihan_id)
            ->update(['status' => 'lunas']);

        return redirect()
            ->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil ditambahkan.');
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['tagihan.wajibRetribusi', 'petugas']);

        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function edit(Pembayaran $pembayaran)
    {
        $tagihans = Tagihan::with('wajibRetribusi')->get();

        return view('admin.pembayaran.edit', compact(
            'pembayaran', 'tagihans'
        ));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'nomor_pembayaran' => [
                'required', 'string', 'max:255',
                Rule::unique('pembayarans', 'nomor_pembayaran')
                    ->ignore($pembayaran->id),
            ],
            'tagihan_id'        => ['required', 'exists:tagihans,id'],
            'nominal_bayar'     => ['required', 'numeric', 'min:0'],
            'metode_pembayaran' => ['required', 'string', 'in:tunai,qris'],
            'waktu_bayar'       => ['required', 'date'],
        ]);

        $pembayaran->update($validated);

        return redirect()
            ->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->setoranDetail()->exists()) {
            return back()->with(
                'error',
                'Pembayaran tidak dapat dihapus karena sudah masuk setoran.'
            );
        }

        $pembayaran->delete();

        return redirect()
            ->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
