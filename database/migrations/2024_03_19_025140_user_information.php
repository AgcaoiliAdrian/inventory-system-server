<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_information', function(Blueprint $table){
            $table -> increments('id');
            $table -> string('employee_name');
            $table -> string('job_position');
            $table -> string('employment_status');
            $table -> string('system_role');
            $table -> string('branch_assigned');
            $table -> string('contact_number');

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
        Schema::dropIfExists('user_information');
    }
}
