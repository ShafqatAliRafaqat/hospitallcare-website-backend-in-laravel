<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('degree')->nullable();
            $table->string('pmdc')->nullable();
            $table->string('phone')->nullable();
            $table->string('speciality')->nullable();
            $table->string('focus_area')->nullable();
            $table->boolean('gender')->nullable()->default(0);
            $table->string('address')->nullable();
            $table->string('city_name')->nullable();
            $table->double('lat', 10,8)->nullable();
            $table->double('lng', 11,8)->nullable();
            $table->string('assistant_name')->nullable();
            $table->string('assistant_phone')->nullable();
            $table->string('notes')->nullable();
            $table->string('about')->nullable();
            $table->string('ad_spent')->nullable();
            $table->string('revenue_share')->nullable();
            $table->string('additional_details')->nullable();
            $table->integer('experience')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('url')->nullable();
            $table->boolean('on_web')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(0);
            $table->boolean('is_approved')->nullable()->default(0);
            $table->boolean('is_partner')->nullable()->default(0);
            $table->boolean('phone_verified')->nullable()->default(0);
            $table->softdeletes();
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
        Schema::dropIfExists('doctors');
    }
}
