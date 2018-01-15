<?php

use Illuminate\Foundation\Inspiring;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('GetProducts', function () {
    dispatch(new App\Jobs\GetProductsFromServer);
});
Artisan::command("SendOrdersToServer {id=''}", function ($id) {
    dispatch(new App\Jobs\SendOrdersToServer($id));
});

Artisan::command('testprinter', function () {
    $data = [
      'products' => [
        [
          'guid' => '66666',
          'name' => 'ttteeest',
          'count' => '3',
          'price' => '666',
        ]
      ],
      'summ' => 666,
      'cash' => 6666,
      'tel' => '666666',
      'address' => 'gdfgdfgdfgdfgfddf',
      'id' => '6666',
      'date' => Carbon::now('Europe/Moscow')->toDateTimeString(),
      'orderDate' => '66.66.66',
      'timeRange' => '121-122',
      'reason' => 'test',
      'delivery' => 300
    ];
    //Четвертое число в размере бумаги это высота чека и его нужно вычислять заранее
    $pdf = PDF::loadView('check/receipt', $data)
      ->setPaper([0, 0, 218, 500], 'portrait');
    //return $pdf->stream();
    $pdf->save(resource_path('reciepts/reciept.pdf'));
    $file = resource_path('reciepts/reciept.pdf');
    $print = `lp {$file}`;
});

Artisan::command("testemail", function () {
    Mail::to("slowdream@yandex.ru")->send(new \App\Mail\TestEmail());
});