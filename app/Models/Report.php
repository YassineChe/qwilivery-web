<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $guarded = [];

    //* Relationship restaurant
    public function restaurant(){
        return $this->belongsTo(Restaurant::class, 'guard_id');
    }

    //* Relationship delivery
    public function delivery(){
        return $this->belongsTo(Delivery::class, 'guard_id');
    }
}
