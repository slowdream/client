<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('/category/{id?}',['as' => 'api.category', 'uses' => 'api\categoryController@getCategory']);
Route::any('/products/{id}',['as' => 'api.products', 'uses' => 'api\productsController@getProducts']);
Route::any('/product/{id}',['as' => 'api.product', 'uses' => 'api\productsController@getSingleProduct']);
Route::get('/search',['as' => 'api.search', 'uses' => 'api\productsController@search']);

Route::get('/printCheck',['as' => 'api.search', 'uses' => 'api\cartController@printCheck']);


Route::group(['prefix' => '/cart'], function() {
	Route::any('/', ['as' => 'api.cart', 'uses' => 'api\cartController@getCart']);
	Route::post('/count', ['as' => 'api.cart.Count', 'uses' => 'api\cartController@count']);
	Route::post('/add', ['as' => 'api.cart.Add', 'uses' => 'api\cartController@addToCart']);
	Route::post('/remove', ['as' => 'api.cart.Remove', 'uses' => 'api\cartController@remove']);
  Route::post('/cancel', ['as' => 'api.cart.Cancel', 'uses' => 'api\cartController@cancel']);
	Route::post('/add_contacts', ['as' => 'api.cart.Contacts', 'uses' => 'api\cartController@addContacts']);
	Route::post('/complete', ['as' => 'api.cart.Complete', 'uses' => 'api\cartController@complete']);
});

Route::group(['prefix' => '/cash'], function () {
  Route::get('/summ', ['uses' => 'api\cashController@getSumm']);
  Route::post('/start', ['uses' => 'api\cashController@startCash']);
  Route::get('/end', ['uses' => 'api\cashController@endCash']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});