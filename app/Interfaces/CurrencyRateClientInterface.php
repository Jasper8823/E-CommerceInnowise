<?php

declare(strict_types=1);

namespace App\Interfaces;

interface CurrencyRateClientInterface
{
    public function fetchRates(string $url): array;
}
