<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table='city';
    protected $fillable = [
        'id',
        'name',
        'desc',
        'photo',
        'created_at',
        'updated_at',

    ];

    public function ScopeSelection($q){
        return $q->select( 'id',
            'name',
            'desc',
            'photo',
            'created_at',
            'updated_at',
        );
    }
    protected $timestamp=true;
    public function getPhotoAttribute($val)
    {
        return ($val!=null)? asset('assets/'.$val):"";
    }

}
