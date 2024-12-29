<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
        'photo',
        'url',
        'club_id'
    ];

    /**
     * Relation avec le modèle Club
     */
    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    /**
     * Relation avec le modèle Article
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'shop_id');
    }
}
