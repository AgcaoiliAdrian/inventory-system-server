<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TempBatchIn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_batch_in', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('barcode_id')->nullable();
            $table->unsignedInteger('grade_id');
            $table->unsignedInteger('glue_type_id');
            $table->unsignedInteger('thickness_id');
            $table->unsignedInteger('variant_id')->nullable();
            $table->unsignedInteger('brand_id');
            $table->integer('quantity');
            $table->date('manufacturing_date');
            $table->string('status');

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
        Schema::dropIfExists('temp_batch_in');
    }
}
