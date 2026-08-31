<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('banner.view'), 403);

        $banners = Banner::ordered()->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        abort_if(!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('banner.create'), 403);

        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('banner.create'), 403);

        $validated = $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'gambar'    => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'urutan'    => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $path = $request->file('gambar')->store('banners', 'public');

        Banner::create([
            'judul'     => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'gambar'    => $path,
            'urutan'    => $validated['urutan'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner)
    {
        abort_if(!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('banner.update'), 403);

        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        abort_if(!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('banner.update'), 403);

        $validated = $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'gambar'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'urutan'    => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $data = [
            'judul'     => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'urutan'    => $validated['urutan'] ?? 0,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($banner->gambar) {
                Storage::disk('public')->delete($banner->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        abort_if(!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('banner.delete'), 403);

        if ($banner->gambar) {
            Storage::disk('public')->delete($banner->gambar);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil dihapus.');
    }
}
