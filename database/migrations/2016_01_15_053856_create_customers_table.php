<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('ref')->nullable();
            $table->string('card_id')->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('city_name')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('marital_status')->nullable();
            $table->date('dob')->nullable();
            $table->float('age')->nullable();
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->text('notes')->nullable();
            $table->integer('organization_id')->unsigned();
            $table->tinyInteger('org_verified')->default(0);
            $table->integer('blood_group_id')->unsigned();
            $table->string('parent_id')->nullable();
            $table->string('employee_code')->nullable();
            $table->integer('status_id')->unsigned();
            $table->string('relation')->nullable();
            $table->boolean('doctor_id')->nullable();
            $table->string('phone_verified')->nullable();
            $table->string('reset_password')->nullable();
            $table->date('next_contact_date')->nullable();
            $table->string('patient_coordinator_id')->nullable();
            $table->tinyInteger('customer_lead')->default(0);
            $table->softdeletes();
            $table->timestamps();
        });
        Schema::table('customers', function($table){
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('blood_group_id')->references('id')->on('blood_groups');
            $table->foreign('status_id')->references('id')->on('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
