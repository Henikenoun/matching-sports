<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;use HasFactory; use HasApiTokens;

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
    public function demandes()
{
    return $this->hasMany(Demande::class);
}

}
