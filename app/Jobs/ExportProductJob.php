<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ExportProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $products;

    public bool $isFirst;

    public bool $isLast;

    public function __construct(array $products, bool $isFirst, bool $isLast)
    {
        $this->products = $products;
        $this->isFirst = $isFirst;
        $this->isLast = $isLast;
    }

    public function handle(Filesystem $storage, Mailer $mailer): void
    {
        $path = 'laravel-products/exports/products.csv';
        $csv = '';
        foreach ($this->products as $product) {
            $csv .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $product['name'] ?? '',
                $product['price'] ?? '',
                $product['release_date'] ?? '',
                $product['description'] ?? ''
            );
        }

        if ($this->isFirst) {
            $storage->delete($path);
            $headers = "\"Name\",\"Price\",\"Release Date\",\"Description\"\n";
            $storage->put($path, $headers.$csv);
        } else {
            $storage->put($path, $storage->get($path).$csv);
        }

        if ($this->isLast) {
            $url = $storage->url($path);
            $mailer->raw('Экспорт завершён. Файл: '.$url, function ($message) {
                $message->to('admin@example.com')->subject('Каталог экспортирован');
            });
        }
    }
}
