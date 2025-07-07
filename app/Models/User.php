<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

final class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password'];
}
