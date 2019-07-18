<?php

namespace App\Http\Controllers;

use App\Cash;
use CashCode;

class cashController extends Controller
{
    public function summ()
    {
        //Отдаем сумму введеных купюр
        $summ = 0;
        $cash = Cash::where('status', 'wait')->get();
        foreach ($cash as $item) {
            $summ += $item->value;
        }

        return $summ;
    }

    public function getCash(Cash $Cash)
    {
        $timeOut = 15;
        $timeStart = time();

        $validator = new CashCode($Cash);
        $Repeat = true;

        while ($Repeat) {
            $LastCode = null;
            $Repeat = false;

            if ($validator->start()) {
                while (true) {
                    $LastCode = $validator->poll($LastCode);

                    if ((time() - $timeStart) > $timeOut) {
                        echo 'timeOut';
                        break;
                    }
                    if ($LastCode === 666) {
                        $Repeat = true;
                    }
                }
                if ($Repeat) {
                    sleep(1);
                }
            } else {
                echo 'fail start';
                dump($validator->info);
            }
        }
    }

    public function seed()
    {
        $Banknotes = [50, 100, 500, 1000];
        for ($i = 0; $i < 50; $i++) {
            Cash::create([
              'value'  => $Banknotes[array_rand($Banknotes, 1)],
              'status' => 'inbox',
            ]);
        }
        for ($i = 0; $i < 5; $i++) {
            Cash::create([
              'value'  => $Banknotes[array_rand($Banknotes, 1)],
              'status' => 'wait',
            ]);
        }
    }
}
