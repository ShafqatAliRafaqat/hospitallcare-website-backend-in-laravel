<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('message')->nullable();
            $table->string('headline')->nullable();
            $table->string('link_description')->nullable();
            $table->longText('article')->nullable();
            $table->string('article_heading')->nullable();
            $table->string('parent_id')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('url')->nullable();
            $table->string('url')->nullable();
            $table->string('payload_en')->nullable();
            $table->string('payload_ur')->nullable();
            $table->string('leading_page_url')->nullable();
            $table->boolean('is_active')->nullable()->default(0);
            $table->boolean('show_in_menu')->nullable()->default(0);
            $table->boolean('position')->nullable()->default(0);
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
        Schema::dropIfExists('treatments');
    }
}
