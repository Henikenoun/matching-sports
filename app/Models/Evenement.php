<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory ;

    protected $fillable =['terrain_id','club_id','nom','type','nombreMax','date','nbActuel','description','photo'
    ,'prixUnitaire','responsable','participant','raison'];




    public function participants()
    {
        return $this->belongsToMany(User::class, 'evenement_participant', 'evenement_id', 'participant_id');

    public function terrain()
    { 
        return $this->hasMany(Terrain::class,"terrain_id"); 
    }

    public function club()
    { 
        return $this->belongsTo(Club::class,"club_id"); 
    }



  


    public function responsable()
    { 
        return $this->belongsTo(User::class,"responsable"); 
    }


}