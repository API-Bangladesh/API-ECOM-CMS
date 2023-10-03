<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('content_categorys');
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('content_types');
            $table->unsignedBigInteger('module_id');
            $table->foreign('module_id')->references('id')->on('content_modules');
            $table->string('image')->nullable();
            $table->string('item_image_banner')->nullable();
            $table->string('item_link')->nullable();
            $table->string('item_video_link')->nullable();
            $table->date('item_date')->nullable();
            $table->text('item_short_desc')->nullable();
            $table->text('item_long_desc')->nullable();
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
        Schema::dropIfExists('content_items');
    }
}
