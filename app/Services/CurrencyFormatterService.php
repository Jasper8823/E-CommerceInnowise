<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CurrencyRate;

class CurrencyFormatterService
{
    public function format(float $amount, string $currency): string
    {
        $rate = CurrencyRate::where('currency', $currency)->first();

        if (! $rate) {
            return 'Currency rate not found for '.$currency;
        }

        $convertedPrice = $amount * $rate->rate;

        return match ($currency) {
            'USD' => '$ '.number_format($convertedPrice, 2),
            'PLN' => 'PLN '.number_format($convertedPrice, 2),
            'EUR' => '€ '.number_format($convertedPrice, 2),
            default => 'Unsupported currency',
        };
    }
}
