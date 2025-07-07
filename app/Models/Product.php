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
}
