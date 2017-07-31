<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateTableOrderProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_prods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product'); // Айдишник товара
            $table->integer('count'); // Количество
            $table->integer('price'); // Цена
            $table->integer('order'); // Айдишник заказа
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
        Schema::dropIfExists('orders_prods');
    }
}
