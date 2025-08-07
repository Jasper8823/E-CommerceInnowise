<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class CurrencyRate extends Model
{
    protected $fillable = [
        'rate',
        'currency',
    ];
}
