<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DompdfServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require_once app_path().'/libs/dompdf/autoload.inc.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
