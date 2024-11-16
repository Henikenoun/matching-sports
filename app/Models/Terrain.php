<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terrain extends Model
{
    use HasFactory;

    protected $table = 'terrains';

    protected $fillable = [
        'nom',
        'type',
        'disponibilite',
        'capacite',
        'fraisLocation',
        'club_id'
    ];

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $casts = [
        'disponibilite' => 'boolean', // Cast to boolean
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }
}
