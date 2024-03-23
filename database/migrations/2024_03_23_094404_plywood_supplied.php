<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlywoodSupplied extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plywood_supplied', function(Blueprint $table){
            $table -> increments('id');
            $table -> unsignedInteger('supplier_id');
            $table -> string('plywood_type');
            $table -> string('plywood_brand');
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
        Schema::dropIfExists('plywood_supplied');
    }
}
