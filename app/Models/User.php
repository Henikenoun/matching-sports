<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'password_confirmation',
        'role',
        'date_of_birth',
        'city',
        'phone_number',
        'photo',
        'availability',
        'transport',
    ];

    protected $hidden = [
        'mot_de_passe', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
