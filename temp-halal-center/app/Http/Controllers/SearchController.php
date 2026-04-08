<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicSearchRequest;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function __construct(protected SearchService $searchService)
    {
    }

    public function __invoke(PublicSearchRequest $request): JsonResponse
    {
        return response()->json($this->searchService->globalSearch($request->validated('keyword')));
    }
}
