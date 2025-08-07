<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CurrencyRate;
use App\Repositories\Contracts\CurrencyRateRepositoryInterface;

class CurrencyRateRepository implements CurrencyRateRepositoryInterface
{
    public function updateOrCreate(string $currency, float $rate): CurrencyRate
    {
        return CurrencyRate::updateOrCreate(
            ['currency' => $currency],
            ['rate' => $rate]
        );
    }

    public function pluck(): array
    {
        return CurrencyRate::pluck('rate', 'currency')->toArray();
    }

    public function getCurrencyByName(string $currency): CurrencyRate
    {
        return CurrencyRate::where('currency', $currency)->first();
    }
}
