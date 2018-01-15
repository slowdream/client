<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

// use App\libs\smsmru\SMSRU;
// use Curl;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $victim;
    protected $text;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($victim, $text)
    {
        $this->victim = $victim;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ch = curl_init("https://sms.ru/sms/send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
          "api_id" => env('smsru_api'),
          "to" => $this->victim, // До 100 штук до раз
          "msg" => $this->text,
          /*
          // Если вы хотите отправлять разные тексты на разные номера, воспользуйтесь этим кодом. В этом случае to и msg нужно убрать.
          "multi" => array( // до 100 штук за раз
              "79377238816"=> iconv("windows-1251", "utf-8", "Привет 1"), // Если приходят крякозябры, то уберите iconv и оставьте только "Привет!",
              "74993221627"=> iconv("windows-1251", "utf-8", "Привет 2")
          ),
          */
          "json" => 1 // Для получения более развернутого ответа от сервера
        ]));
        $body = curl_exec($ch);
        $body = json_decode($body, true);
    }
}
