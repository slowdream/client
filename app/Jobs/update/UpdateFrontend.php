<?php

namespace App\Jobs\update;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateFrontend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      // Переходим в папку с проектом
      `cd home/terminal/web/frontend`;
      //Скачиваем свежую версию с гита и ставим пакеты
      `git fetch --all && git reset --hard origin/master && npm i`;
      // Билдим свежую версию
      `npm run build`;
    }
}
