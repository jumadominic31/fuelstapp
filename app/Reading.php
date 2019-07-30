<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    public function pump()
    {
        return $this->belongsTo('App\Pump', 'pumpid');
    }
}
