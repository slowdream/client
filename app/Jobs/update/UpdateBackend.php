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

  protected $hard; // Жесткое обновление

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct ($hard = false)
  {
    $this->hard = $hard;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle ()
  {
    $path = base_path();
    // Переходим в папку с проектом
    `cd {$path}`;
    //Скачиваем свежую версию с гита
    `git fetch --all && git reset --hard origin/master`;
    // обновляем пакеты
    `composer install`;
    if ($this->hard) {
      // Перезапускаем миграции
      `php artisan migrate:refresh`;
      // Стягиваем заново все товары
      dispatch(new GetProductsFromServer);
    } else {
      // Выполняем новые миграции
      `php artisan migrate`;
    }
  }
}
