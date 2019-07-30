<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    public function owner()
    {
        return $this->belongsTo('App\Owner', 'owner_id');
    }
}
