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
            $table->integer('guid')->nullable(); // Айдишник товара в 1С
            $table->integer('product_id')->nullable(); // Айдишник товара в нашей базе
            $table->integer('count')->nullable(); // Количество
            $table->integer('price')->nullable(); // Цена
            $table->integer('order_id')->nullable(); // Айдишник заказа
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
