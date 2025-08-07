<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'currency-selector' => ['string'],
        ];
    }
}
