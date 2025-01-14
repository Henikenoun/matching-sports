<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    public $timestamps = false;


    protected $fillable = ['user_id', 'rateable_id', 'rateable_type', 'rating', 'review'];

    /**
     * Relation polymorphique.
     */
    public function rateable()
    {
        return $this->morphTo();
    }

    /**
     * Relation avec l'utilisateur qui fait l'évaluation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
