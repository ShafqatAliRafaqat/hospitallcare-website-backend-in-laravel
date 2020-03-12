<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verified')->default(0);
            $table->boolean('notification_status')->nullable()->default(0);
            $table->string('password');
            $table->integer('organization_id')->unsigned()->nullable();
            $table->integer('medical_center_id')->unsigned()->nullable();
            $table->integer('doctor_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('google_id');
            $table->string('facebook_id');
            $table->boolean('is_approved')->nullable()->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::table('users', function($table){
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('medical_center_id')->references('id')->on('medical_centers');
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
