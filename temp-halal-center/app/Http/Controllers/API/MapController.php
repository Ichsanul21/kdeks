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
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $regions = Region::query()->orderBy('sort_order')->get();
        $partners = LphPartner::query()->where('is_active', true)->orderBy('sort_order')->get();

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

        $resolvedLocations = $locations->filter(function (HalalLocation $location): bool {
            return filled($location->latitude) && filled($location->longitude)
                || ($location->relationLoaded('region') && $location->region && filled($location->region->latitude) && filled($location->region->longitude));
        });

        if ($resolvedLocations->isEmpty()) {
            $resolvedLocations = $this->fallbackLocations($regions, $partners);
        }

        return ApiResponse::success([
            'regions' => RegionResource::collection($regions)->resolve(),
            'lph_partners' => LphPartnerResource::collection($partners)->resolve(),
            'locations' => HalalLocationResource::collection($resolvedLocations)->resolve(),
        ], 'Map data loaded.');
    }

    protected function fallbackLocations(Collection $regions, Collection $partners): Collection
    {
        $categories = [
            'Makanan',
            'Minuman',
            'Wisata Ramah',
            'Produk Halal Lainnya',
        ];

        return $regions
            ->filter(fn (Region $region) => filled($region->latitude) && filled($region->longitude))
            ->values()
            ->map(function (Region $region, int $index) use ($partners, $categories): HalalLocation {
                $partner = $partners->get($index % max(1, $partners->count()));

                $location = new HalalLocation([
                    'id' => 'fallback-'.$region->id,
                    'name' => 'UMKM '.$region->name,
                    'slug' => null,
                    'location_type' => 'umkm',
                    'category' => $categories[$index % count($categories)],
                    'city_name' => $region->name,
                    'business_scale' => 'UMKM',
                    'brand_name' => 'UMKM '.$region->name,
                    'product_name' => 'Produk Halal '.$region->name,
                    'address' => 'Sentra UMKM '.$region->name,
                    'latitude' => $region->latitude,
                    'longitude' => $region->longitude,
                    'certificate_number' => null,
                    'description' => 'Titik fallback otomatis berdasarkan wilayah.',
                    'status' => 'published',
                    'region_id' => $region->id,
                    'lph_partner_id' => $partner?->id,
                ]);

                $location->setRelation('region', $region);
                if ($partner) {
                    $location->setRelation('lphPartner', $partner);
                }

                return $location;
            });
    }
}
