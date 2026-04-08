<?php

namespace App\Services;

use App\Models\Article;
use App\Models\HalalProduct;
use App\Models\KnowledgeResource;
use App\Models\Regulation;

class SearchService
{
    public function globalSearch(string $keyword): array
    {
        return [
            'articles' => Article::query()
                ->where('status', 'published')
                ->where(fn ($query) => $query
                    ->where('title', 'like', "%{$keyword}%")
                    ->orWhere('excerpt', 'like', "%{$keyword}%"))
                ->latest('published_at')
                ->limit(5)
                ->get(),
            'products' => HalalProduct::query()
                ->where('status', 'published')
                ->where(fn ($query) => $query
                    ->where('name', 'like', "%{$keyword}%")
                    ->orWhere('brand_name', 'like', "%{$keyword}%")
                    ->orWhere('category', 'like', "%{$keyword}%"))
                ->limit(5)
                ->get(),
            'resources' => KnowledgeResource::query()
                ->where(fn ($query) => $query
                    ->where('title', 'like', "%{$keyword}%")
                    ->orWhere('summary', 'like', "%{$keyword}%"))
                ->latest('published_at')
                ->limit(5)
                ->get(),
            'regulations' => Regulation::query()
                ->where(fn ($query) => $query
                    ->where('title', 'like', "%{$keyword}%")
                    ->orWhere('regulation_number', 'like', "%{$keyword}%"))
                ->latest('issued_at')
                ->limit(5)
                ->get(),
        ];
    }
}
