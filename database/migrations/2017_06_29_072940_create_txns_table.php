<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTxnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('txns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('receiptno');
            $table->string('userid');
            $table->string('stationid');
            $table->string('vehregno');
            $table->string('amount');
            $table->string('volume');
            $table->string('sellprice');
            $table->string('fueltype');
            $table->string('paymethod');
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
        Schema::dropIfExists('txns');
    }
}
