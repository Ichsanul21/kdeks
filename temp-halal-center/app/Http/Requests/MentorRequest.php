<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MentorRequest extends FormRequest
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
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email'],
            'expertise' => ['nullable', 'string', 'max:255'],
            'district_coverage' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'certification_number' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'photo_path' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
