<?php

namespace App\Helpers;
//require_once base_path().'/app/libs/cashcode/CashValidator.php';
//require_once base_path().'/app/libs/cashcode/cashcodes.php';
use App\libs\cashcode\CashValidator;
use App\libs\cashcode\cashcodes;
use App\Order;
use Log;
class CashCode{

  use cashcodes;
  public $info = [];
  private $BillToBill_CMD = [];
  public $validator;
  private $cash;
  private $order;

  public function __construct($cashClass)
  {
    $this->cash = $cashClass;
    $this->order = Order::firstOrCreate(['status'=>'active']);
    $this->validator = new CashValidator();

    $this->BillToBill_CMD = [
      "ACK" => pack("c*", 0x02, 0x03, 0x06, 0x00, 0xC2, 0x82),
      "Reset" => pack("c*", 0x02, 0x03, 0x06, 0x30, 0x41, 0xB3),
      "GetStatus" => pack("c*", 0x02, 0x03, 0x06, 0x31, 0xC8, 0xA2),
      "SetSecurity" => pack("c*", 0x02, 0x03, 0x06, 0x32, 0x53, 0x90),
      "Poll" => pack("c*", 0x02, 0x03, 0x06, 0x33, 0xDA, 0x81),
      "EnableBillTypes" => pack("c*", 0x02, 0x03, 0x0C, 0x34, 0xFF, 0xFF, 0xFF, 0x00, 0x00, 0x00, 0xB5, 0xC1),
      "EnableBillTypesEscrow" => pack("c*", 0x02, 0x03, 0x0C, 0x34, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFE, 0xF7),
      "DisableBillTypes" => pack("c*", 0x02, 0x03, 0x0C, 0x34, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x17, 0x0C),
      "Stack" => pack("c*", 0x02, 0x03, 0x06, 0x35, 0xEC, 0xE4),
      "Return" => pack("c*", 0x02, 0x03, 0x06, 0x36, 0x77, 0xD6),
      "Identification" => pack("c*", 0x02, 0x03, 0x06, 0x37, 0xFE, 0xC7),
      "Hold" => pack("c*", 0x02, 0x03, 0x06, 0x38, 0x09, 0x3F),
      "CassetteStatus" => pack("c*", 0x02, 0x03, 0x06, 0x3B, 0x92, 0x0D),
      "Dispense" => pack("c*", 0x02, 0x03, 0x06, 0x3C, 0x2D, 0x79),
      "Unload" => pack("c*", 0x02, 0x03, 0x06, 0x3D, 0xA4, 0x68),
      "EscrowCassetteStatus" => pack("c*", 0x02, 0x03, 0x06, 0x3E, 0x3F, 0x5A),
      "EscrowCassetteUnload" => pack("c*", 0x02, 0x03, 0x06, 0x3F, 0xB6, 0x4B),
      "SetCassetteType" => pack("c*", 0x02, 0x03, 0x06, 0x40, 0xC6, 0xC0),
      "GetBillTable" => pack("c*", 0x02, 0x03, 0x06, 0x41, 0x4F, 0xD1),
      "Download" => pack("c*", 0x02, 0x03, 0x06, 0x50, 0x47, 0xD0),
    ];
  }

  public function __destruct()
  {
    //$this->validator->open();
    //$this->validator->ExecuteCommand($this->BillToBill_CMD["Reset"]);
  }
  public function info($val)
  {
    Log::info($val);
    $this->info = array_merge($this->info, $val);
  }
  public function start() {

    $this->info(['info' => "Try open..."]);
    if (!$this->validator->open()){
      $this->info(['error' => "Validator is not opened!"]);
      $this->validator->close();
      return false;
    }

    $this->info(['info' => "Poll..."]);
    if (!(($this->validator->ExecuteCommand($this->BillToBill_CMD["Poll"])) && ($this->CommandResult(3) == 0))){
      $codeMore = $this->CommandResult(4);
      $this->info(['error' => "Failed to Poll!",'more' => $codeMore]);
      // Если код ответа был 25, можно попробовать ресетнуть, должно помочь
      if ($codeMore == 25) {
        $this->info(['info' => "Reset..."]);
        if (!(($this->validator->ExecuteCommand($this->BillToBill_CMD["Reset"])) && ($this->CommandResult(3) == 0))){
          $this->info(['error' => "Failed to reset!",'more' => $this->CommandResult(3)]);
          $this->validator->close();
          return false;
        }
        // Если ресет прошел удачно пробуем отправить Poll снова.
        // if (!(($this->validator->ExecuteCommand($this->BillToBill_CMD["Poll"])) && ($this->CommandResult(3) == 0))){
        //   $codeMore = $this->CommandResult(3);
        //   $this->info(['error' => "Failed to Poll!",'more' => $codeMore]);
        //   return false;
        // }
      }
    }

    // Чисто чтобы пожжужал для привлечения внимания
    $this->info(['info' => "Reset..."]);
    if (!(($this->validator->ExecuteCommand($this->BillToBill_CMD["Reset"])) && ($this->CommandResult(3) == 0))){
      $this->info(['error' => "Failed to reset!", 'more' => $this->CommandResult(3)]);
      $this->validator->close();
      return false;
    }

    $this->info(['info' => "Poll..."]);
    if (!(($this->validator->ExecuteCommand($this->BillToBill_CMD["Poll"])) && ($this->CommandResult(3) == 0))){
      $codeMore = $this->CommandResult(4);
      $this->info(['error' => "Failed to Poll!",'more' => $codeMore]);
      // Если код ответа был 25, можно попробовать ресетнуть, должно помочь
      if ($codeMore == 25) {
        $this->info(['info' => "Reset..."]);
        if (!(($this->validator->ExecuteCommand($this->BillToBill_CMD["Reset"])) && ($this->CommandResult(3) == 0))){
          $this->info(['error' => "Failed to reset!",'more' => $this->CommandResult(3)]);
          $this->validator->close();
          return false;
        }
        // Если ресет прошел удачно пробуем отправить Poll снова.
        // if (!(($this->validator->ExecuteCommand($this->BillToBill_CMD["Poll"])) && ($this->CommandResult(3) == 0))){
        //   $codeMore = $this->CommandResult(3);
        //   $this->info(['error' => "Failed to Poll!",'more' => $codeMore]);
        //   return false;
        // }
      }
    }

    $this->info(['info' => "Enable Bill Types..."]);
    if (!(($this->validator->ExecuteCommand($this->BillToBill_CMD["EnableBillTypes"])) && ($this->CommandResult(3) == 0))){
      $this->info(['error' => "Failed to set bill types!"]);
      $this->validator->close();
      return false;
    }

    return true;
  }
  //Тут у нас бесконечный loop

  /**
   * @param $LastCode
   * @param $min
   *
   * @return int
   */
  public function poll($LastCode)
  {
    if ($this->validator->ExecuteCommand($this->BillToBill_CMD["Poll"])){

      $this->validator->sendACK($this->BillToBill_CMD["ACK"]);
      $Code = $this->CommandResult(3);
      if ($Code != 0){
        if ($Code == $LastCode){
          $this->info(['info' => "wait"]);
        }else{
          $LastCode = $Code;
          $ExtendedCode = $this->CommandResult(4);
          $massage = dechex($Code)."H ".$this->BillToBill_Code[$Code];
          // if ($this->BillToBill_ExtendedCode[$Code][$ExtendedCode] != "") {
          //  $massage .= " - ".$this->BillToBill_ExtendedCode[$Code][$ExtendedCode];
          // }
          $this->info(['massage' => $massage]);
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
              $this->validator->ExecuteCommand($this->BillToBill_CMD["Stack"]);
              break;
            case 0x81:
              $this->info(['info' => "*BILLING MONEY*"]);
              $cash = $this->cash::where(['status' => 'wait'])->first();
              $cashValue = [
                2 => 10,
                3 => 50,
                4 => 100,
                5 => 500,
                6 => 1000,
                7 => 5000
              ];
              $cash->value = $cashValue[$ExtendedCode];
              $cash->status = 'injected';
              $this->order->cash()->save($cash);
              //$cash->save();

              $summ = 0;
              $cash = $this->cash::where('status', 'injected')->get();
              foreach ($cash as $item) {
                $summ += $item->value;
              }
              $this->cash::create(['status' => 'wait']);
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
  public function CommandResult($slot)
  {
    return ord($this->validator->CommandResult[$slot]);
  }

  public function getCommandResult()
  {
    $massage = " \r\n nothing";
    $Code = $this->CommandResult(3);
    if($Code){
      $massage = dechex($Code)."H ".$this->BillToBill_Code[$Code];
    }
    return $massage;
  }

  public function sendCommand($command)
  {
    $command = $this->BillToBill_CMD[$command];
    if($this->validator->ExecuteCommand($command)) {
      return $this->CommandResult(3);
    }
    return false;
  }

}