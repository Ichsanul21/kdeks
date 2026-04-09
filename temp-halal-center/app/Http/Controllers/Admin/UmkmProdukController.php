<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UmkmProdukRequest;
use App\Models\Umkm;
use App\Models\UmkmProduk;
use App\Services\MediaStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UmkmProdukController extends Controller
{
    public function __construct(protected MediaStorageService $mediaStorageService) {}

    public function create(Umkm $umkm): View
    {
        return view('admin.umkms.produk-form', [
            'pageTitle' => 'Tambah Produk — ' . $umkm->nama_umkm,
            'umkm' => $umkm,
            'produk' => new UmkmProduk(),
            'mode' => 'create',
        ]);
    }

    public function store(UmkmProdukRequest $request, Umkm $umkm): RedirectResponse
    {
        $data = $request->validated();

        $produk = new UmkmProduk($data);
        $produk->umkm_id = $umkm->id;

        if ($request->hasFile('image_path')) {
            $produk->image_path = $this->mediaStorageService->replace(
                null, $request->file('image_path'), 'public', 'umkm-produk'
            );
        }

        $produk->save();

        return redirect()->route('admin.umkms.edit', $umkm->id)
            ->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Umkm $umkm, UmkmProduk $produk): View
    {
        return view('admin.umkms.produk-form', [
            'pageTitle' => 'Edit Produk — ' . $produk->nama_produk,
            'umkm' => $umkm,
            'produk' => $produk,
            'mode' => 'edit',
        ]);
    }

    public function update(UmkmProdukRequest $request, Umkm $umkm, UmkmProduk $produk): RedirectResponse
    {
        $produk->fill($request->validated());

        if ($request->hasFile('image_path')) {
            $produk->image_path = $this->mediaStorageService->replace(
                $produk->image_path, $request->file('image_path'), 'public', 'umkm-produk'
            );
        }

        $produk->save();

        return redirect()->route('admin.umkms.edit', $umkm->id)
            ->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Umkm $umkm, UmkmProduk $produk): RedirectResponse
    {
        if ($produk->image_path) {
            $this->mediaStorageService->delete($produk->image_path, 'public');
        }

        $produk->delete();

        return redirect()->route('admin.umkms.edit', $umkm->id)
            ->with('status', 'Produk berhasil dihapus.');
    }
}
