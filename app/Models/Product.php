<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\CurrencyFormatterService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'product_type_id',
        'name',
        'price',
        'release_date',
        'manufacturer_id',
        'description',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'product_service')
            ->withPivot('price', 'days_needed');
    }

    public function getPrice($currency, $price): string
    {
        $formatter = new CurrencyFormatterService();

        return $formatter->format($price, $currency);
    }
}
