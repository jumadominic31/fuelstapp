<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    public function txns(){
        return $this->hasMany('App\Txn');
    }

    public function eodays(){
        return $this->hasMany('App\Eoday');
    }

    public function pumps(){
        return $this->hasMany('App\Eoday');
    }

    public function stations(){
        return $this->hasMany('App\User');
    }
    
    public function rates(){
        return $this->hasMany('App\Rate');
    }
}
