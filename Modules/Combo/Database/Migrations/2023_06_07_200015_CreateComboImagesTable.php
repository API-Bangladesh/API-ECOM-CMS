<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComboImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combo_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_id')->nullable()->constrained('combos', 'id');
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
        Schema::dropIfExists('combo_images');
    }
}
