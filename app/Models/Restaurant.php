<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [];
    
    protected $hidden = [
        'password',
    ];

    //? Getter (Name)
    public function getNameAttribute($value){
        return ucfirst($value);
    }

    //? Setter (Name)
    public function setNameAttribute($value){
        $this->attributes['name'] = strtolower(trim($value));
    }

    //? Setter (Email)
    public function setEmailAttribute($value){
        $this->attributes['email'] = strtolower(trim($value));
    }

    //? Setter (Address)
    public function setAddressAttribute($value){
        $this->attributes['address'] = strtoupper($value);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    
}
