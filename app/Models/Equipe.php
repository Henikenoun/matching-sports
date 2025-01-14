<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'type', 'nombre', 'reservation_id','participants'];

    // public function reservation()
    // {
    //     return $this->belongsTo(Reservation::class,'reservation_id');
    // }

    public function demandes()
    {
        return $this->hasMany(Demande::class,'demande_id');
    }
 




}
