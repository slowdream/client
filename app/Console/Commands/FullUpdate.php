<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FullUpdate extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'FullUpdate {--hard}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Постановка в очередь команд для полного обвновления, ключ --hard перезапишет базы данных';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct ()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle ()
  {
    dispatch(new \App\Jobs\FullUpdate($this->option('hard')));
  }
}
