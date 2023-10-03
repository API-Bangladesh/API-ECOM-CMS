<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('order_date')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers', 'id');
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->float('sub_total')->nullable();
            $table->float('discount')->nullable();
            $table->float('shipping_charge')->nullable();
            $table->float('tax')->nullable();
            $table->float('grand_total')->nullable();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods', 'id');
            $table->json('payment_details')->nullable();
            $table->tinyInteger('payment_status_id')->nullable();
            $table->tinyInteger('order_status_id')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
