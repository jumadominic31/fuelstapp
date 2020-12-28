<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tankshift extends Model
{
    public function tank(){
        return $this->belongsTo('App\Tank', 'tank_id');
    }
}
