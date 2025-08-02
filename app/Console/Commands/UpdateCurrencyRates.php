<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\CurrencyRateClient;
use App\Repositories\CurrencyRateRepository;
use Illuminate\Console\Command;
use Throwable;

final class UpdateCurrencyRates extends Command
{
    protected $signature = 'update:currency-rates';

    protected $description = 'Downloads current exchange rates from ECB and stores them in the database';

    public function __construct(
        private CurrencyRateRepository $currencyRateRepository,
        private CurrencyRateClient $currencyClient
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Fetching currency exchange rates from ECB...');

        try {
            $rates = $this->currencyClient->fetchRates();

            foreach ($rates as $currency => $rate) {
                $this->currencyRateRepository->updateOrCreate($currency, $rate);
                $this->info("Updated rate for {$currency}: {$rate}");
            }

            $this->info('Currency rates updated successfully.');
        } catch (Throwable $e) {
            $this->error('Error while updating rates: '.$e->getMessage());
        }
    }
}
