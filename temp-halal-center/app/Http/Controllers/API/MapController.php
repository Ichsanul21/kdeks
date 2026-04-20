<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LphPartnerResource;
use App\Http\Resources\RegionResource;
use App\Models\HalalLocation;
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

        // Collect hierarchical filters
        $filters = Umkm::query()
            ->select('kab_kota', 'kecamatan', 'kelurahan')
            ->whereNotNull('kab_kota')
            ->get()
            ->concat(HalalLocation::query()
                ->select('city_name as kab_kota', 'kecamatan', 'kelurahan')
                ->whereNotNull('city_name')
                ->get())
            ->groupBy('kab_kota')
            ->map(function ($items) {
                return $items->groupBy('kecamatan')->map(function ($subItems) {
                    return $subItems->pluck('kelurahan')->filter()->unique()->sort()->values();
                })->forget('');
            })->forget('');

        $locations = Umkm::query()
            ->with(['region', 'lphPartner'])
            ->when($request->filled('category'), fn ($query) => $query->where('kategori', $request->string('category')->toString()))
            ->when($request->filled('region_id'), fn ($query) => $query->where('region_id', $request->integer('region_id')))
            ->when($request->filled('kecamatan'), fn ($query) => $query->where('kecamatan', $request->string('kecamatan')->toString()))
            ->when($request->filled('kelurahan'), fn ($query) => $query->where('kelurahan', $request->string('kelurahan')->toString()))
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

        $halalLocations = HalalLocation::query()
            ->with(['region', 'lphPartner'])
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')->toString()))
            ->when($request->filled('region_id'), fn ($query) => $query->where('region_id', $request->integer('region_id')))
            ->when($request->filled('kecamatan'), fn ($query) => $query->where('kecamatan', $request->string('kecamatan')->toString()))
            ->when($request->filled('kelurahan'), fn ($query) => $query->where('kelurahan', $request->string('kelurahan')->toString()))
            ->when($request->filled('city'), function ($query) use ($request): void {
                $city = $request->string('city')->toString();
                $query->where(function ($builder) use ($city): void {
                    $builder->where('city_name', $city)
                        ->orWhereHas('region', fn ($regionQuery) => $regionQuery->where('name', $city));
                });
            })
            ->when($request->filled('lph_partner_id'), fn ($query) => $query->where('lph_partner_id', $request->integer('lph_partner_id')))
            ->when($request->filled('keyword'), function ($query) use ($request): void {
                $keyword = $request->string('keyword')->toString();
                $query->where(function ($builder) use ($keyword): void {
                    $builder->where('name', 'like', "%{$keyword}%")
                        ->orWhere('owner_name', 'like', "%{$keyword}%")
                        ->orWhere('city_name', 'like', "%{$keyword}%")
                        ->orWhere('category', 'like', "%{$keyword}%");
                });
            })
            ->where('status', 'published')
            ->get();

        $mergedLocations = $locations->concat($halalLocations->map(function ($loc) {
            $u = new Umkm([
                'id' => $loc->id + 1000000, // Offset to avoid ID collision in collection
                'nama_umkm' => $loc->name,
                'slug' => $loc->slug,
                'kategori' => $loc->category,
                'kab_kota' => $loc->city_name,
                'alamat' => $loc->address,
                'latitude' => $loc->latitude,
                'longitude' => $loc->longitude,
                'nomor_wa' => $loc->phone,
                'foto_url' => $loc->image_path ? asset('storage/' . $loc->image_path) : null,
                'deskripsi' => $loc->description,
                'nama_pemilik' => $loc->owner_name,
                'region_id' => $loc->region_id,
                'lph_partner_id' => $loc->lph_partner_id,
            ]);
            $u->setRelation('region', $loc->region);
            $u->setRelation('lphPartner', $loc->lphPartner);
            return $u;
        }));

        $resolvedLocations = $mergedLocations->filter(function (Umkm $location): bool {
            $lat = (float) ($location->latitude ?: $location->region?->latitude);
            $lng = (float) ($location->longitude ?: $location->region?->longitude);

            if (! $lat || ! $lng) {
                return false;
            }

            // Bounding box filter for Kalimantan region (roughly)
            // Anything clearly in Java (lat < -6) or too far east/west is excluded.
            $isOutsideKaltim = $lat < -4.5 || $lat > 6.5 || $lng < 113 || $lng > 120.5;

            return ! $isOutsideKaltim;
        })->map(function (Umkm $location) {
            $lat = (float) ($location->latitude ?: $location->region?->latitude);
            $lng = (float) ($location->longitude ?: $location->region?->longitude);

            // If coordinates are exactly the fallback region center, add a tiny jitter
            // so they don't stack perfectly on top of each other.
            if ($location->region && abs($lat - $location->region->latitude) < 0.00001 && abs($lng - $location->region->longitude) < 0.00001) {
                // Deterministic jitter based on ID so it remains relatively stable
                $jitterLat = (fmod($location->id * 0.1313, 1) - 0.5) * 0.015;
                $jitterLng = (fmod($location->id * 0.1717, 1) - 0.5) * 0.015;
                $lat += $jitterLat;
                $lng += $jitterLng;
            }

            $location->latitude = $lat;
            $location->longitude = $lng;
            return $location;
        });

        if ($resolvedLocations->isEmpty()) {
            $resolvedLocations = $this->fallbackLocations($regions, $partners);
        }

        return ApiResponse::success([
            'regions' => RegionResource::collection($regions)->resolve(),
            'lph_partners' => LphPartnerResource::collection($partners)->resolve(),
            'filters' => $filters,
            'locations' => $resolvedLocations->map(fn (Umkm $location) => [
                'id' => $location->id,
                'name' => $location->nama_umkm,
                'slug' => $location->slug,
                'category' => $location->kategori,
                'city_name' => $location->kab_kota ?: $location->region?->name,
                'address' => $location->alamat,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
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
