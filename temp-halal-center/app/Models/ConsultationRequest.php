<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'organization_name',
        'subject',
        'message',
        'preferred_language',
        'status',
    ];
}
