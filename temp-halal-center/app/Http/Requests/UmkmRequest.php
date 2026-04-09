<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_id' => ['nullable', 'integer'],
            'nomor' => ['nullable', 'integer'],
            'region_id' => ['nullable', 'exists:regions,id'],
            'lph_partner_id' => ['nullable', 'exists:lph_partners,id'],
            'nama_umkm' => ['required', 'string', 'max:255'],
            'nama_pemilik' => ['nullable', 'string', 'max:255'],
            'lembaga' => ['nullable', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'provinsi' => ['nullable', 'string', 'max:255'],
            'kab_kota' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'detail_url' => ['nullable', 'url', 'max:500'],
            'edit_url' => ['nullable', 'url', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'nomor_wa' => ['nullable', 'string', 'max:50'],
            'link_pembelian' => ['nullable', 'string', 'max:500'],
            'deskripsi' => ['nullable', 'string'],
            'foto_url' => ['nullable', 'string', 'max:500'],
            'jumlah_produk' => ['nullable', 'integer', 'min:0'],
            'approval' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:published,draft'],
            'is_featured' => ['nullable', 'boolean'],
            'image_path' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
