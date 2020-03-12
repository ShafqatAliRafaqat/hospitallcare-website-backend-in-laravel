<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_centers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('center_name');
            $table->string('focus_area');
            $table->double('lat', 10,8);
            $table->double('lng', 11,8);
            $table->string('city_name');
            $table->string('address');
            $table->boolean('facilitator')->nullable();
            $table->string('degree')->nullable();
            $table->string('phone')->nullable();
            $table->string('assistant_name')->nullable();
            $table->string('assistant_phone')->nullable();
            $table->string('notes')->nullable();
            $table->string('ad_spent')->nullable();
            $table->string('revenue_share')->nullable();
            $table->string('additional_details')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('url')->nullable();
            $table->string('requested_by')->nullable();
            $table->boolean('is_active')->nullable()->default(0);
            $table->boolean('is_approved')->nullable()->default(0);
            $table->boolean('is_sponsered')->nullable()->default(0);
            $table->boolean('on_web')->nullable()->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
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
        Schema::dropIfExists('medical_centers');
    }
}
