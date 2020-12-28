<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    public function station(){
        return $this->belongsTo('App\Station', 'station_id');
    }
}
