<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BarcodeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcode_details', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('variant_id')->nullable();
            $table->unsignedInteger('glue_type_id')->nullable();
            $table->unsignedInteger('thickness_id')->nullable();
            $table->string('barcode_number');

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
        Schema::dropIfExists('barcode_details');
    }
}
