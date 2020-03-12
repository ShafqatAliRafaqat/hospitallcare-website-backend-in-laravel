<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerDiagnosticHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_diagnostic_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('lab_id')->unsigned();
            $table->integer('diagnostic_id')->unsigned();
            $table->integer('cost')->nullable();
            $table->integer('discounted_cost')->nullable();
            $table->integer('discount_per')->default(0);
            $table->integer('appointment_from')->default(0);
            $table->integer('home_sampling')->default(0);
            $table->dateTime('appointment_date')->nullable();
            $table->timestamps();
        });

        Schema::table('customer_diagnostic_history', function($table){
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');
            $table->foreign('diagnostic_id')->references('id')->on('diagnostics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_diagnostic_history');
    }
}
