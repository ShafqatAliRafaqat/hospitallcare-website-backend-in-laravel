<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_treatments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('treatment_id')->unsigned();
            $table->integer('doctor_id')->unsigned();
            $table->integer('schedule_id')->unsigned();
            $table->integer('cost')->nullable();
            $table->timestamps();
        });
        Schema::table('doctor_treatments', function($table){
            $table->foreign('treatment_id')->references('id')->on('treatments');
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('schedule_id')->references('id')->on('center_doctor_schedule');        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_treatments');
    }
}
