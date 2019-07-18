<?php

namespace App\libs\cashcode;

use Log;

class CashValidator
{
    public $ValidatorHandle = null;
    public $CommandResult = null;

    public function open()
    {
        $com = env('ID_COM_PORT', '/dev/ttyS0');
        `stty -F {$com} min 0 -hupcl -icrnl -ixon -isig -icanon -iexten -echo ignbrk noflsh -opost`;

        return $this->ValidatorHandle = fopen($com, 'r+');
        //return $this->ValidatorHandle = fopen("com1", "w+b");
    }

    public function close()
    {
        if ($this->ValidatorHandle) {
            fclose($this->ValidatorHandle);
        }
    }

    public function sendACK($ack)
    {
        if ($this->ValidatorHandle) {
            fwrite($this->ValidatorHandle, $ack);
        }
    }

    public function ErrorHandler($errno, $errstr, $errfile, $errline)
    {
        $data['error_text'] = $errstr;

        return false;
    }

    public function ExecuteCommand($Command, $Waiting = true)
    {
        if ($this->ValidatorHandle) {
            Log::info('FWrite "ExecuteCommand" start');
            fwrite($this->ValidatorHandle, $Command);
            Log::info('FWrite "ExecuteCommand" end');
            if (!$Waiting) {
                return true;
            }
            $result = null;
            $cur_time = time();
            $expire_time = time() + 5; //Максимум 5 сек ждем
            while (time() < $expire_time) {
                Log::info('FRead "ExecuteCommand" start');
                $result .= fread($this->ValidatorHandle, 255);
                Log::info('FRead "ExecuteCommand" end');
                if (($result) && (ord($result[2]) > 0) && (strlen($result) >= ord($result[2]))) {
                    $this->CommandResult = $result;
                    break;
                } else {
                    usleep(50 * 1000);
                }
            }
        }
        if ($this->CommandResult) {
            return true;
        } else {
            return false;
        }
    }

    public function readPort()
    {
        $result = null;
        $cur_time = time();
        $expire_time = $cur_time + 5; //Максимум 5 сек ждем
        while (time() < $expire_time) {
            Log::info('FRead "readPort" start');
            $result .= fread($this->ValidatorHandle, 255);
            Log::info('FRead "readPort" end');
            if ($result) {
                return $result;
                break;
            } else {
                usleep(50 * 1000);
            }
        }

        return false;
    }
}
