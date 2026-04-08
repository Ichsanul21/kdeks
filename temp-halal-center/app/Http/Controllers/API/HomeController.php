<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\KnowledgeResourceResource;
use App\Http\Resources\LphPartnerResource;
use App\Http\Resources\PotentialItemResource;
use App\Http\Resources\RegionResource;
use App\Http\Resources\SectorItemResource;
use App\Services\LandingPageService;
use App\Support\ApiResponse;

class HomeController extends Controller
{
    public function __construct(protected LandingPageService $landingPageService)
    {
    }

    public function index()
    {
        $data = $this->landingPageService->getHomepageData();

        return ApiResponse::success([
            'statistics' => $data['statistics'],
            'regions' => RegionResource::collection($data['regions']),
            'lph_partners' => LphPartnerResource::collection($data['lphPartners']),
            'potential_items' => PotentialItemResource::collection($data['potentialItems']),
            'sector_items' => SectorItemResource::collection($data['sectorItems']),
            'articles' => ArticleResource::collection($data['featuredArticles']),
            'events' => EventResource::collection($data['events']),
            'resources' => KnowledgeResourceResource::collection($data['resources']),
        ], 'Homepage data loaded.');
    }
}
