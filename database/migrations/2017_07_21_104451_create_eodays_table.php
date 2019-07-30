<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEodaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eodays', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stationid');
            $table->double('tot_diesel_vol');
            $table->double('tot_petrol_vol');
            $table->double('tot_diesel_val');
            $table->double('tot_petrol_val');
            $table->double('tot_val');
            $table->double('tot_diesel_coll');
            $table->double('tot_petrol_coll');
            $table->double('tot_coll');
            $table->double('diesel_shortage');
            $table->double('petrol_shortage');
            $table->double('shortage');
            $table->double('diesel_open_stock');
            $table->double('petrol_open_stock');
            $table->double('diesel_purchases');
            $table->double('petrol_purchases');
            $table->double('diesel_close_stock');
            $table->double('petrol_close_stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eodays');
    }
}
