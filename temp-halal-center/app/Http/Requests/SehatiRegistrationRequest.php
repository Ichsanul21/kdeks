<?php

namespace App\Http\Requests;

class SehatiRegistrationRequest extends SehatiRegistrationStoreRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'status' => ['required', 'in:baru,ditinjau,diproses,selesai'],
            'izin_status' => ['nullable', 'string'],
        ];
    }
}
