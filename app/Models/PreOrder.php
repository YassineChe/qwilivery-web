<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    //* Relationship orders
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
