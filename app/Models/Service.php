<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\CurrencyFormatterService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_service')
            ->withPivot('price', 'days_needed');
    }

    public function getPrice($currency, $price): string
    {
        $formatter = new CurrencyFormatterService();

        return $formatter->format($price, $currency);
    }
}
