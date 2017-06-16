<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('productName');
            $table->integer('quantity');
            $table->string('category');
            $table->string('brand');
            $table->string('image');
            $table->text('description');
            $table->decimal('price',10,2);
            $table->boolean('available');
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
        Schema::drop('products');
    }
}
