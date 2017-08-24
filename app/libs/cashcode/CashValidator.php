<?php
namespace App\libs\cashcode;
use Log;
class CashValidator{

	public $ValidatorHandle = null;
	public $CommandResult = null;

	function open()
	{
		return $this->ValidatorHandle = fopen("/dev/ttyS0", "r+");
	}

	function close()
	{
		if ($this->ValidatorHandle) {fclose($this->ValidatorHandle);}
	}

	function sendACK($ack)
	{
		
		if ($this->ValidatorHandle) {fwrite($this->ValidatorHandle, $ack);}
	}

	function ErrorHandler($errno, $errstr, $errfile, $errline)
	{
		$data["error_text"] = $errstr;
		return false;
	}

	function ExecuteCommand($Command, $Waiting = true)
	{
		if ($this->ValidatorHandle)
		{
			fwrite($this->ValidatorHandle, $Command);
			if (!$Waiting) {
				return true;
			}
			Log::info(1);
			$result = null;
			$cur_time = time();
			$expire_time = time() + 5; //Максимум 5 сек ждем
			while (time() < $expire_time)
			{Log::info(2);
				$result .= fread($this->ValidatorHandle, 255);
				Log::info(3);
				if (($result) && (ord($result[2]) > 0) && (strlen($result) >= ord($result[2])))
				{
					$this->CommandResult = $result;
					break;
				}
				else
				{usleep(50 * 1000);}
			}
		}
		if ($this->CommandResult)
		{return true;}
		else
		{return false;}
	}

	public function readPort()
	{
		$result = null;
		$cur_time = time();
		$expire_time = $cur_time + 5; //Максимум 5 сек ждем
		while (time() < $expire_time)
		{
			$result .= fread($this->ValidatorHandle, 255);
			if ($result)
			{
				return $result;
				break;
			}
			else
			{usleep(50 * 1000);}
		}
		return false;
	}

}