<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address');
            $table->string('address2');
            $table->string('city');
            $table->string('state');
            $table->integer('zip');
            $table->string('country');
            $table->string('phone');
            $table->double('salesTax');
            $table->string('lat');
            $table->string('lng');
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
        Schema::drop('store');
    }
}
