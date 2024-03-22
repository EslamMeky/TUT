<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table='admins';
    protected $fillable=
        [
            'id',
            'fname',
            'lname',
            'email',
            'password',
            'gender',
            'phone',
            'photo',
            'created_at',
            'updated_at',

        ];

    public function ScopeSelection($q){
        return $q->select('id',
            'fname',
            'lname',
            'email',
            'password',
            'gender',
            'phone',
            'photo',
            'created_at',
            'updated_at');
    }
    protected $timestamp=true;
    public function getPhotoAttribute($val)
    {
        return ($val!=null)? asset('assets/'.$val):"";
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
