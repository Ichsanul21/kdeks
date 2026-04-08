<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HalalProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'region_id' => ['nullable', 'exists:regions,id'],
            'name' => ['required', 'string', 'max:255'],
            'brand_name' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'certificate_number' => ['nullable', 'string', 'max:255'],
            'certificate_issued_at' => ['nullable', 'date'],
            'certificate_expires_at' => ['nullable', 'date', 'after_or_equal:certificate_issued_at'],
            'description' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'product_url' => ['nullable', 'url'],
            'status' => ['required', 'in:published,draft'],
            'is_featured' => ['nullable', 'boolean'],
            'image_path' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
