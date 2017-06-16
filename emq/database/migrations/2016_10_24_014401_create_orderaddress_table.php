<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderaddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_address', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('fullName');
            $table->string('address');
            $table->string('address2');
            $table->string('city');
            $table->string('state');
            $table->integer('zip');
            $table->string('country');
            $table->string('phone');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_address');
    }
}
