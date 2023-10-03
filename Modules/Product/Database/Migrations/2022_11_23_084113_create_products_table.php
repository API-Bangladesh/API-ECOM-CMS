<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedBigInteger('sub_category_id');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->foreign('variant_id')->references('id')->on('variants');
            $table->unsignedBigInteger('variant_option_id')->nullable();
            $table->foreign('variant_option_id')->references('id')->on('variant_options');
            $table->unsignedBigInteger('segment_id')->nullable();
            $table->foreign('segment_id')->references('id')->on('segments');
            $table->unsignedBigInteger('pack_id')->nullable();
            $table->foreign('pack_id')->references('id')->on('pack_types');
            $table->string('product_link')->nullable();
            $table->string('product_bncn')->nullable();
            $table->string('product_video_path')->nullable();
            $table->text('product_short_desc')->nullable();
            $table->text('product_long_desc')->nullable();
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
        Schema::dropIfExists('products');
    }
}
