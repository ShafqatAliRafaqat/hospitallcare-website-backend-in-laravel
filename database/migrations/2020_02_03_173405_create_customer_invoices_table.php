<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('customer_invoices', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('claim_id')->unsigned();
                $table->string('image')->nullable();
                $table->timestamps();
            });
            Schema::table('customer_invoices', function($table){
                $table->foreign('claim_id')->references('id')->on('customer_claims')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_invoices');
    }
}
