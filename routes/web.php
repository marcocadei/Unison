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

Route::get('/', 'BaseController@index')->name('index');

Route::get('/login', 'AuthController@create')->name('login');
Route::get('/register', 'AuthController@create')->name('register');
Route::post('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::post('/registration', 'AuthController@register')->name('registration');

Route::post('/checkNewUserCredentials', 'AuthController@checkNewUserCredentials');
Route::post('/checkUserCredentials', 'AuthController@checkUserCredentials');

// FIXME Questa route è da ributtare al feed (una volta che sarà pronto; poi eliminare anche HomeController)
//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/alltracks', 'TrackController@allTracks')->middleware('auth')->name('home');
Route::get('/user/{username}', 'TrackController@userProfile');