<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UmkmExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'UMKM Ringkas' => new UmkmSummarySheet(),
            'UMKM Detail' => new UmkmDetailSheet(),
            'Produk UMKM' => new UmkmProdukSheet(),
        ];
    }
}
