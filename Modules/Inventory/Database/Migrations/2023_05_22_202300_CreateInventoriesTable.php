<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products', 'id');
            $table->string('title')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->float('sale_price')->nullable();
            $table->float('offer_price')->nullable();
            $table->timestamp('offer_start')->nullable();
            $table->timestamp('offer_end')->nullable();
            $table->integer('stock_quantity')->nullable();
            $table->integer('reorder_quantity')->nullable();
            $table->enum('is_special_deal', ['1', '2'])->nullable();
            $table->enum('is_manage_stock', ['1', '2'])->nullable();
            $table->integer('min_order_quantity')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['1', '2'])->nullable()->default('1')->comment("1=Active, 2=Inactive");
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
        Schema::dropIfExists('inventories');
    }
}
