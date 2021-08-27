<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpressDelivery extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getTakenAtAttribute($value){
        // return $this->created_at;

        if ($value == null)
            return null;

        $diffInMinutes = \Carbon\Carbon::parse($value)->diffInMinutes($this->attributes['created_at']);

        if ($diffInMinutes <= 0)
            return \Carbon\Carbon::parse($value)->diffInSeconds($this->attributes['created_at']) .' seconde(s)';

        if ($diffInMinutes > 60 && $diffInMinutes < 1440)
            return \Carbon\Carbon::parse($value)->diffInHours($this->attributes['created_at']) .' heure(s)';

        if ($diffInMinutes > 1440)
            return \Carbon\Carbon::parse($value)->diffInDays($this->attributes['created_at']) .'  jour(s)';
            

        return $diffInMinutes . ' minute(s)';
            
    }

    public function getCreatedAtAttribute($value){
        // return $this->attributes['taken_at'];
        return \Carbon\Carbon::parse($value)->diffForHumans();
        // return \Carbon\Carbon::parse($value)->format('Y-m-d H:i');
    }

    //* Relationship delivery
    public function delivery(){
        return $this->belongsTo(Delivery::class);
    }

    public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }
}
