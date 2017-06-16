<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('payment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('nameOnCard');
            $table->string('cardNumber');
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
        Schema::dropIfExists('payment');
    }
}
