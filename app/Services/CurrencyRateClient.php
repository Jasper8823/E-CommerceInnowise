<?php

namespace App\Services;

use App\Interfaces\CurrencyRateClientInterface;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final class CurrencyRateClient implements CurrencyRateClientInterface
{
    public function fetchRates(): array
    {
        $url = config('services.ecb.url');
        $response = Http::get($url);

        if (! $response->ok()) {
            throw new RuntimeException('Failed to fetch exchange rates.');
        }

        $xml = simplexml_load_string($response->body());
        $xml->registerXPathNamespace('ns', 'http://www.ecb.int/vocabulary/2002-08-01/eurofxref');
        $nodes = $xml->xpath('//ns:Cube/ns:Cube/ns:Cube');

        $result = ['EUR' => 1.0];
        foreach ($nodes as $node) {
            $currency = (string) $node['currency'];
            $rate = (float) $node['rate'];
            $result[$currency] = $rate;
        }

        return $result;
    }
}

