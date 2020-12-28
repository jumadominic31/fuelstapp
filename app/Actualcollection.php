<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actualcollection extends Model
{
    public function attendant(){
        return $this->belongsTo('App\User', 'attendant_id');
    }
}
