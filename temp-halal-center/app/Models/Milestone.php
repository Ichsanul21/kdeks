<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'year',
        'sub_title',
        'title',
        'color',
        'icon',
        'items',
        'image_path',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
        ];
    }
}
