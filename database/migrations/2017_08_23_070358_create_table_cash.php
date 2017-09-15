<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCash extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('cash', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('value'); // Номинал купюры
      /*
        inbox - в боксе,
        extracted - Извлечено инкассатором,
        wait - ожидает поступления купюры,
        injected - купюра получена и ожидает зачисления
      */
      // $table->string('status')->default('wait'); //
      $table->string('status'); //
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
        Schema::dropIfExists('cash');
    }
}
