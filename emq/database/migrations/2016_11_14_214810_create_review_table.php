<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('reviews')){
            Schema::create('reviews', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('rating');
                $table->integer('product_id');
                $table->integer('user_id');
                $table->string('review')->nullable();
                $table->integer('helpful')->default(0);
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
        Schema::dropIfExists('reviews');
    }
}
