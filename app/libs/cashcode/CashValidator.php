<?php
namespace App\libs\cashcode;
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
				return;
			}
			$result = null;
			$cur_time = time();
			$expire_time = mktime(date("H", $cur_time), date("i", $cur_time), date("s", $cur_time) + 5, date("m", $cur_time), date("d", $cur_time), date("Y", $cur_time));
			while (time() < $expire_time)
			{
				$result .= fread($this->ValidatorHandle, 255);
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

}