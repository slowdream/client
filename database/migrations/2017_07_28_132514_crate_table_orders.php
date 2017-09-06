<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateTableOrders extends Migration
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
            $table->integer('guid')->nullable(); // Айдишник заказа пришедший от 1С, заполняется только для заказов со статусом "оформлен"
            $table->string('status')->nullable(); // Статус заказа (формируется в терминале \ Отменен \ Оформлен)
            $table->string('contacts')->nullable(); // Контактные данные в json формате
            $table->string('whyCanceled')->nullable(); // Причина закрытия (По тайм ауту \ Отменен в ручную \ Оформлен)
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
        Schema::dropIfExists('orders');
    }
}
