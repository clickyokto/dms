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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/bulk/price/update', 'HomeController@bulk')->name('bulk');
Route::post('/bulk/price/update', 'HomeController@bulkUpdate')->name('bulk-update');
//Route::get('/', function () {
//    return view('welcome');
//});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/register', 'HomeController@index');


Route::get('/update', function() {
    // if (Auth::check()) {

    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    dd('Updated');


    //
});


