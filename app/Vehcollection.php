<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehcollection extends Model
{
    public function vehicle(){
        return $this->belongsTo('App\Vehicle', 'vehicle_id');
    }

    public function owner(){
        return $this->belongsTo('App\Owner', 'owner_id');
    }
}