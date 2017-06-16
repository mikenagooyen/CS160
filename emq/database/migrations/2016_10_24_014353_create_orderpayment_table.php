<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderpaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('order_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nameOnCard');
            $table->integer('lastFour');
            $table->integer('expMonth');
            $table->integer('expYear');
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
        Schema::drop('order_payment');
    }
}
