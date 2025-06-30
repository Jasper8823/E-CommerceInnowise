<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

final class AuthorisedUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'surname',
        'company_id',
        'is_admin',
    ];

    protected $hidden = [
        'remember_token',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
