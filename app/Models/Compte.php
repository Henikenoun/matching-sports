<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    protected $table = 'comptes';

    protected $fillable = [
        'nom',
        'prénom',
        'date de naissance',
        'ville',
        'numéro de téléphone',
        'email',
        'mot de passe',
        'transport',
        'photo',
        'disponibilité',
    ];


}