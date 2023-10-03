<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('name')->unique();
            $table->unsignedBigInteger('blog_category_id')->nullable();
            $table->foreign('blog_category_id')->references('id')->on('blog_categorys');
            $table->string('blog_author')->nullable();
            $table->string('image')->nullable();
            $table->string('blog_banner_image')->nullable();
            $table->date('blog_date')->nullable();
            $table->text('blog_short_desc')->nullable();
            $table->text('blog_long_desc')->nullable();
            $table->enum('status',['1','2'])->default('1')->comment="1=Active,2=Inactive";
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('blogs');
    }
}
