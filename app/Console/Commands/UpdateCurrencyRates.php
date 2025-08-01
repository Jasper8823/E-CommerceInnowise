<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CurrencyRate;
use App\Repositories\CurrencyRateRepository;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

final class UpdateCurrencyRates extends Command
{
    protected $signature = 'update:currency-rates';

    protected $description = 'Downloads current exchange rates from bankdabrabyt.by and stores them in the database';

    private CurrencyRateRepository $currencyRateRepository;

    public function __construct(CurrencyRateRepository $currencyRateRepository)
    {
        parent::__construct();
        $this->currencyRateRepository = $currencyRateRepository;
    }

    public function handle(): void
    {
        $this->info('Downloading currency exchange rates from ECB...');

        try {
            $response = Http::get('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');

            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());

                foreach ($xml->Cube->Cube->Cube as $rateNode) {
                    $currency = (string) $rateNode['currency'];
                    $rate = (float) $rateNode['rate'];

                    $this->currencyRateRepository->updateOrCreate($currency, $rate);

                    $this->info("Currency $currency updated $rate");
                }
            } else {
                $this->error('Failed to fetch currency rates');
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
