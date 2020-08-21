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

Route::resource('/sample', 'SampleController')->middleware('auth');
Route::resource('/category', 'CategoryController')->middleware('auth');
Route::resource('/product', 'ProductController')->middleware('auth');
Route::resource('/export', 'ExportController');
