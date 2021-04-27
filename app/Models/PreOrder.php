<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    //? Setter (Address)
    public function setAddressAttribute($value){
        $this->attributes['address'] = strtoupper($value);
    }

    //* Relationship orders
    public function orders(){
        return $this->hasMany(Order::class);
    }

    //* Relationship restaurant
    public function restaurant(){
        return $this->belongsTo(PreOrder::class);
    }

    public function delivery(){
        return $this->belongsTo(Delivery::class);
    }
}
