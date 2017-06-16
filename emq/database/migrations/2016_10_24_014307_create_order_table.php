<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('store_id');
            $table->integer('orderaddress_id');
            $table->integer('orderpayment_id');
            $table->decimal('cost',10,2);
            $table->decimal('total',10,2);
            $table->decimal('tax',10,2);
            $table->integer('delivery_time');
            $table->boolean('delivered');
            $table->string('delivered_at')->nullable();

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
        /* temporary till everyone has refreshed database tables */
        if(Schema::hasTable('order')){
            Schema::drop('order');
        }
        
        if(Schema::hasTable('orders')){
            Schema::drop('orders');
        }
    }
}
