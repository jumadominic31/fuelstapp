<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eoday extends Model
{
    public function station()
    {
        return $this->belongsTo('App\Station', 'stationid');
    }
}
