<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::query()
            ->where('status', 'published')
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->string('type')->toString()))
            ->latest('published_at')
            ->paginate(10);

        return ApiResponse::success(ArticleResource::collection($articles), 'Articles loaded.');
    }
}
