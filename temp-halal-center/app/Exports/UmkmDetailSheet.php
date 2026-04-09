<?php

namespace App\Exports;

use App\Models\Umkm;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class UmkmDetailSheet implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function query()
    {
        return Umkm::query()->withCount('produks')->orderByRaw('COALESCE(source_id, id)');
    }

    public function headings(): array
    {
        return [
            'id',
            'nomor',
            'nama_pemilik',
            'nama_umkm',
            'lembaga',
            'kategori',
            'provinsi',
            'kab_kota',
            'approval',
            'detail_url',
            'edit_url',
            'foto_url',
            'nomor_wa',
            'link_pembelian',
            'latitude_longitude',
            'deskripsi_detail',
            'jumlah_produk',
        ];
    }

    public function map($umkm): array
    {
        $latitudeLongitude = null;

        if ($umkm->latitude !== null && $umkm->longitude !== null) {
            $latitudeLongitude = "{$umkm->latitude}, {$umkm->longitude}";
        }

        return [
            $umkm->source_id ?? $umkm->id,
            $umkm->nomor,
            $umkm->nama_pemilik,
            $umkm->nama_umkm,
            $umkm->lembaga,
            $umkm->kategori,
            $umkm->provinsi,
            $umkm->kab_kota,
            $umkm->approval,
            $umkm->detail_url,
            $umkm->edit_url,
            $umkm->foto_url,
            $umkm->nomor_wa,
            $umkm->link_pembelian,
            $latitudeLongitude,
            $umkm->deskripsi,
            $umkm->jumlah_produk ?? $umkm->produks_count,
        ];
    }

    public function title(): string
    {
        return 'UMKM Detail';
    }
}
