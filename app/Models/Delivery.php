<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    //? Getter (FirstName)
    public function getFirstNameAttribute($value){
        return ucfirst($value);
    }
    
    //? Getter (LastName)
    public function getLastNameAttribute($value){
        return ucfirst($value);
    }

    //? Setter (LastName)
    public function setLastNameAttribute($value){
        $this->attributes['last_name'] = strtolower(trim($value));
    }

    //? Setter (FirstName)
    public function setFirstNameAttribute($value){
        $this->attributes['first_name'] = strtolower(trim($value));
    }

    //* Relationship Preorder (order)
    public function pre_order(){
        return $this->hasMany(PreOrder::class);
    }

}
