<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LphPartnerResource;
use App\Http\Resources\RegionResource;
use App\Models\LphPartner;
use App\Models\Region;
use App\Models\Umkm;
use App\Support\ApiResponse;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $regions = Region::query()->orderBy('sort_order')->get();
        $partners = LphPartner::query()->where('is_active', true)->orderBy('sort_order')->get();

        $locations = Umkm::query()
            ->with(['region', 'lphPartner'])
            ->when($request->filled('category'), fn ($query) => $query->where('kategori', $request->string('category')->toString()))
            ->when($request->filled('region_id'), fn ($query) => $query->where('region_id', $request->integer('region_id')))
            ->when($request->filled('city'), function ($query) use ($request): void {
                $city = $request->string('city')->toString();

                $query->where(function ($builder) use ($city): void {
                    $builder
                        ->where('kab_kota', $city)
                        ->orWhereHas('region', fn ($regionQuery) => $regionQuery->where('name', $city));
                });
            })
            ->when($request->filled('lph_partner_id'), fn ($query) => $query->where('lph_partner_id', $request->integer('lph_partner_id')))
            ->when($request->filled('keyword'), function ($query) use ($request): void {
                $keyword = $request->string('keyword')->toString();

                $query->where(function ($builder) use ($keyword): void {
                    $builder
                        ->where('nama_umkm', 'like', "%{$keyword}%")
                        ->orWhere('nama_pemilik', 'like', "%{$keyword}%")
                        ->orWhere('kab_kota', 'like', "%{$keyword}%")
                        ->orWhere('kategori', 'like', "%{$keyword}%");
                });
            })
            ->where('status', 'published')
            ->get();

        $resolvedLocations = $locations->filter(function (Umkm $location): bool {
            return filled($location->latitude) && filled($location->longitude)
                || ($location->relationLoaded('region') && $location->region && filled($location->region->latitude) && filled($location->region->longitude));
        });

        if ($resolvedLocations->isEmpty()) {
            $resolvedLocations = $this->fallbackLocations($regions, $partners);
        }

        return ApiResponse::success([
            'regions' => RegionResource::collection($regions)->resolve(),
            'lph_partners' => LphPartnerResource::collection($partners)->resolve(),
            'locations' => $resolvedLocations->map(fn (Umkm $location) => [
                'id' => $location->id,
                'name' => $location->nama_umkm,
                'slug' => $location->slug,
                'category' => $location->kategori,
                'city_name' => $location->kab_kota ?: $location->region?->name,
                'address' => $location->alamat,
                'latitude' => (float) ($location->latitude ?: $location->region?->latitude),
                'longitude' => (float) ($location->longitude ?: $location->region?->longitude),
                'nomor_wa' => $location->nomor_wa,
                'foto_url' => $location->foto_url,
                'deskripsi' => $location->deskripsi,
                'nama_pemilik' => $location->nama_pemilik,
                'certificate_number' => null,
                'region' => [
                    'id' => $location->region?->id,
                    'name' => $location->region?->name,
                ],
                'lph_partner' => [
                    'id' => $location->lphPartner?->id,
                    'name' => $location->lphPartner?->name,
                ],
            ])->values()->all(),
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
            ->map(function (Region $region, int $index) use ($partners, $categories): Umkm {
                $partner = $partners->get($index % max(1, $partners->count()));

                $location = new Umkm([
                    'id' => 99999 + $region->id,
                    'nama_umkm' => 'UMKM '.$region->name,
                    'slug' => 'umkm-'.\Illuminate\Support\Str::slug($region->name).'-'.$region->id,
                    'kategori' => $categories[$index % count($categories)],
                    'kab_kota' => $region->name,
                    'alamat' => 'Sentra UMKM '.$region->name,
                    'latitude' => $region->latitude,
                    'longitude' => $region->longitude,
                    'nomor_wa' => null,
                    'foto_url' => null,
                    'deskripsi' => 'Sebaran UMKM/lokasi halal di wilayah ini.',
                    'nama_pemilik' => '-',
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
