<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempDoctorTreatmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_doctor_treatment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('treatment_id')->unsigned();
            $table->integer('doctor_id')->unsigned();
            $table->integer('parent_id')->nullable();
            $table->timestamps();
        });
        Schema::table('temp_doctor_treatment', function($table){
            $table->foreign('treatment_id')->references('id')->on('treatments');
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
        Schema::dropIfExists('temp_doctor_treatment');
    }
}
