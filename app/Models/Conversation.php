<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $guarded = [];

    //* Relationshion chatflow

    public function chatflows(){
        return $this->HasMany(Chatflow::class);
    }
}
