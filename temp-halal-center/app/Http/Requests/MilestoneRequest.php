<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MilestoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'string', 'max:50'],
            'sub_title' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:50'],
            'icon' => ['required', 'string', 'max:50'],
            'items' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'image_path' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
