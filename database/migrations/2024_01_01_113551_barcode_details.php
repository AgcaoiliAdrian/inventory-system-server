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
            $table->unsignedInteger('variant_id');
            $table->unsignedInteger('glue_type_id');
            $table->unsignedInteger('thickness_id');
            $table->unsignedInteger('grade_id');
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
