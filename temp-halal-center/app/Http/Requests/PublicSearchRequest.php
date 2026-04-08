<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => ['required', 'string', 'min:2', 'max:100'],
        ];
    }
}
