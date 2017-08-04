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
	Route::get('/',function(){
		return redirect()->route('category');	
	})->name('home');
	Route::get('/category/{id?}',['as' => 'category', 'uses' => 'mainController@getContent']);
	//Route::get('/items/{id}',['as' => 'items', 'uses' => 'mainController@items']);

	Route::post('/refresh', function(){
		$path = base_path();
		$echo = `cd {$path} && git pull && php artisan migrate:refresh`;
		return $echo;		
	});
});



Route::group(['prefix' => 'cart'], function() {
	Route::get('/', ['as' => 'cart', 'uses' => 'cartController@index']);
	Route::post('/count', ['as' => 'cartCount', 'uses' => 'cartController@count']);
	Route::post('/add', ['as' => 'cartAdd', 'uses' => 'cartController@add']);
	Route::post('/remove', ['as' => 'cartRemove', 'uses' => 'cartController@remove']);
	Route::post('/cancel', ['as' => 'cartcancel', 'uses' => 'cartController@cancel']);
});