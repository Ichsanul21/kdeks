<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SehatiRegistrationStoreRequest extends FormRequest
{
    protected $errorBag = 'sehatiRegistration';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lph_partner_id' => ['nullable', 'exists:lph_partners,id'],
            'owner_name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ];
    }
}
