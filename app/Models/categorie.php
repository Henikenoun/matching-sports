<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
        'photo',
    ];

    /**
     * Relation avec le modèle Article
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'categorie_id');
    }
}
