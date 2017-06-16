<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('admins')){
            Schema::create('admins', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('role');
                $table->nullableTimestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('admins');
    }
}
