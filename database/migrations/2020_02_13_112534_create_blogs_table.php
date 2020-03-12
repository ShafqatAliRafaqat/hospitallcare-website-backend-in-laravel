<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->string('title');
            $table->longText('description');
            $table->string('mete_title')->nullable();
            $table->string('mete_description')->nullable();
            $table->string('url')->nullable();
            $table->integer('is_active')->default(0);
            $table->integer('position')->default(0);        // 0 => bottom  1=> center  2=>top
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softdeletes();
            $table->timestamps();
        });
        Schema::table('blogs', function($table){
            $table->foreign('category_id')->references('id')->on('blog_category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
