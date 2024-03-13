<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crate_stock', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('barcode_id')->nullable();
            $table->unsignedInteger('grade_id');
            $table->integer('quantity');
            $table->date('manufacturing_date');
            $table->string('batch_number')->nullable();
            $table->string('status')->nullable();

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
        Schema::dropIfExists('crate_stock');
    }
}
