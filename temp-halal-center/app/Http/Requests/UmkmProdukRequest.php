<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UmkmProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor' => ['nullable', 'integer'],
            'nama_produk' => ['required', 'string', 'max:255'],
            'detail_url' => ['nullable', 'url', 'max:500'],
            'edit_url' => ['nullable', 'url', 'max:500'],
            'foto_url' => ['nullable', 'string', 'max:500'],
            'harga' => ['nullable', 'string', 'max:255'],
            'lph_lp3h' => ['nullable', 'string', 'max:255'],
            'akta_halal' => ['nullable', 'string', 'max:255'],
            'tahun_terbit' => ['nullable', 'string', 'max:10'],
            'deskripsi' => ['nullable', 'string'],
            'image_path' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
