<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuId',
        'product_type_id',
        'name',
        'price',
        'release_date',
        'company_id',
        'description',
    ];

    public function getRouteKeyName()
    {
        return 'uuId';
    }

    public function type()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'product_service')
            ->withPivot('price', 'days_needed');
    }

    public function getPrice($currency, $price)
    {
        $rate = CurrencyRate::where('currency', $currency)->first();

        if (!$rate) {
            return 'Currency rate not found for ' . $currency;
        }

        $convertedPrice = $price * $rate->rate;

        switch ($currency) {
            case 'USD':
                $formattedPrice = '$ ' . number_format($convertedPrice, 2);
                break;
            case 'PLN':
                $formattedPrice = 'PLN ' . number_format($convertedPrice, 2);
                break;
            case 'EUR':
                $formattedPrice = '€ ' . number_format($convertedPrice, 2);
                break;
            default:
                $formattedPrice = 'Unsupported currency';
                break;
        }

        return $formattedPrice;
    }
}
