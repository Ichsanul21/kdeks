<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Filter out empty file inputs if photos array is present
        if ($this->hasFile('photos')) {
            $filtered = array_values(array_filter($this->file('photos'), function ($file) {
                return $file instanceof \Illuminate\Http\UploadedFile && $file->isValid();
            }));
            $this->files->set('photos', $filtered);
        }
    }

    public function rules(): array
    {
        $isCreate = $this->isMethod('POST') && !$this->route('gallery_item');

        return [
            'title' => ['required', 'string', 'max:255'],
            'sector_item_id' => ['nullable', 'exists:sector_items,id'],
            'media_type' => ['required', 'in:image,video'],
            'caption' => ['nullable', 'string'],
            'external_video_url' => ['nullable', 'url'],
            'recorded_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'media_path' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov', 'max:20480'],
            'photos' => [$isCreate ? 'required' : 'nullable', 'array', 'min:1', 'max:5'],
            'photos.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov', 'max:20480'],
        ];
    }

    public function messages(): array
    {
        return [
            'photos.required' => 'Minimal 1 foto wajib diunggah.',
            'photos.min' => 'Minimal 1 foto wajib diunggah.',
            'photos.max' => 'Maksimal 5 foto yang dapat diunggah.',
            'photos.*.required' => 'File foto wajib dipilih.',
            'photos.*.file' => 'File yang diunggah harus berupa file yang valid.',
            'photos.*.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, WEBP, MP4, atau MOV.',
            'photos.*.max' => 'Ukuran setiap foto maksimal 20 MB.',
            'media_path.mimes' => 'Format file media harus berupa JPG, JPEG, PNG, WEBP, MP4, atau MOV.',
            'media_path.max' => 'Ukuran file media maksimal 20 MB.',
        ];
    }
}
