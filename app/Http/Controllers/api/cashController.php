<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use App\OrderProds;
use App\Cash;

use App\Jobs\CashCode;

/**
 * Class cashController
 * @package App\Http\Controllers\api
 */
class cashController extends Controller
{

  public function getSumm ()
  {
    //Отдаем сумму введеных купюр
    $summ = 0;
    $cash = Cash::where('status', 'injected')->get();
    foreach ($cash as $item) {
      $summ += $item->value;
    }
    return $summ;
  }

  public function endCash ()
  {
    Cash::where('status', 'wait')->delete();
    $cash = Cash::where('status', 'injected')->get();
    foreach ($cash as $banknote) {
      $banknote->status = 'inbox';
      $banknote->save();
    }
  }

  /*
   Запускает процедуру приема денег
  */
  public function startCash (Request $Request)
  {
    // TODO: Перед запуском проверить запущен ли уже прием
    $min = $Request->input('cash');
    $this->dispatch(new CashCode($min));
    return 'true';
  }




  public function seed()
  {
    $Banknotes = [50,100,500,1000];
    for ($i=0; $i < 50; $i++) {
      Cash::create([
        'value' => $Banknotes[array_rand($Banknotes, 1)],
        'status' => 'inbox'
      ]);
    }
    for ($i=0; $i < 5; $i++) {
      Cash::create([
        'value' => $Banknotes[array_rand($Banknotes, 1)],
        'status' => 'wait'
      ]);
    }

  }
}
