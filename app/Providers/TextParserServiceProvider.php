<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TextParserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require_once app_path().'/Helpers/TextParser.php';
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
