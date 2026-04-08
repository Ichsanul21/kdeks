<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'location_name' => ['nullable', 'string', 'max:255'],
            'meeting_url' => ['nullable', 'url'],
            'registration_url' => ['nullable', 'url'],
            'status' => ['required', 'in:published,draft'],
            'is_featured' => ['nullable', 'boolean'],
            'banner_path' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
