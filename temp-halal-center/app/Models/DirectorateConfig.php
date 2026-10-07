<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorateConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_key',
        'sub_module',
        'group_prefix',
        'title',
        'instructions',
        'columns',
        'custom_template_path',
    ];

    protected $casts = [
        'columns' => 'array',
    ];
}
