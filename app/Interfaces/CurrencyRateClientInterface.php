<?php

namespace App\Interfaces;

interface CurrencyRateClientInterface
{
    public function fetchRates(): array;
}
