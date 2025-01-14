<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','equipe_id','etat','date',];

<<<<<<< HEAD
     public function user()
=======
    public function user()
>>>>>>> fce54fef574bbd97f87739dcdc034b22625b8727
    {
        return $this->belongsTo(User::class,'user_id');
    }


    public function equipe(){
        return $this->belongsTo(Equipe::class,'equipe_id');
    }

}
