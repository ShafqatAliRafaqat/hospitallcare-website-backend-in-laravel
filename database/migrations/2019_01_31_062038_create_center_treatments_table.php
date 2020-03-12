<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCenterTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('center_treatments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('med_centers_id')->unsigned();
            $table->integer('treatments_id')->unsigned();
            $table->integer('cost')->nullable();
            $table->timestamps();
        });
        Schema::table('center_treatments', function($table){
            $table->foreign('treatments_id')->references('id')->on('treatments')->onDelete('cascade');        
            $table->foreign('med_centers_id')->references('id')->on('medical_centers')->onDelete('cascade');        
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('center_treatments');
    }
}
