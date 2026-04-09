<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WatermarkSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->hasRole('developer') ?? false;
    }

    public function rules(): array
    {
        return [
            'watermark_enabled' => ['nullable', 'boolean'],
            'watermark_text' => ['nullable', 'string', 'max:255'],
            'watermark_image_path' => ['nullable', 'image', 'max:4096'],
            'watermark_opacity' => ['nullable', 'numeric', 'between:0.05,1'],
        ];
    }
}
