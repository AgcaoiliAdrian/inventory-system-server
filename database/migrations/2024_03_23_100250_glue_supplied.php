<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GlueSupplied extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('glue_supplied', function (Blueprint $table){
            $table -> increments('id');
            $table -> unsignedInteger('supplier_id');
            $table -> string('glue_type');
            $table -> string('glue_brand');
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
        Schema::dropIfExists('glue_supplied');
    }
}
