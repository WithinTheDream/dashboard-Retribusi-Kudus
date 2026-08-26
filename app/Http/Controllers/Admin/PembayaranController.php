<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('pembayaran.view'), 403);

        $pembayarans = Pembayaran::with(['tagihan.wajibRetribusi', 'petugas'])
            ->latest()
            ->paginate(10);

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('pembayaran.create'), 403);

        $tagihans = Tagihan::where('status', '!=', 'lunas')
            ->orWhereNull('status')
            ->with('wajibRetribusi')
            ->latest()
            ->get();

        return view('admin.pembayaran.create', compact('tagihans'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('pembayaran.create'), 403);

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

        $validated['user_id'] = auth()->id();

        DB::transaction(function () use ($validated, $request) {
            $pembayaran = Pembayaran::create($validated);

            Tagihan::where('id', $request->tagihan_id)
                ->update(['status' => 'lunas']);
        });

        return redirect()
            ->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil ditambahkan.');
    }

    public function show(Pembayaran $pembayaran)
    {
        abort_if(!auth()->user()->hasPermission('pembayaran.view'), 403);

        $pembayaran->load(['tagihan.wajibRetribusi', 'petugas']);

        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function edit(Pembayaran $pembayaran)
    {
        abort_if(!auth()->user()->hasPermission('pembayaran.update'), 403);

        $tagihans = Tagihan::with('wajibRetribusi')->get();

        return view('admin.pembayaran.edit', compact(
            'pembayaran', 'tagihans'
        ));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        abort_if(!auth()->user()->hasPermission('pembayaran.update'), 403);

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
        abort_if(!auth()->user()->hasPermission('pembayaran.delete'), 403);

        if ($pembayaran->setoranDetail()->exists()) {
            return back()->with(
                'error',
                'Pembayaran tidak dapat dihapus karena sudah masuk setoran.'
            );
        }

        DB::transaction(function () use ($pembayaran) {
            $tagihanId = $pembayaran->tagihan_id;
            $pembayaran->delete();

            // Kembalikan status tagihan ke belum_bayar jika tidak ada pembayaran lain
            if (!Pembayaran::where('tagihan_id', $tagihanId)->exists()) {
                Tagihan::where('id', $tagihanId)->update(['status' => 'belum_bayar']);
            }
        });

        return redirect()
            ->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil dihapus dan status tagihan dikembalikan.');
    }
}
