<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Cash;
use CashCode as validator;

class CashCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $min;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($min)
    {
      $this->min = $min;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $min = $this->min;
      $Cash = new Cash();
      $Cash::firstOrCreate(['status' => 'wait']);
      $timeOut = 50;
      $timeStart = time();
      $validator = new validator($Cash);
      $Repeat = true;

      while ($Repeat) {
        $LastCode = null;
        $Repeat = false;
        $banknote = '';
        if ($validator->start()){
          while(true){
            $banknote = Cash::where('status', 'wait')->first();
            if (!$banknote) {
              // Если не найдена запись со статусом wait, значит остановим прием
              $validator->sendCommand('DisableBillTypes');
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
          // TODO: Надо чет делать если не получилось запуститься
        }
      }
    }
}
