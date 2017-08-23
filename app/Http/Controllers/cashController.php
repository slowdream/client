<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Cash;
use Server1C;
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
    	$validator = new CashCode($Cash);
		$Repeat = true;
		while ($Repeat) {
			$LastCode = null;
			$Repeat = false;
			$validator->start();	
			
			while(true){
				$LastCode = $validator->poll($LastCode);
				if ($LastCode === 666) {
					$Repeat = true;
				}
				dd($validator->info);
			}		

			if ($Repeat) {sleep(1);}
		}
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
