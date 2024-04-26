<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'days', 'city'];

    public function users()
    {
        return $this->belongsTo(User::class,"user_id","id");
    }
   /* public function tripPlaces()
    {
        return $this->hasMany(TripPlace::class);
    }*/
}

