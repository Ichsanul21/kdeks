<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DynamicSubModule extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'sector_item_id',
        'name',
        'slug',
        'description',
        'instructions',
        'fields',
        'custom_template_path',
        'created_by',
    ];

    protected $casts = [
        'fields' => 'array',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function sectorItem(): BelongsTo
    {
        return $this->belongsTo(SectorItem::class, 'sector_item_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rows(): HasMany
    {
        return $this->hasMany(DynamicSubModuleData::class, 'dynamic_sub_module_id')
            ->orderBy('row_order')
            ->orderBy('id');
    }

    /**
     * Get list of column names for the table and Excel headers.
     *
     * @return array<string>
     */
    public function getColumnsAttribute(): array
    {
        if (empty($this->fields) || !is_array($this->fields)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($f) {
            return is_array($f) ? ($f['name'] ?? null) : null;
        }, $this->fields)));
    }

    /**
     * Get title attribute (alias for name to maintain consistency with DirectorateConfig).
     */
    public function getTitleAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get item_key attribute (alias for slug to maintain consistency with DirectorateConfig).
     */
    public function getItemKeyAttribute(): string
    {
        return $this->slug;
    }

    /**
     * Normalized map of fields indexed by column name.
     */
    public function getFieldMapAttribute(): array
    {
        $map = [];
        if (is_array($this->fields)) {
            foreach ($this->fields as $field) {
                if (is_array($field) && !empty($field['name'])) {
                    $map[$field['name']] = [
                        'name' => $field['name'],
                        'type' => $field['type'] ?? 'text',
                        'required' => !empty($field['required']),
                        'options' => is_array($field['options'] ?? null) ? $field['options'] : [],
                    ];
                }
            }
        }
        return $map;
    }
}
