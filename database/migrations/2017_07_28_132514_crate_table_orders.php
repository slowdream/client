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
  public function up ()
  {
    Schema::create('orders', function (Blueprint $table) {
      $table->increments('id');
      // guid = Айдишник заказа пришедший от 1С, заполняется только для заказов со статусом "отправлен"
      $table->integer('guid')->nullable();
      /*
          status:
              active = Активный заказ сессии
              complete = завершен и готовится к отправке
              sended = Отправлен в 1С
              canceled = Отменен

      */
      $table->string('status')->nullable(); // Статус заказа
      $table->string('contacts')->nullable(); // Контактные данные в json формате
      /*
          reason:
              fullpayed = оплачен полностью
              prepayed = предоплата
              partpayed = Деньги внесены не полностью
              timeout = Закрыт по таймауту
              cancel = Отменен из корзины
              clear = Очищен
      */
      $table->string('reason')->nullable(); // Причина закрытия
      $table->integer('cash_id')->nullable(); // Купюры привязанные к заказу
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down ()
  {
    Schema::dropIfExists('orders');
  }
}
