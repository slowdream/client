<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use App\OrderProds;
use App\Cash;
use CashCode;

/**
 * Class cashController
 * @package App\Http\Controllers\api
 */
class cashController extends Controller
{

  public function summ()
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
  }

  /*
    Запускается один раз и работает в фоне. Частые обращения приведут к зависанию
  */
  public function getCash(Request $request, Cash $Cash)
  {
    $timeOut = 5;
    $timeStart = time();
    //$min = $request->input('minimum');
    $min = 500;

    $validator = new CashCode($Cash);
    $Repeat = true;

    while ($Repeat) {
      $LastCode = null;
      $Repeat = false;
      $banknote = '';
      if ($validator->start()){
        while(true){
          $banknote = Cash::where('status', 'wait')->first();
          if (!$banknote) {
            break;
          }
          $LastCode = $validator->poll($LastCode, $min);
          if ((time() - $timeStart) > $timeOut){
              echo "timeOut";
              break;
          }
          if ($LastCode === 666) {
              $Repeat = true;
          }
        }
        if ($Repeat) {sleep(1);}
      } else {
        echo 'fail start';
        dump($validator->info);
      }
    }
    //echo $validator->sendCommand('ACK');
    //$validator->validator->close();
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
