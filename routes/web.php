<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('admin.dashboard');
})->middleware('auth');
Auth::routes(['register' => false]);
Route::get('/dashboard', 'HomeController@index')->name('dashboard');
Route::get('/logout', 'HomeController@logout');
Route::get('/profile', 'HomeController@profile')->middleware('auth');

Route::post('/profile-change', 'HomeController@profile_change')->middleware('auth');
Route::post('/change-password', 'HomeController@change_password')->middleware('auth');

Route::resource('/user', 'UserController')->middleware('auth');
Route::resource('/category', 'CategoryController')->middleware('auth');
Route::post('/category-is-popular', 'CategoryController@is_popular')->name('category.is_popular')->middleware('auth');
Route::resource('/product', 'ProductController')->middleware('auth');
Route::get('/get-sub-category/{id}', 'ProductController@sub_category')->name('sub.category')->middleware('auth');
Route::post('/product-is-popular', 'ProductController@is_popular')->name('product.is_popular')->middleware('auth');
Route::resource('/slider', 'SliderController')->middleware('auth');


// Sub Category
Route::get('/sub-category/{id}', 'SubCategoryController@index')->name('sub_category.index')->middleware('auth');
Route::get('/sub-category-create/{id}', 'SubCategoryController@create')->name('sub_category.create')->middleware('auth'); 
Route::post('/sub-category-store/{id}', 'SubCategoryController@store')->name('sub_category.store')->middleware('auth'); 
Route::get('/sub-category-edit/{cat_id}/{sub_cat_id}', 'SubCategoryController@edit')->name('sub_category.edit')->middleware('auth'); 
Route::patch('/sub-category-update/{cat_id}/{sub_cat_id}', 'SubCategoryController@update')->name('sub_category.update')->middleware('auth'); 
Route::delete('/sub-category-destroy/{cat_id}/{sub_cat_id}', 'SubCategoryController@destroy')->name('sub_category.destroy')->middleware('auth'); 
