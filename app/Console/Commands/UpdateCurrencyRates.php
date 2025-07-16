<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CurrencyRate;
use Exception;
use Illuminate\Console\Command;

final class UpdateCurrencyRates extends Command
{
    protected $signature = 'update:currency-rates';

    protected $description = 'Downloads current exchange rates from bankdabrabyt.by and stores them in the database';

    public function handle()
    {
        $this->info('Downloading currency exchange rates from ECB...');

        try {
            $xml = simplexml_load_file('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');

            if (! $xml) {
                throw new Exception('Unable to parse ECB XML.');
            }

            $cube = $xml->Cube->Cube;

            foreach ($cube->Cube as $rateNode) {
                $currency = (string) $rateNode['currency'];
                $rate = (float) $rateNode['rate'];

                CurrencyRate::updateOrCreate(
                    ['currency' => $currency],
                    ['rate' => $rate]
                );

                $this->info("Updated: $currency => $rate");
            }

            CurrencyRate::updateOrCreate(
                ['currency' => 'EUR'],
                ['rate' => 1.0]
            );

            $this->info('Currency rates updated successfully from ECB.');

        } catch (Exception $e) {
            $this->error('Error updating currency rates: '.$e->getMessage());
        }
    }
}
