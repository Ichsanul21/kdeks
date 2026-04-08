<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegulationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'regulation_type' => ['required', 'string', 'max:255'],
            'regulation_number' => ['nullable', 'string', 'max:255'],
            'issued_at' => ['nullable', 'date'],
            'summary' => ['nullable', 'string'],
            'external_url' => ['nullable', 'url'],
            'is_featured' => ['nullable', 'boolean'],
            'document_path' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ];
    }
}
