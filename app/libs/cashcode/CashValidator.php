<?php
namespace App\libs\cashcode;
use Log;
class CashValidator{

  public $ValidatorHandle = null;
  public $CommandResult = null;

  function open()
  {
    $port = "/dev/ttyS" + env('ID_COM_PORT', 0);
    return $this->ValidatorHandle = fopen($port, "r+");
    //return $this->ValidatorHandle = fopen("com1", "w+b");
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
      Log::info('FWrite "ExecuteCommand" start');
      fwrite($this->ValidatorHandle, $Command);
      Log::info('FWrite "ExecuteCommand" end');
      if (!$Waiting) {
        return true;
      }
      $result = null;
      $cur_time = time();
      $expire_time = time() + 5; //Максимум 5 сек ждем
      while (time() < $expire_time)
      {
        Log::info('FRead "ExecuteCommand" start');
        $result .= fread($this->ValidatorHandle, 255);
        Log::info('FRead "ExecuteCommand" end');
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
      Log::info('FRead "readPort" start');
      $result .= fread($this->ValidatorHandle, 255);
      Log::info('FRead "readPort" end');
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