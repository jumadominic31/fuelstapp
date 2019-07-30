<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'rate_date', 'fueltype', 'buyprice', 'sellprice', 'updated_by', 'created_at', 'updated_at',
    ];

    public function station(){
        return $this->belongsTo('App\Station', 'stationid');
    }
}
