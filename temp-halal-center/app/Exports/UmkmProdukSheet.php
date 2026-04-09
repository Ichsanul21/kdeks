<?php

namespace App\Exports;

use App\Models\UmkmProduk;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class UmkmProdukSheet implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function query()
    {
        return UmkmProduk::query()->with('umkm')->orderBy('umkm_id')->orderBy('nomor');
    }

    public function headings(): array
    {
        return [
            'umkm_id',
            'nama_umkm',
            'nama_pemilik',
            'umkm_detail_url',
            'produk_nomor',
            'produk_detail_url',
            'produk_edit_url',
            'produk_foto_url',
            'nama_produk',
            'harga',
            'lph_lp3h',
            'akta_halal',
            'tahun_terbit',
            'deskripsi',
        ];
    }

    public function map($produk): array
    {
        return [
            $produk->umkm?->source_id ?? $produk->umkm_id,
            $produk->umkm?->nama_umkm,
            $produk->umkm?->nama_pemilik,
            $produk->umkm?->detail_url,
            $produk->nomor,
            $produk->detail_url,
            $produk->edit_url,
            $produk->foto_url,
            $produk->nama_produk,
            $produk->harga,
            $produk->lph_lp3h,
            $produk->akta_halal,
            $produk->tahun_terbit,
            $produk->deskripsi,
        ];
    }

    public function title(): string
    {
        return 'Produk UMKM';
    }
}
