<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $table='favorites';
    protected $fillable=[
      'id',
        'user_id',
        'place_id',
        'created_at',
        'updated_at'
    ];

    public function ScopeSelection($q){
        return $q->select(
            'user_id',
            'place_id');
    }
    public $timestamps=true;

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function places()
    {
        return  $this->belongsTo(Place::class,'place_id','id');
    }
}
