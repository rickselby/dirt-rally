<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AcCars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_cars', function(Blueprint $table) {
            $table->increments('id');
            $table->string('ac_identifier');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('ac_session_entrants', function(Blueprint $table) {
            $table->unsignedInteger('ac_car_id')->nullable();

            $table->foreign('ac_car_id')->references('id')->on('ac_cars')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
