<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $table='rating';
    protected $fillable = [
        'id',
        'user_id',
        'rating',
        'place_id',
        'review',
        'status',
        'created_at',
        'updated_at',

    ];

    public function ScopeSelection($q){
        return $q->select(
            'id',
            'user_id',
            'rating',
            'place_id',
            'review',
            'status',
            'created_at',
            'updated_at',

        );
    }
    protected $timestamp=true;

    public function places()
    {
        return $this->belongsTo(Place::class,'place_id'  ,'id');
    }
    public function users()
    {
        return $this->belongsTo(User::class,'user_id'  ,'id');
    }



}
