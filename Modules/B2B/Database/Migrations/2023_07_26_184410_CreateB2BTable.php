<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2BTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b', function (Blueprint $table) {
            $table->id();
            $table->string('country_name')->nullable();
            $table->string('name')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_quantity')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email_address')->nullable();
            $table->enum('status', ['1', '2', '3', '4', '5'])->nullable()->default('1')->comment("1=Pending, 2=In Progress, 3=Processing, 4=Communicated, 5=Cancelled");
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
        Schema::dropIfExists('b2b');
    }
}
