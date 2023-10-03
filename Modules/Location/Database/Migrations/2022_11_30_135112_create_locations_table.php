<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('retail_code')->nullable();
            $table->string('name')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('address')->nullable();
            $table->string('zone')->nullable();
            $table->string('sales_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
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
        Schema::dropIfExists('locations');
    }
}
