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

Route::get('/', function () {
    return view('index');
})->name('index');


Route::get('/login', 'AuthController@create')->name('login');
Route::get('/register', 'AuthController@create')->name('register');
Route::post('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::post('/registration', 'AuthController@register');

Route::post('/checkNewUserCredentials', 'AuthController@checkNewUserCredentials');
Route::post('/checkUserCredentials', 'AuthController@checkUserCredentials');

Route::get('/home', 'HomeController@index')->name('home');