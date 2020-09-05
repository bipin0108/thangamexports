<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

 
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'Api\ApiController@login');
    Route::post('logout', 'Api\ApiController@logout');
    Route::post('refresh', 'Api\ApiController@refresh');
});

Route::get('category-list', 'Api\ApiController@category_list');
Route::get('product-list', 'Api\ApiController@product_list');
Route::get('dashboard', 'Api\ApiController@dashboard');
Route::get('sub-category-list/{id}', 'Api\ApiController@sub_category_list');

Route::post('order', 'Api\ApiController@order');
Route::get('order-list/{id}', 'Api\ApiController@order_list');

Route::group(['middleware' => ['auth.jwt']], function() { 

});
