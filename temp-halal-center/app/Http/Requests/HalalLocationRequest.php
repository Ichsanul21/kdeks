<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HalalLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'region_id' => ['required', 'exists:regions,id'],
            'lph_partner_id' => ['nullable', 'exists:lph_partners,id'],
            'name' => ['required', 'string', 'max:255'],
            'location_type' => ['required', 'in:umkm,service_office'],
            'category' => ['required', 'in:Makanan,Minuman,Wisata Ramah,Unit Usaha Ponpes,Produk Halal Lainnya,Rumah Potong,Industri Kreatif,Perbankan Syariah,Lembaga Keuangan'],
            'city_name' => ['nullable', 'string', 'max:255'],
            'business_scale' => ['nullable', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'brand_name' => ['nullable', 'string', 'max:255'],
            'product_name' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email'],
            'website_url' => ['nullable', 'url'],
            'description' => ['nullable', 'string'],
            'certificate_number' => ['nullable', 'string', 'max:255'],
            'certificate_issued_at' => ['nullable', 'date'],
            'certificate_expires_at' => ['nullable', 'date', 'after_or_equal:certificate_issued_at'],
            'status' => ['required', 'in:published,draft'],
            'is_featured' => ['nullable', 'boolean'],
            'image_path' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
