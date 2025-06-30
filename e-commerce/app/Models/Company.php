<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'total_income'];

    public function users()
    {
        return $this->hasMany(AuthorisedUser::class);
    }
}
