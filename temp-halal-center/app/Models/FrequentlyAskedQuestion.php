<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrequentlyAskedQuestion extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }
}
