<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Variant;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];


    //* RelationShip
    public function variants(){
        return $this->hasMany(Variant::class);
    }
}
