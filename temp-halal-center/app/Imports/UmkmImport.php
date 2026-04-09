<?php

namespace App\Imports;

use App\Models\LphPartner;
use App\Models\Region;
use App\Models\Umkm;
use App\Models\UmkmProduk;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class UmkmImport
{
    protected int $importedUmkm = 0;
    protected int $importedProduk = 0;
    protected int $skipped = 0;

    protected ?array $regionCache = null;
    protected ?array $lphCache = null;

    public function import(
        ?UploadedFile $bundleFile = null,
        ?UploadedFile $summaryFile = null,
        ?UploadedFile $detailFile = null,
        ?UploadedFile $produkFile = null,
    ): void {
        if ($bundleFile) {
            $extension = Str::lower($bundleFile->getClientOriginalExtension());

            if (in_array($extension, ['xlsx', 'xls'], true)) {
                Excel::import(new UmkmWorkbookImport($this), $bundleFile);
            } else {
                $this->importStandaloneCsv($bundleFile);
            }
        }

        if ($summaryFile) {
            Excel::import(new UmkmSummarySheetImport($this), $summaryFile);
        }

        if ($detailFile) {
            Excel::import(new UmkmDetailSheetImport($this), $detailFile);
        }

        if ($produkFile) {
            Excel::import(new UmkmProdukSheetImport($this), $produkFile);
        }

        $this->syncJumlahProduk();
    }

    public function getStats(): array
    {
        return [
            'umkm' => $this->importedUmkm,
            'produk' => $this->importedProduk,
            'skipped' => $this->skipped,
        ];
    }

    public function importUmkmRow(array $row): void
    {
        try {
            $sourceId = $this->resolveSourceId($row);
            $namaUmkm = $this->cleanValue($row['nama_umkm'] ?? null);

            if (! $sourceId && ! $namaUmkm) {
                $this->skipped++;
                return;
            }

            $existing = $this->findExistingUmkm($sourceId, $namaUmkm, $this->cleanValue($row['nama_pemilik'] ?? null));

            [$latitude, $longitude] = $this->resolveCoordinates($row, $existing);

            $kabKota = $this->cleanValue($row['kab_kota'] ?? null) ?? $existing?->kab_kota;
            $lembaga = $this->cleanValue($row['lembaga'] ?? null) ?? $existing?->lembaga;

            $data = [
                'source_id' => $sourceId,
                'nomor' => $this->resolveInteger($row['nomor'] ?? null) ?? $existing?->nomor,
                'nama_umkm' => $namaUmkm ?? $existing?->nama_umkm,
                'nama_pemilik' => $this->cleanValue($row['nama_pemilik'] ?? null) ?? $existing?->nama_pemilik,
                'lembaga' => $lembaga,
                'kategori' => $this->cleanValue($row['kategori'] ?? null) ?? $existing?->kategori,
                'provinsi' => $this->cleanValue($row['provinsi'] ?? null) ?? $existing?->provinsi ?? 'KALIMANTAN TIMUR',
                'kab_kota' => $kabKota,
                'alamat' => $this->cleanValue($row['alamat'] ?? null) ?? $existing?->alamat,
                'detail_url' => $this->cleanValue($row['detail_url'] ?? null) ?? $existing?->detail_url,
                'edit_url' => $this->cleanValue($row['edit_url'] ?? null) ?? $existing?->edit_url,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'nomor_wa' => $this->cleanValue($row['nomor_wa'] ?? null) ?? $existing?->nomor_wa,
                'link_pembelian' => $this->cleanValue($row['link_pembelian'] ?? null) ?? $existing?->link_pembelian,
                'deskripsi' => $this->cleanValue($row['deskripsi_detail'] ?? $row['deskripsi'] ?? null) ?? $existing?->deskripsi,
                'foto_url' => $this->cleanValue($row['foto_url'] ?? null) ?? $existing?->foto_url,
                'jumlah_produk' => $this->resolveInteger($row['jumlah_produk'] ?? null) ?? $existing?->jumlah_produk,
                'approval' => $this->cleanValue($row['approval'] ?? null) ?? $existing?->approval ?? 'DISETUJUI',
                'status' => $this->cleanValue($row['status'] ?? null) ?? $existing?->status ?? 'published',
                'region_id' => $this->resolveRegionId($kabKota) ?? $existing?->region_id,
                'lph_partner_id' => $this->resolveLphId($lembaga) ?? $existing?->lph_partner_id,
            ];

            if (! $data['nama_umkm']) {
                $this->skipped++;
                return;
            }

            $lookup = $sourceId
                ? ['source_id' => $sourceId]
                : ['nama_umkm' => $data['nama_umkm'], 'nama_pemilik' => $data['nama_pemilik']];

            Umkm::updateOrCreate($lookup, array_filter(
                $data,
                static fn ($value): bool => $value !== null
            ));

            $this->importedUmkm++;
        } catch (Throwable $e) {
            report($e);
            $this->skipped++;
        }
    }

    public function importProdukRow(array $row): void
    {
        try {
            $namaProduk = $this->cleanValue($row['nama_produk'] ?? null);
            $umkmSourceId = $this->resolveSourceId(['id' => $row['umkm_id'] ?? null]);

            if (! $namaProduk || ! $umkmSourceId) {
                $this->skipped++;
                return;
            }

            $umkm = Umkm::query()->where('source_id', $umkmSourceId)->first();

            if (! $umkm) {
                $umkm = Umkm::query()
                    ->where('nama_umkm', $this->cleanValue($row['nama_umkm'] ?? null))
                    ->where('nama_pemilik', $this->cleanValue($row['nama_pemilik'] ?? null))
                    ->first();
            }

            if (! $umkm) {
                $this->skipped++;
                return;
            }

            $editUrl = $this->cleanValue($row['produk_edit_url'] ?? $row['edit_url'] ?? null);

            UmkmProduk::updateOrCreate(
                $editUrl
                    ? ['edit_url' => $editUrl]
                    : ['umkm_id' => $umkm->id, 'nama_produk' => $namaProduk],
                array_filter([
                    'umkm_id' => $umkm->id,
                    'nomor' => $this->resolveInteger($row['produk_nomor'] ?? $row['nomor'] ?? null),
                    'nama_produk' => $namaProduk,
                    'detail_url' => $this->cleanValue($row['produk_detail_url'] ?? $row['detail_url'] ?? null),
                    'edit_url' => $editUrl,
                    'foto_url' => $this->cleanValue($row['produk_foto_url'] ?? $row['foto_url'] ?? null),
                    'harga' => $this->cleanValue($row['harga'] ?? null),
                    'lph_lp3h' => $this->cleanValue($row['lph_lp3h'] ?? null),
                    'akta_halal' => $this->cleanValue($row['akta_halal'] ?? null),
                    'tahun_terbit' => $this->cleanValue($row['tahun_terbit'] ?? null),
                    'deskripsi' => $this->cleanValue($row['deskripsi'] ?? null),
                ], static fn ($value): bool => $value !== null)
            );

            $this->importedProduk++;
        } catch (Throwable $e) {
            report($e);
            $this->skipped++;
        }
    }

    protected function importStandaloneCsv(UploadedFile $file): void
    {
        $filename = Str::lower($file->getClientOriginalName());

        if (str_contains($filename, 'produk')) {
            Excel::import(new UmkmProdukSheetImport($this), $file);
            return;
        }

        if (str_contains($filename, 'detail')) {
            Excel::import(new UmkmDetailSheetImport($this), $file);
            return;
        }

        Excel::import(new UmkmSummarySheetImport($this), $file);
    }

    protected function findExistingUmkm(?int $sourceId, ?string $namaUmkm, ?string $namaPemilik): ?Umkm
    {
        if ($sourceId) {
            return Umkm::query()->where('source_id', $sourceId)->first();
        }

        if (! $namaUmkm) {
            return null;
        }

        return Umkm::query()
            ->where('nama_umkm', $namaUmkm)
            ->where('nama_pemilik', $namaPemilik)
            ->first();
    }

    protected function resolveCoordinates(array $row, ?Umkm $existing): array
    {
        $latitude = null;
        $longitude = null;

        // 1) Try dedicated latitude/longitude columns
        $rawLat = $this->cleanValue($row['latitude'] ?? $row['lat'] ?? null);
        $rawLng = $this->cleanValue($row['longitude'] ?? $row['long'] ?? $row['lng'] ?? null);

        if ($this->isValidLatitude($rawLat) && $this->isValidLongitude($rawLng)) {
            $latitude = (float) $rawLat;
            $longitude = (float) $rawLng;
        }

        // 2) Try combined latitude_longitude column (e.g. "-0.52, 117.08")
        if ($latitude === null) {
            $latLng = null;
            $possibleKeys = ['latitude_longitude', 'lat_long', 'lat_lng', 'koordinat', 'koordinat_lokasi', 'location'];

            foreach ($possibleKeys as $key) {
                if (!empty($row[$key])) {
                    $latLng = $this->cleanValue($row[$key]);
                    break;
                }
            }

            // Also scan for any key containing both 'lat' and 'long'
            if (!$latLng) {
                foreach ($row as $key => $value) {
                    if (is_string($key) && str_contains($key, 'lat') && str_contains($key, 'long')) {
                        $latLng = $this->cleanValue($value);
                        break;
                    }
                }
            }

            if ($latLng && str_contains($latLng, ',')) {
                [$lat, $lng] = array_map('trim', explode(',', $latLng, 2));
                if ($this->isValidLatitude($lat) && $this->isValidLongitude($lng)) {
                    $latitude = (float) $lat;
                    $longitude = (float) $lng;
                }
            }
        }

        // 3) Handle CSV comma-split: if latitude_longitude was "-0.52, 117.08" the CSV
        //    parser splits it into two columns. The first part lands in latitude_longitude
        //    as just "-0.52", and the second part ("117.08") shifts into the next column.
        //    Detect this by checking if latitude_longitude contains a valid latitude
        //    without a comma, and the very next key in the row holds a valid longitude.
        if ($latitude === null) {
            $keys = array_keys($row);
            foreach ($possibleKeys ?? ['latitude_longitude'] as $key) {
                $idx = array_search($key, $keys, true);
                if ($idx === false) {
                    continue;
                }
                $candidateLat = $this->cleanValue($row[$key] ?? null);
                if (!$this->isValidLatitude($candidateLat)) {
                    continue;
                }
                // Check the next column for a valid longitude
                $nextKey = $keys[$idx + 1] ?? null;
                if ($nextKey !== null) {
                    $candidateLng = $this->cleanValue($row[$nextKey] ?? null);
                    if ($this->isValidLongitude($candidateLng)) {
                        $latitude = (float) $candidateLat;
                        $longitude = (float) $candidateLng;
                        break;
                    }
                }
            }
        }

        return [
            $latitude ?? $existing?->latitude,
            $longitude ?? $existing?->longitude,
        ];
    }

    protected function isValidLatitude(mixed $value): bool
    {
        return is_numeric($value) && (float) $value >= -90 && (float) $value <= 90;
    }

    protected function isValidLongitude(mixed $value): bool
    {
        return is_numeric($value) && (float) $value >= -180 && (float) $value <= 180;
    }

    protected function resolveSourceId(mixed $value): ?int
    {
        if (is_array($value)) {
            $value = $value['source_id'] ?? $value['id'] ?? null;
        }

        $value = $this->cleanValue($value);

        return is_numeric($value) ? (int) $value : null;
    }

    protected function resolveInteger(mixed $value): ?int
    {
        $value = $this->cleanValue($value);

        return is_numeric($value) ? (int) $value : null;
    }

    protected function cleanValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' || $value === '-' ? null : $value;
    }

    protected function resolveRegionId(?string $kabKota): ?int
    {
        if (! $kabKota) {
            return null;
        }

        if ($this->regionCache === null) {
            $this->regionCache = Region::query()
                ->get()
                ->mapWithKeys(static fn (Region $region): array => [
                    Str::lower(trim($region->name)) => $region->id,
                ])
                ->all();
        }

        return $this->regionCache[Str::lower(trim($kabKota))] ?? null;
    }

    protected function resolveLphId(?string $lembaga): ?int
    {
        if (! $lembaga) {
            return null;
        }

        if ($this->lphCache === null) {
            $this->lphCache = LphPartner::query()
                ->get()
                ->mapWithKeys(static fn (LphPartner $partner): array => [
                    Str::lower(trim($partner->name)) => $partner->id,
                ])
                ->all();
        }

        return $this->lphCache[Str::lower(trim($lembaga))] ?? null;
    }

    protected function syncJumlahProduk(): void
    {
        Umkm::query()
            ->withCount('produks')
            ->get()
            ->each(function (Umkm $umkm): void {
                $umkm->forceFill([
                    'jumlah_produk' => $umkm->produks_count,
                ])->saveQuietly();
            });
    }
}

class UmkmWorkbookImport implements WithMultipleSheets
{
    public function __construct(protected UmkmImport $parent)
    {
    }

    public function sheets(): array
    {
        return [
            'UMKM Ringkas' => new UmkmSummarySheetImport($this->parent),
            'UMKM Detail' => new UmkmDetailSheetImport($this->parent),
            'Produk UMKM' => new UmkmProdukSheetImport($this->parent),
            'UMKM' => new UmkmDetailSheetImport($this->parent),
            'Produk' => new UmkmProdukSheetImport($this->parent),
            0 => new UmkmSummarySheetImport($this->parent),
            1 => new UmkmDetailSheetImport($this->parent),
            2 => new UmkmProdukSheetImport($this->parent),
        ];
    }
}

class UmkmSummarySheetImport implements ToCollection, WithHeadingRow
{
    public function __construct(protected UmkmImport $parent)
    {
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $this->parent->importUmkmRow($row->toArray());
        }
    }
}

class UmkmDetailSheetImport implements ToCollection, WithHeadingRow
{
    public function __construct(protected UmkmImport $parent)
    {
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $this->parent->importUmkmRow($row->toArray());
        }
    }
}

class UmkmProdukSheetImport implements ToCollection, WithHeadingRow
{
    public function __construct(protected UmkmImport $parent)
    {
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $this->parent->importProdukRow($row->toArray());
        }
    }
}
