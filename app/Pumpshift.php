<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pumpshift extends Model
{
    public function pump(){
        return $this->belongsTo('App\Pump', 'pump_id');
    }

    public function attendant(){
        return $this->belongsTo('App\User', 'attendant_id');
    }
}
