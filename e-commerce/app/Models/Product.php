<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuId',
        'product_type_id',
        'name',
        'price',
        'releaseDate',
        'company_id',
        'description',
    ];

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'product_service')
            ->withPivot('price', 'daysNeeded');
    }
}
