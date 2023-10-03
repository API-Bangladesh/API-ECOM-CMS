<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->foreignId('order_id')->nullable()->constrained('orders', 'id')->cascadeOnDelete();
            $table->foreignId('inventory_id')->nullable()->constrained('inventories', 'id');
            $table->foreignId('combo_id')->nullable()->constrained('combos', 'id');
            $table->float('quantity')->nullable();
            $table->float('unit_price')->nullable();
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
        Schema::dropIfExists('order_items');
    }
}
