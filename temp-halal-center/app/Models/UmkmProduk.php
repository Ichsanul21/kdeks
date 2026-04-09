<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmkmProduk extends Model
{
    use Sluggable;

    protected $fillable = [
        'umkm_id',
        'nomor',
        'nama_produk',
        'slug',
        'detail_url',
        'edit_url',
        'foto_url',
        'image_path',
        'harga',
        'lph_lp3h',
        'akta_halal',
        'tahun_terbit',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'nomor' => 'integer',
        ];
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class);
    }

    /**
     * Get the display image: prefer local upload, fallback to external URL.
     */
    public function getDisplayImageAttribute(): ?string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        return $this->foto_url;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama_produk',
            ],
        ];
    }
}
