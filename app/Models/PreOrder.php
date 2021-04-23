<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    use HasFactory;
    protected $guarded = [];

    //* Relationship orders
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
