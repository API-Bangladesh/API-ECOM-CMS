<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('ratting_number')->nullable();
            $table->text('comments')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers', 'id')->cascadeOnDelete();
            $table->foreignId('inventory_id')->nullable()->constrained('inventories', 'id')->cascadeOnDelete();
            $table->foreignId('combo_id')->nullable()->constrained('combos', 'id')->cascadeOnDelete();
            $table->enum('status', ['1', '2'])->default('1')->comment="1=Approved, 2=Pending";
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
        Schema::dropIfExists('reviews');
    }
}
