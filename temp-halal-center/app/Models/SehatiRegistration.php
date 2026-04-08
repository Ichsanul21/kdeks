<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SehatiRegistration extends Model
{
    protected $fillable = [
        'lph_partner_id',
        'owner_name',
        'business_name',
        'product_name',
        'phone',
        'description',
        'status',
    ];

    public function lphPartner(): BelongsTo
    {
        return $this->belongsTo(LphPartner::class);
    }
}
