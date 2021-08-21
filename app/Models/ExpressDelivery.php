<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpressDelivery extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getTakenAtAttribute($value){
        return $value == null ? null : \Carbon\Carbon::parse($value)->diffForHumans();
    }

    //* Relationship delivery
    public function delivery(){
        return $this->belongsTo(Delivery::class);
    }
}
