<?php

namespace App\Jobs\update;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        `cd /home/terminal/web/frontend &&
      git fetch --all &&
      git reset --hard origin/master &&
      git pull &&
      npm i &&
      npm run build`;
        //Скачиваем свежую версию с гита и ставим пакеты
        // Билдим свежую версию
    }
}
