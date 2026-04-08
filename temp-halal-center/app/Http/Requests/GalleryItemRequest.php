<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'media_type' => ['required', 'in:image,video'],
            'caption' => ['nullable', 'string'],
            'external_video_url' => ['nullable', 'url'],
            'recorded_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'media_path' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov', 'max:20480'],
        ];
    }
}
