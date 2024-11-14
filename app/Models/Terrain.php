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
        'clubId'
    ];

    protected $primaryKey = 'id';

    public $timestamps = true;

    public function club()
    {
        return $this->belongsTo(Club::class, 'clubId');
    }
}
