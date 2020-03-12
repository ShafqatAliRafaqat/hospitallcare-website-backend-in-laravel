<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('employee_code')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('address')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('marital_status')->nullable();
            $table->float('age')->nullable();
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->unsignedInteger('organization_id')->nullable();
            $table->string('treatment')->nullable();
            $table->string('notes')->nullable();
            $table->boolean('lead_from')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_customers');
    }
}
