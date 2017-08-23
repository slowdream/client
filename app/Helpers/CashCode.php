<?php

namespace App\Helpers;
require_once base_path().'/app/libs/cashcode/cashcode.php';
require_once base_path().'/app/libs/cashcode/cashcodes.php';
//use App\libs\cashcode\CashValidator;

class CashCode{

	use cashcodes;
	public $info = ['error' => ''];
	private $validator = new CashValidator();
	private $cash;


	public function __construct($cashClass)
	{
		$this->cash = $cashClass;
	}
		
	public function __destruct()
	{
		$this->open();
		$this->validator->ExecuteCommand($this->BillToBill_CMD["Reset"]);
	}
	public function info($val)
	{
		$this->info = array_merge($this->info, $val)
	}
	public function start() {
		$this->info(['info' => "Try open..."]);
		if (!$this->open()){
			$this->info['error'] = "Validator is not opened!";			
			return false;
		}
		$this->info(['info' => "Reset..."]);	
		if (!(($this->validator->ExecuteCommand($this->BillToBill_CMD["Reset"])) && ($this->CommandResult(3) == 0))){
			$this->info(['error' => "Failed to reset!"]);
			return false;
		}
		$this->info(['info' => "Enable Bill Types..."]);	
		if (!(($this->validator->ExecuteCommand($this->BillToBill_CMD["EnableBillTypes"])) && ($this->CommandResult(3) == 0))){
			$this->info(['error' => "Failed to set bill types!"]);
			return false;
		}

	}
	//Тут у нас бесконечный loop
	public function poll($LastCode)
	{
		if ($this->ExecuteCommand($this->BillToBill_CMD["Poll"])){

			$this->validator->sendACK();
			$Code = $this->CommandResult(3);
			if ($Code != 0){
				if ($Code == $LastCode){
					$this->info(['info' => "wait"]);				
				}else{
					$LastCode = $Code;
					$ExtendedCode = $this->CommandResult(4);
					$massage = dechex($Code)."H ".$this->BillToBill_Code[$Code];
					if ($this->BillToBill_ExtendedCode[$Code][$ExtendedCode] != "") {
						$massage .= " - ".$this->BillToBill_ExtendedCode[$Code][$ExtendedCode];
					}
					$this->info(['massage' => $massage])
					switch ($Code)
					{
						case 0x43:
						case 0x44:
						case 0x47:
						case 0x48:
						//Купюру зажевало
							$LastCode = 666;

							break;
						case 0x80:
							ExecuteCommand($this->BillToBill_CMD["Stack"]);
							break;
						case 0x81:
							$this->info(['info' => "*BILLING MONEY*"]);
							$this->cash::create([
				    			'value' => $ExtendedCode,
				    			'status' => 'wait'
				    		]);
							break;
					}
				}
			}
		} else {
			usleep(300 * 1000);
		}
		return $LastCode;
	}
	// Возвращает расшифрованный код ответа
	// Под цифрой 3 идет стандартный ответ, под 4 расширенный
	public function CommandResult($i)
	{
		return ord($this->validator->CommandResult[$i]);
	}
	// public function start()
	// {
	// 	if(!$this->reset()){
	// 		return $this->info;
	// 	}

	// }

}