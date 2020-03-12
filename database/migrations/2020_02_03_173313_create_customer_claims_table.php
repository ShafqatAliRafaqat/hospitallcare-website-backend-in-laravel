<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_claims', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->string('title')->nullable();
            $table->string('appointment_for')->nullable();
            $table->string('appointment_fee')->nullable();
            $table->string('cth_id')->nullable();  //customer Treatment History ID
            $table->string('status')->nullable();
            $table->string('internal_comment')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('customer_claims', function($table){
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('cth_id')->references('id')->on('customer_treatment_history')->onDelete('cascade'); //customer Treatment History ID
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_claims');
    }
}
