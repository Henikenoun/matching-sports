<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class reservation extends Model
{
    use HasFactory;
    protected $fillable=[
        "User_Reserve",
        "Nom_Club",
        "Nb_Place",
        "Complet",
        "Type",
        "Date_Reservation",
        "Date_TempsReel",
        "ispaye",
        "Participants"
    ];

    //public function Terain()
    //{ 
      //  return $this->belongsTo(  Terain::class ,"ID"); 
    //}
       
    //relation avec participant 
 


}
