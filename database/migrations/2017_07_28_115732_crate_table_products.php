<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateTableProducts extends Migration
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
            $table->string('guid'); //Айдишник в базе 1С
            $table->string('name', 100);
            $table->integer('price'); //Цена
            $table->integer('count'); //Колво
            $table->string('image', 250);
            $table->text('description');
            $table->string('unit', 10); //Единица измерения
            $table->integer('category_id'); //Айдишник Категории родительской
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}
