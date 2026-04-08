<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicSearchRequest;
use App\Support\ApiResponse;
use App\Services\SearchService;

class SearchController extends Controller
{
    public function __construct(protected SearchService $searchService)
    {
    }

    public function __invoke(PublicSearchRequest $request)
    {
        return ApiResponse::success(
            $this->searchService->globalSearch($request->validated('keyword')),
            'Search results loaded.'
        );
    }
}
