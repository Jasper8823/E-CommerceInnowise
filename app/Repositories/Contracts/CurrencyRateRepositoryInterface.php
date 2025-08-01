<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\CurrencyRate;

interface CurrencyRateRepositoryInterface
{
    public function updateOrCreate(string $currency, float $rate): CurrencyRate;

    public function pluck(): array;
}
