<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\CurrencyRateClientException;
use App\Interfaces\CurrencyRateClientInterface;
use Illuminate\Http\Client\Factory;

final class CurrencyRateClient implements CurrencyRateClientInterface
{
    private const xml_namespace = 'http://www.ecb.int/vocabulary/2002-08-01/eurofxref';

    public function __construct(
        private readonly Factory $http,
    ) {}

    public function fetchRates(string $url): array
    {
        $response = $this->http->get($url);

        if (! $response->ok()) {
            throw new CurrencyRateClientException('Failed to fetch exchange rates from ECB.');
        }

        $xml = simplexml_load_string($response->body());
        if ($xml === false) {
            throw new CurrencyRateClientException('Invalid XML format from ECB response.');
        }

        $xml->registerXPathNamespace('ns', self::xml_namespace);
        $nodes = $xml->xpath('//ns:Cube/ns:Cube/ns:Cube');

        if (! is_array($nodes)) {
            throw new CurrencyRateClientException('Malformed XML structure.');
        }

        $result = ['EUR' => 1.0];
        foreach ($nodes as $node) {
            $currency = (string) $node['currency'];
            $rate = (float) $node['rate'];
            $result[$currency] = $rate;
        }

        return $result;
    }
}
