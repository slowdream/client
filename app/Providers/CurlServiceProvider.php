<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CurlServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require_once app_path().'/Helpers/Curl.php';
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
