<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class AuthorisedUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'surname',
        'company_id',
        'isAdmin',
    ];

    protected $hidden = [
        'remember_token',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
