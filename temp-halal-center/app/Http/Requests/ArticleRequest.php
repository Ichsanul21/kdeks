<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:news,publication,research'],
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'excerpt_en' => ['nullable', 'string'],
            'body' => ['required', 'string'],
            'body_en' => ['nullable', 'string'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:published,draft'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'cover_image_path' => ['nullable', 'image', 'max:4096'],
            'document_path' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx', 'max:10240'],
        ];
    }
}
