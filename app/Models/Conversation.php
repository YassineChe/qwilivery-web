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
        return $this->HasMany(Chatflow::class)->orderBy('id');
    }


    //* Relationshp delivery
    public function delivery(){
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }

    //* Relationshp restaurant
    public function restaurant(){
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    //* Relationshp admin
    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    //* Relationshp last message
    public function last_message(){
        return $this->hasOne(Chatflow::class)->orderBy('created_at', 'desc');
    }

    //* Relationshp undread messages
    public function unread_messages(){
        //Selecting id, converation (because we don't want to overlad the server the data will be counted)
        return $this->hasMany(Chatflow::class)->whereNull('seen_at')->select('id', 'conversation_id');
    }
}
