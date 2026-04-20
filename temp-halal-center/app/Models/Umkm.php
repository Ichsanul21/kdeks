<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Umkm extends Model
{
    use Sluggable;
    
    protected static function booted()
    {
        static::saved(function ($umkm) {
            if ($umkm->isDirty('region_id') || $umkm->wasRecentlyCreated) {
                if ($umkm->getOriginal('region_id')) {
                    self::syncRegionCount($umkm->getOriginal('region_id'));
                }
                if ($umkm->region_id) {
                    self::syncRegionCount($umkm->region_id);
                }
            }
        });

        static::deleted(function ($umkm) {
            if ($umkm->region_id) {
                self::syncRegionCount($umkm->region_id);
            }
        });
    }

    protected static function syncRegionCount($regionId)
    {
        $region = Region::find($regionId);
        if ($region) {
            $region->update(['halal_msmes_count' => $region->umkms()->count()]);
        }
    }

    protected $fillable = [
        'source_id',
        'nomor',
        'region_id',
        'lph_partner_id',
        'nama_umkm',
        'slug',
        'nama_pemilik',
        'lembaga',
        'kategori',
        'provinsi',
        'kab_kota',
        'kecamatan',
        'kelurahan',
        'alamat',
        'detail_url',
        'edit_url',
        'latitude',
        'longitude',
        'nomor_wa',
        'link_pembelian',
        'deskripsi',
        'foto_url',
        'image_path',
        'jumlah_produk',
        'approval',
        'status',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_featured' => 'boolean',
            'source_id' => 'integer',
            'nomor' => 'integer',
            'jumlah_produk' => 'integer',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function lphPartner(): BelongsTo
    {
        return $this->belongsTo(LphPartner::class);
    }

    public function produks(): HasMany
    {
        return $this->hasMany(UmkmProduk::class);
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
                'source' => 'nama_umkm',
            ],
        ];
    }
}
