<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
     protected $table='users';
   protected $fillable = [
        'id',
       'fname',
       'lname',
        'email',
        'password',
       'age',
       'gender',
       'phone',
       'photo',
       'city',
       'country',
       'created_at',
       'updated_at',

    ];

   public function ScopeSelection($q){
       return $q->select('id',
           'fname',
           'lname',
           'email',
           'password',
           'age',
           'gender',
           'phone',
           'photo',
           'city',
           'country',
           'created_at',
           'updated_at');
   }
    protected $timestamp=true;
    public function getPhotoAttribute($val)
    {
        return ($val!=null)? asset('assets/'.$val):"";
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class,'user_id','id');
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
