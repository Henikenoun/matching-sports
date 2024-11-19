<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $table = 'clubs';

    protected $fillable = [
        'nom',
        'ville',
        'adresse',
        'numTel',
        'email',
        'nbTerrain'
    ];

    protected $primaryKey = 'id';

    public $timestamps = true;

    public function terrains()
    {
        return $this->hasMany(Terrain::class, 'club_id');
    }
    public function reservation()
    {
        return $this->hasMany(reservation::class, 'club_id');
    }
}
