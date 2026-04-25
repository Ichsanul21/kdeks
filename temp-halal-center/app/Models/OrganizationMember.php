<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    protected $fillable = [
        'name',
        'role_title',
        'bio',
        'photo_path',
        'email',
        'phone',
        'expertise',
        'sort_order',
        'is_board_member',
        'category',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(OrganizationMember::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(OrganizationMember::class, 'parent_id')->orderBy('sort_order');
    }

    protected function casts(): array
    {
        return [
            'is_board_member' => 'boolean',
        ];
    }
}
