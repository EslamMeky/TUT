<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripPlacesTable extends Migration
{
    public function up()
    {
        Schema::create('trip_places', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->integer('day_num');
            $table->unsignedBigInteger('place_id');
            $table->timestamps();

            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('trip_places');
    }
}

