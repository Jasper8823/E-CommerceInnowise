<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CurrencyRateRepository;

class CurrencyFormatterService
{
    private $currencyRateRepository;

    public function format(float $amount, string $currency): string
    {
        if (! $this->currencyRateRepository) {
            $this->currencyRateRepository = new CurrencyRateRepository();
        }
        $rate = $this->currencyRateRepository->getCurrencyByName($currency);

        $convertedPrice = $amount * $rate->rate;

        return match ($currency) {
            'USD' => '$ '.number_format($convertedPrice, 2),
            'PLN' => 'PLN '.number_format($convertedPrice, 2),
            'EUR' => '€ '.number_format($convertedPrice, 2),
            default => 'Unsupported currency',
        };
    }
}
