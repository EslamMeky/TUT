<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $table='places';
    protected $fillable = [
        'id',
        'name',
        'desc',
        'photo',
        'city_id',
        'category_name',
        'longitude',
        'latitude',
        'created_at',
        'updated_at',

    ];

    public function ScopeSelection($q){
        return $q->select( 'id',
            'name',
            'desc',
            'photo',
            'city_id',
            'category_name',
            'longitude',
            'latitude',
            'created_at',
            'updated_at',

        );
    }
    protected $timestamp=true;
    public function getPhotoAttribute($val)
    {
        return ($val!=null)? asset('assets/'.$val):"";
    }

    public function cities()
    {
        return $this->belongsTo(City::class,'city_id','id');
    }

}
