<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

final class ExportProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $products;

    public bool $isFirst;

    public bool $isLast;

    public function __construct(array $products, bool $isFirst = false, bool $isLast = false)
    {
        $this->products = $products;
        $this->isFirst = $isFirst;
        $this->isLast = $isLast;
    }

    public function handle()
    {
        $path = 'exports/products.csv';

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
            Storage::disk('s3')->delete($path);
            $headers = "\"Name\",\"Price\",\"Release Date\",\"Description\"\n";
            Storage::disk('s3')->put($path, $headers.$csv);
        } else {
            $old = Storage::disk('s3')->get($path);
            Storage::disk('s3')->put($path, $old.$csv);
        }

        if ($this->isLast) {
            $url = Storage::disk('s3')->url($path);
            $url = str_replace('localstack', 'localhost', $url); // заменить localstack на localhost
            Mail::raw('Экспорт завершён. Файл: '.$url, function ($message) {
                $message->to('admin@example.com')->subject('Каталог экспортирован');
            });
        }
    }
}
