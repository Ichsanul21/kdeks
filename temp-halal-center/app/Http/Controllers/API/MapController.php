<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\HalalLocationResource;
use App\Http\Resources\LphPartnerResource;
use App\Http\Resources\RegionResource;
use App\Models\HalalLocation;
use App\Models\LphPartner;
use App\Models\Region;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $locations = HalalLocation::query()
            ->with(['region', 'lphPartner'])
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')->toString()))
            ->when($request->filled('location_type'), fn ($query) => $query->where('location_type', $request->string('location_type')->toString()))
            ->when($request->filled('region_id'), fn ($query) => $query->where('region_id', $request->integer('region_id')))
            ->when($request->filled('city'), function ($query) use ($request): void {
                $city = $request->string('city')->toString();

                $query->where(function ($builder) use ($city): void {
                    $builder
                        ->where('city_name', $city)
                        ->orWhereHas('region', fn ($regionQuery) => $regionQuery->where('name', $city));
                });
            })
            ->when($request->filled('lph_partner_id'), fn ($query) => $query->where('lph_partner_id', $request->integer('lph_partner_id')))
            ->when($request->filled('keyword'), function ($query) use ($request): void {
                $keyword = $request->string('keyword')->toString();

                $query->where(function ($builder) use ($keyword): void {
                    $builder
                        ->where('name', 'like', "%{$keyword}%")
                        ->orWhere('brand_name', 'like', "%{$keyword}%")
                        ->orWhere('product_name', 'like', "%{$keyword}%")
                        ->orWhere('city_name', 'like', "%{$keyword}%")
                        ->orWhere('category', 'like', "%{$keyword}%");
                });
            })
            ->where('status', 'published')
            ->get();

        return ApiResponse::success([
            'regions' => RegionResource::collection(Region::query()->orderBy('sort_order')->get())->resolve(),
            'lph_partners' => LphPartnerResource::collection(LphPartner::query()->where('is_active', true)->orderBy('sort_order')->get())->resolve(),
            'locations' => HalalLocationResource::collection($locations)->resolve(),
        ], 'Map data loaded.');
    }
}
