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
    $this->getCash($min);
  }

  /*
    Запускается один раз и работает в фоне. Частые обращения приведут к зависанию
  */
  public function getCash ($min = 500)
  {
    ignore_user_abort(1);
    $Cash = new Cash();
    $Cash::firstOrCreate(['status' => 'wait']);
    $timeOut = 30;
    $timeStart = time();
    $validator = new CashCode($Cash);
    $Repeat = true;

    while ($Repeat) {
      $LastCode = null;
      $Repeat = false;
      $banknote = '';
      if ($validator->start()){
        while(true){
          $banknote = Cash::where('status', 'wait')->first();
          //dd($banknote);
          if (!$banknote) {
            break;
          }
          $LastCode = $validator->poll($LastCode, $min);
          if ((time() - $timeStart) > $timeOut){
              // отрубаем по таймауту
              $validator->sendCommand('DisableBillTypes');
              break;
          }
          if ($LastCode === 666) {
              // Я не помню зачем, но вроде так надо
              // Если купюру зажевало - пройтись с нуля по циклу.
              $Repeat = true;
          }
          if ($LastCode == 129) {
            // Если купюра принята - обнуляем таймаут
            $timeStart = time();
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
