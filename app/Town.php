<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    public function stations(){
        return $this->hasMany('App\Station');
    }
}
