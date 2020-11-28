<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pump extends Model
{
    /*public function readings(){
        return $this->hasMany('App\Reading');
    }*/

    public function station()
    {
        return $this->belongsTo('App\Station', 'stationid');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'attendantid');
    }

}
