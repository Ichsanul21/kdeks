<?php

namespace App\Exports;

use App\Models\Umkm;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class UmkmSummarySheet implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function query()
    {
        return Umkm::query()->orderByRaw('COALESCE(source_id, id)');
    }

    public function headings(): array
    {
        return [
            'nomor',
            'id',
            'detail_url',
            'edit_url',
            'foto_url',
            'nama_pemilik',
            'nama_umkm',
            'lembaga',
            'alamat',
            'kategori',
            'provinsi',
            'kab_kota',
            'approval',
        ];
    }

    public function map($umkm): array
    {
        return [
            $umkm->nomor,
            $umkm->source_id ?? $umkm->id,
            $umkm->detail_url,
            $umkm->edit_url,
            $umkm->foto_url,
            $umkm->nama_pemilik,
            $umkm->nama_umkm,
            $umkm->lembaga,
            $umkm->alamat,
            $umkm->kategori,
            $umkm->provinsi,
            $umkm->kab_kota,
            $umkm->approval,
        ];
    }

    public function title(): string
    {
        return 'UMKM Ringkas';
    }
}
