<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('admin_log')){
            Schema::create('admin_log', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('message');
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
        Schema::dropIfExists('admin_log');
    }
}
