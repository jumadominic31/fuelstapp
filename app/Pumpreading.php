<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pumpreading extends Model
{
    public function pump()
    {
        return $this->belongsTo('App\Pump', 'pump_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'attendant_id');
    }
}
