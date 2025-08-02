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
            'manufacturer_id' => ['nullable', 'exists:manufacturers,id'],

            'services' => ['nullable', 'array'],
            'services.*.price' => ['nullable', 'numeric', 'min:0'],
            'services.*.daysNeeded' => ['nullable', 'integer', 'min:0'],

            'custom_services' => ['nullable', 'array'],
            'custom_services.*.name' => ['required', 'string', 'min:2', 'max:64'],
            'custom_services.*.price' => ['required', 'numeric', 'min:0'],
            'custom_services.*.daysNeeded' => ['required', 'integer', 'min:0'],
        ];
    }
}
