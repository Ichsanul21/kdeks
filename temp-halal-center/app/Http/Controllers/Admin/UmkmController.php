<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UmkmExport;
use App\Exports\UmkmDetailSheet;
use App\Exports\UmkmProdukSheet;
use App\Exports\UmkmSummarySheet;
use App\Http\Requests\UmkmRequest;
use App\Imports\UmkmImport;
use App\Models\LphPartner;
use App\Models\Region;
use App\Models\Umkm;
use App\Models\UmkmProduk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class UmkmController extends BaseCrudController
{
    protected string $modelClass = Umkm::class;
    protected string $pageTitle = 'Manajemen UMKM';
    protected string $routePrefix = 'admin.umkms';
    protected string $requestClass = UmkmRequest::class;
    protected ?string $publicIndexRoute = null;
    protected ?string $publicShowRoute = null;
    protected array $searchColumns = ['source_id', 'nama_umkm', 'nama_pemilik', 'kategori', 'kab_kota', 'alamat', 'lembaga', 'approval'];
    protected array $tableColumns = [
        ['key' => 'source_id', 'label' => 'ID Sumber'],
        ['key' => 'nama_umkm', 'label' => 'Nama UMKM'],
        ['key' => 'nama_pemilik', 'label' => 'Pemilik'],
        ['key' => 'kab_kota', 'label' => 'Kab/Kota'],
        ['key' => 'kategori', 'label' => 'Kategori'],
        ['key' => 'approval', 'label' => 'Approval'],
    ];
    protected array $publicFileFields = ['image_path'];

    protected function indexQuery()
    {
        return Umkm::query()->withCount('produks')->orderByDesc('source_id')->orderByDesc('id');
    }

    public function index(Request $request): View
    {
        $query = $this->indexQuery();

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search): void {
                foreach ($this->searchColumns as $column) {
                    $builder->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        $items = $query->latest()->paginate(15)->withQueryString();

        return view('admin.umkms.index', [
            'pageTitle' => $this->pageTitle,
            'routePrefix' => $this->routePrefix,
            'items' => $items,
            'tableColumns' => $this->tableColumns,
            'publicIndexRoute' => $this->publicIndexRoute,
            'publicShowRoute' => $this->publicShowRoute,
            'publicShowRouteKey' => $this->publicShowRouteKey,
            'totalUmkm' => Umkm::count(),
            'totalProduk' => UmkmProduk::count(),
        ]);
    }

    protected function resolvedFields(): array
    {
        $regions = Region::query()->orderBy('name')->pluck('name', 'id')->all();
        $lphPartners = LphPartner::query()->orderBy('name')->pluck('name', 'id')->all();

        return [
            ['name' => 'source_id', 'label' => 'ID Sumber', 'type' => 'number'],
            ['name' => 'nomor', 'label' => 'Nomor Urut', 'type' => 'number'],
            ['name' => 'nama_umkm', 'label' => 'Nama UMKM', 'type' => 'text', 'required' => true],
            ['name' => 'nama_pemilik', 'label' => 'Nama Pemilik', 'type' => 'text'],
            ['name' => 'lembaga', 'label' => 'Lembaga', 'type' => 'text'],
            ['name' => 'region_id', 'label' => 'Wilayah', 'type' => 'select', 'options' => $regions],
            ['name' => 'lph_partner_id', 'label' => 'LPH / LP3H', 'type' => 'select', 'options' => $lphPartners],
            ['name' => 'kategori', 'label' => 'Kategori', 'type' => 'select', 'options' => [
                'Makanan Halal' => 'Makanan Halal',
                'Minuman Halal' => 'Minuman Halal',
                'Wisata Ramah' => 'Wisata Ramah',
                'Unit Usaha Ponpes' => 'Unit Usaha Ponpes',
                'Produk Halal Lainya' => 'Produk Halal Lainya',
                'Rumah Potong' => 'Rumah Potong',
                'Industri Kreatif' => 'Industri Kreatif',
                'Perbankan Syariah' => 'Perbankan Syariah',
                'Lembaga Keuangan' => 'Lembaga Keuangan',
            ]],
            ['name' => 'provinsi', 'label' => 'Provinsi', 'type' => 'text'],
            ['name' => 'kab_kota', 'label' => 'Kabupaten / Kota', 'type' => 'text'],
            ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'id' => 'location-address'],
            ['name' => 'detail_url', 'label' => 'URL Detail', 'type' => 'url'],
            ['name' => 'edit_url', 'label' => 'URL Edit', 'type' => 'url'],
            ['name' => 'location_picker', 'label' => 'Pilih Titik Peta', 'type' => 'map-picker', 'latitude_target' => 'location-latitude', 'longitude_target' => 'location-longitude', 'address_target' => 'location-address'],
            ['name' => 'latitude', 'label' => 'Latitude', 'type' => 'number', 'step' => '0.0000001', 'id' => 'location-latitude'],
            ['name' => 'longitude', 'label' => 'Longitude', 'type' => 'number', 'step' => '0.0000001', 'id' => 'location-longitude'],
            ['name' => 'nomor_wa', 'label' => 'Nomor WhatsApp', 'type' => 'text'],
            ['name' => 'link_pembelian', 'label' => 'Link Pembelian', 'type' => 'url'],
            ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'richtext'],
            ['name' => 'foto_url', 'label' => 'URL Foto Eksternal', 'type' => 'url'],
            ['name' => 'jumlah_produk', 'label' => 'Jumlah Produk', 'type' => 'number'],
            ['name' => 'approval', 'label' => 'Approval', 'type' => 'select', 'options' => ['DISETUJUI' => 'DISETUJUI', 'MENUNGGU' => 'MENUNGGU', 'DITOLAK' => 'DITOLAK']],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['published' => 'Published', 'draft' => 'Draft']],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
            ['name' => 'image_path', 'label' => 'Upload Gambar', 'type' => 'image'],
        ];
    }

    public function edit(string $id): View
    {
        $item = $this->findModel($id);
        $item->load('produks');

        return view('admin.umkms.form', [
            'pageTitle' => $this->pageTitle,
            'routePrefix' => $this->routePrefix,
            'item' => $item,
            'mode' => 'edit',
            'formFields' => $this->resolvedFields(),
            'publicFileFields' => $this->publicFileFields,
            'privateFileFields' => $this->privateFileFields,
            'publicShowRoute' => $this->publicShowRoute,
            'publicShowRouteKey' => $this->publicShowRouteKey,
        ]);
    }

    public function create(): View
    {
        return view('admin.umkms.form', [
            'pageTitle' => $this->pageTitle,
            'routePrefix' => $this->routePrefix,
            'item' => new Umkm(),
            'mode' => 'create',
            'formFields' => $this->resolvedFields(),
            'publicFileFields' => $this->publicFileFields,
            'privateFileFields' => $this->privateFileFields,
            'publicShowRoute' => $this->publicShowRoute,
            'publicShowRouteKey' => $this->publicShowRouteKey,
        ]);
    }

    /**
     * Import UMKM data from CSV/XLSX.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'import_file' => ['nullable', 'file', 'mimes:csv,xlsx,xls,txt', 'max:20480'],
            'umkm_file' => ['nullable', 'file', 'mimes:csv,txt', 'max:20480'],
            'umkm_detail_file' => ['nullable', 'file', 'mimes:csv,txt', 'max:20480'],
            'produk_file' => ['nullable', 'file', 'mimes:csv,txt', 'max:20480'],
        ]);

        if (! $request->hasFile('import_file') && ! $request->hasFile('umkm_file') && ! $request->hasFile('umkm_detail_file') && ! $request->hasFile('produk_file')) {
            return redirect()->route("{$this->routePrefix}.index")
                ->withErrors(['import_file' => 'Unggah satu file XLSX/CSV atau kombinasi file CSV UMKM, detail, dan produk.']);
        }

        $import = new UmkmImport();
        $import->import(
            $request->file('import_file'),
            $request->file('umkm_file'),
            $request->file('umkm_detail_file'),
            $request->file('produk_file'),
        );

        $stats = $import->getStats();

        return redirect()->route("{$this->routePrefix}.index")
            ->with('status', "Import berhasil! {$stats['umkm']} UMKM dan {$stats['produk']} produk diproses. {$stats['skipped']} baris dilewati.");
    }

    /**
     * Export UMKM data to XLSX.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'xlsx');
        $filename = 'umkm-export-' . now()->format('Y-m-d-His');

        if ($format === 'csv') {
            return $this->downloadCsvBundle($filename);
        }

        return Excel::download(new UmkmExport(), "{$filename}.xlsx");
    }

    protected function downloadCsvBundle(string $filename): BinaryFileResponse
    {
        $temporaryDirectory = storage_path('app/temp');
        File::ensureDirectoryExists($temporaryDirectory);

        $zipPath = $temporaryDirectory . DIRECTORY_SEPARATOR . "{$filename}.zip";

        if (File::exists($zipPath)) {
            File::delete($zipPath);
        }

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('umkm-terverifikasi.csv', $this->buildSummaryCsv());
        $zip->addFromString('umkm-terverifikasi-detail.csv', $this->buildDetailCsv());
        $zip->addFromString('umkm-terverifikasi-produk.csv', $this->buildProdukCsv());
        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    protected function buildSummaryCsv(): string
    {
        $sheet = new UmkmSummarySheet();
        $rows = Umkm::query()->orderByRaw('COALESCE(source_id, id)')->get();

        return $this->buildCsvString($sheet->headings(), $rows->map(fn (Umkm $umkm): array => $sheet->map($umkm))->all());
    }

    protected function buildDetailCsv(): string
    {
        $sheet = new UmkmDetailSheet();
        $rows = Umkm::query()->withCount('produks')->orderByRaw('COALESCE(source_id, id)')->get();

        return $this->buildCsvString($sheet->headings(), $rows->map(fn (Umkm $umkm): array => $sheet->map($umkm))->all());
    }

    protected function buildProdukCsv(): string
    {
        $sheet = new UmkmProdukSheet();
        $rows = UmkmProduk::query()->with('umkm')->orderBy('umkm_id')->orderBy('nomor')->get();

        return $this->buildCsvString($sheet->headings(), $rows->map(fn (UmkmProduk $produk): array => $sheet->map($produk))->all());
    }

    protected function buildCsvString(array $headings, array $rows): string
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headings);

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle) ?: '';
        fclose($handle);

        return $csv;
    }
}
