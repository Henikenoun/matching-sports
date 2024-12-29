<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        "User_Reserve",
        "Nb_Place",
        "Complet",
        "Type",
        "Date_Reservation",
        "Date_TempsReel",
        "ispaye",
        "Participants",
        "Club_id",
        "terrain_id" // Convention pour la clé étrangère
    ];

    public function terrain()
    {
        return $this->belongsTo(Terrain::class, 'terrain_id'); // Relation avec Terrain
    }
    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id'); // Relation avec Terrain
    }
}