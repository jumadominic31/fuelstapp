<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public function txns(){
        return $this->hasMany('App\Txn');
    }

    public function pumps(){
        return $this->hasMany('App\Pump');
    }

    public function station()
    {
        return $this->belongsTo('App\Station', 'stationid');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'fullname', 'phone', 'password', 'stationid', 'status', 'usertype',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
