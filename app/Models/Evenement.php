<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory ;

    protected $fillable =['IDTerrain','nom','type','nombreMax','date','nbActuel','description','photo'
    ,'prixUnitaire','responsable','participant','raison'];


    // public function terrain()
    // { 
    //     return $this->belongsTo(Terrain::class,"IDTerrain"); 
    // }

    public function particpant()
    { 
        return $this->belongsTo(User::class,"participant"); 
    }

    public function responsable()
    { 
        return $this->belongsTo(User::class,"responsable"); 
    }


}
