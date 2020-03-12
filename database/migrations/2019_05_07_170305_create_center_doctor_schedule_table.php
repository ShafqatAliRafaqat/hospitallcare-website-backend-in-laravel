<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCenterDoctorScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('center_doctor_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('center_id')->unsigned();
            $table->integer('doctor_id')->unsigned();
            $table->string('time_from')->nullable();
            $table->string('time_to')->nullable();
            $table->string('day_from')->nullable();
            $table->string('day_to')->nullable();
            $table->string('fare')->nullable();
            $table->string('discount')->nullable();
            $table->string('appointment_duration')->nullable();
            $table->tinyInteger('is_primary')->default(0);
            $table->timestamps();
        });
        Schema::table('center_doctor_schedule', function($table){
            $table->foreign('center_id')->references('id')->on('medical_centers');
            $table->foreign('doctor_id')->references('id')->on('doctors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('center_doctors');
    }
}
