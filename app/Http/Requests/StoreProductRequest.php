<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'releaseDate' => ['required', 'date'],
            'description' => ['required', 'string'],

            'product_type_id' => ['nullable', 'exists:product_types,id'],
            'new_product_type' => ['nullable', 'string', 'max:255'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'new_company' => ['nullable', 'string', 'max:255'],

            'services' => ['nullable', 'array'],
            'custom_services' => ['nullable', 'array'],
        ];
    }
}
