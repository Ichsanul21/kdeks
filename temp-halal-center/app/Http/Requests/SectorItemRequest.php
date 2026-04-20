<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectorItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'icon_key' => ['required', 'string', 'max:100'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
