<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    public function vehicles(){
        return $this->hasMany('App\Vehicle');
    }

    public function txns(){
        return $this->hasMany('App\Txn');
    }
}
