<?php

declare(strict_types=1);

namespace App\Models;

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
        $rate = CurrencyRate::where('currency', $currency)->first();

        if (! $rate) {
            return 'Currency rate not found for '.$currency;
        }

        $convertedPrice = $price * $rate->rate;

        switch ($currency) {
            case 'USD':
                $formattedPrice = '$ '.number_format($convertedPrice, 2);
                break;
            case 'PLN':
                $formattedPrice = 'PLN '.number_format($convertedPrice, 2);
                break;
            case 'EUR':
                $formattedPrice = '€ '.number_format($convertedPrice, 2);
                break;
            default:
                $formattedPrice = 'Unsupported currency';
                break;
        }

        return $formattedPrice;
    }
}
