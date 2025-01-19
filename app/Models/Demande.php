<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','equipe_id','etat','date',];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le modèle Equipe
    public function equipe()
    {
        return $this->belongsTo(Equipe::class);
    }

    // Relation avec le modèle Terrain
    public function terrain()
    {
        return $this->belongsTo(Terrain::class);
    }

}
