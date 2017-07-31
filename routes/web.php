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
	Route::get('/',function(){
		return redirect()->route('categorys');	
	});
	Route::get('/categorys/{id?}',['as' => 'categorys', 'uses' => 'mainController@categorys']);
	Route::get('/items/{id}',['as' => 'items', 'uses' => 'mainController@items']);
});