<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorateRow extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_key',
        'data',
        'row_order',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
