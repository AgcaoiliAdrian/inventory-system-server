<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PanelStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panel_stock', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('barcode_id')->nullable();
            $table->integer('quantity');
            $table->date('manufacturing_date');
            $table->string('grader');
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
        Schema::dropIfExists('panel_stock');
    }
}
