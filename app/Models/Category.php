<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table='categories';
    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',

    ];

    public function ScopeSelection($q){
        return $q->select(
            'id',
            'name',
            'created_at',
            'updated_at',
        );
    }
    protected $timestamp=true;
}
