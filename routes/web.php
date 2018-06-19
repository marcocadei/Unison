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

// FIXME solo debug - poi eliminare
Route::get('/alltracks', 'TrackController@allTracks')->middleware('auth');

Route::get("/feed", function () {
    return redirect("/home");
});
Route::get("/home", "TrackController@userFeed")->middleware('auth')->name('home');
Route::get('/user/{username}', 'TrackController@userProfile');
Route::get('top50', 'TrackController@top50');