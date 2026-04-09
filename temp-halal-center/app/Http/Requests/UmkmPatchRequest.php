<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UmkmPatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // "sometimes" supaya endpoint quick update bisa kirim hanya field yang berubah.
        return [
            'source_id' => ['sometimes', 'nullable', 'integer'],
            'nomor' => ['sometimes', 'nullable', 'integer'],
            'region_id' => ['sometimes', 'nullable', 'exists:regions,id'],
            'lph_partner_id' => ['sometimes', 'nullable', 'exists:lph_partners,id'],
            'nama_umkm' => ['sometimes', 'required', 'string', 'max:255'],
            'nama_pemilik' => ['sometimes', 'nullable', 'string', 'max:255'],
            'lembaga' => ['sometimes', 'nullable', 'string', 'max:255'],
            'kategori' => ['sometimes', 'nullable', 'string', 'max:255'],
            'provinsi' => ['sometimes', 'nullable', 'string', 'max:255'],
            'kab_kota' => ['sometimes', 'nullable', 'string', 'max:255'],
            'alamat' => ['sometimes', 'nullable', 'string'],
            'detail_url' => ['sometimes', 'nullable', 'url', 'max:500'],
            'edit_url' => ['sometimes', 'nullable', 'url', 'max:500'],
            'latitude' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],
            'nomor_wa' => ['sometimes', 'nullable', 'string', 'max:50'],
            'link_pembelian' => ['sometimes', 'nullable', 'string', 'max:500'],
            'deskripsi' => ['sometimes', 'nullable', 'string'],
            'foto_url' => ['sometimes', 'nullable', 'string', 'max:500'],
            'jumlah_produk' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'approval' => ['sometimes', 'nullable', 'string', 'max:50'],
            'status' => ['sometimes', 'required', 'in:published,draft'],
            'is_featured' => ['sometimes', 'nullable', 'boolean'],
            'image_path' => ['sometimes', 'nullable', 'image', 'max:4096'],
        ];
    }
}

