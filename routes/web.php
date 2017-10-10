<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::group(['middleware' => 'web'],function(){

	Route::get('/1c',['as' => '1с', 'uses' => 'mainController@getDataFrom1C']);
	Route::get('/',['as' => 'home', 'uses' => 'mainController@index']);
	Route::post('/category/{id?}',['as' => 'category', 'uses' => 'mainController@getContent']);

	Route::get('/migrate', function(){
    //$order = App\Order::where('status', 'payed')->first();
    //dd($order);
    dispatch(new App\Jobs\SendOrdersToServer);
	});
});

Route::post('/search', [
    'as' => 'search',
    'uses' => 'mainController@search'
]);

Route::get('/pdf', [
    'uses' => 'mainController@pdf'
]);

Route::group(['prefix' => 'cash'], function() {
	Route::get('/', ['uses' => 'cashController@summ']);
	Route::get('/seed', ['uses' => 'cashController@seed']);
	Route::get('/get', ['uses' => 'cashController@getCash']);
});

Route::group(['prefix' => 'cart'], function() {
	Route::post('/', ['as' => 'cart', 'uses' => 'cartController@index']);
	Route::post('/count', ['as' => 'cartCount', 'uses' => 'cartController@count']);
	Route::post('/add', ['as' => 'cartAdd', 'uses' => 'cartController@add']);
	Route::post('/remove', ['as' => 'cartRemove', 'uses' => 'cartController@remove']);
	Route::post('/cancel', ['as' => 'cartCancel', 'uses' => 'cartController@cancel']);
	Route::post('/complete', ['as' => 'cartComplete', 'uses' => 'cartController@complete']);
});