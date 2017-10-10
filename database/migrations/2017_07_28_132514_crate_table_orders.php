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
            /*
                status:
                    active = Активный заказ сессии
                    payed = Оплачен и готовится к отправке
                    sended = Отправлен в 1С

            */
            $table->string('status')->nullable(); // Статус заказа (формируется в терминале \ Отменен \ Оформлен)
            $table->string('contacts')->nullable(); // Контактные данные в json формате
            /*
                whyCanceled:
                    payed = оплачен
                    timeout = Закрыт по тай ауту
                    canceled = Отменен из корзины
            */
            $table->string('whyCanceled')->nullable(); // Причина закрытия (По тайм ауту \ Отменен в ручную \ Оформлен)
            $table->integer('cash_id')->nullable(); // Купюры привязанные к заказу
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
