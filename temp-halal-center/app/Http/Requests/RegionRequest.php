<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'halal_msmes_count' => ['required', 'integer', 'min:0'],
            'mentor_count' => ['required', 'integer', 'min:0'],
            'issued_certificate_count' => ['required', 'integer', 'min:0'],
            'service_office_count' => ['required', 'integer', 'min:0'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
