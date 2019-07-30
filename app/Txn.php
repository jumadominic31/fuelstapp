<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Txn extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'userid');
    }

    public function station()
    {
        return $this->belongsTo('App\Station', 'stationid');
    }
}
