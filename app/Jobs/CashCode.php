<?php

namespace App\Jobs;

use App\Cash;
use CashCode as validator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CashCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $timeOut;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($timeOut = 50)
    {
        $this->timeOut = $timeOut;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $Cash = new Cash();
        // Пока так, защита от будущих повисаний
        $Cash::where(['status' => 'wait'])->delete();
        $Cash::create(['status' => 'wait']);
        $timeOut = $this->timeOut;
        //$Cash::firstOrCreate(['status' => 'wait']);
        $timeStart = time();
        $validator = new validator($Cash);
        $Repeat = true;

        while ($Repeat) {
            $LastCode = null;
            $Repeat = false;
            $banknote = '';
            if ($validator->start()) {
                while (true) {
                    $banknote = Cash::where('status', 'wait')->first();
                    if (!$banknote) {
                        // Если не найдена запись со статусом wait, значит остановим прием
                        $validator->sendCommand('DisableBillTypes');
                        break;
                    }
                    $LastCode = $validator->poll($LastCode);
                    if ((time() - $timeStart) > $timeOut) {
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
                if ($Repeat) {
                    sleep(1);
                }
            } else {
                // TODO: Надо чет делать если не получилось запуститься
            }
        }
    }
}
