<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicSubModuleData extends Model
{
    use HasFactory;

    protected $table = 'dynamic_sub_module_data';

    protected $fillable = [
        'dynamic_sub_module_id',
        'sector_item_id',
        'data',
        'row_order',
        'created_by',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function subModule(): BelongsTo
    {
        return $this->belongsTo(DynamicSubModule::class, 'dynamic_sub_module_id');
    }

    public function sectorItem(): BelongsTo
    {
        return $this->belongsTo(SectorItem::class, 'sector_item_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
