<?php

namespace App\Jobs\update;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBackend implements ShouldQueue
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
        return;
      $path = base_path();
      // Переходим в папку с проектом
      `cd {$path}`;
      //Скачиваем свежую версию с гита
      `git fetch --all && git reset --hard origin/master && composer update`;
      // Выполняем миграции
      `php artisan migrate:refresh`;
      // обновляем пакеты
      `composer install`;
      // Стягиваем заново все товары
      dispatch(new GetProductsFromServer);
    }
}
