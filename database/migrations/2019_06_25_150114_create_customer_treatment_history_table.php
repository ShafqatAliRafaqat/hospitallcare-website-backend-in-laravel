<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTreatmentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_treatment_history', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('treatments_id')->unsigned();
            $table->integer('hospital_id')->unsigned();
            $table->integer('doctor_id')->unsigned();
            $table->integer('cost')->nullable();
            $table->integer('discounted_cost')->nullable();
            $table->integer('discount_per')->default(0);
            $table->integer('status')->default(0);
            $table->integer('appointment_from')->default(0);
            $table->integer('home_sampling')->default(0);
            $table->dateTime('appointment_date')->nullable();
            $table->timestamps();
        });
        Schema::table('customer_treatment_history', function($table){
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('treatments_id')->references('id')->on('treatments')->onDelete('cascade');
            $table->foreign('hospital_id')->references('id')->on('medical_centers')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_treatment_history');
    }
}
