<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'name',
        'desc',
        'photo',
        'quantity',
        'price',
        'couleur',
        'remise',
        'offre',
        'categorie_id',
        'shop_id'
    ];

    /**
     * Relation avec le modèle Shop
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    /**
     * Relation avec le modèle Categorie
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }
    public function demandes()
{
    return $this->hasMany(DemandeP::class);
}

}
