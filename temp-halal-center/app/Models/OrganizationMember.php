<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    protected $fillable = [
        'name',
        'role_title',
        'role_title_en',
        'bio',
        'photo_path',
        'email',
        'phone',
        'expertise',
        'sort_order',
        'is_board_member',
    ];

    protected function casts(): array
    {
        return [
            'is_board_member' => 'boolean',
        ];
    }
}
