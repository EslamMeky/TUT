<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripPlace extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'day_num', 'place_id'];

    public function trip()
    {
        return $this->belongsTo(Trip::class,"trip_id","id");
    }

    public function place()
    {
        return $this->belongsTo(Place::class,"place_id","id");
    }
}

