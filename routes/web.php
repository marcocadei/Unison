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
Route::post('/registration', 'AuthController@registration')->name('registration');

Route::post('/checkNewUserCredentials', 'AuthController@checkNewUserCredentials');
Route::post('/checkUserCredentials', 'AuthController@checkUserCredentials');

Route::get('/modify', 'UserController@edit')->name('modify');
// Route usata per modificare le informazioni utente; put è un metodo apposito; NB: in caso di modifica dell'username viene chiamata sull'username vecchio
Route::post('/modify', 'UserController@update');
Route::post('/checkNewModifiedUserCredentials', 'AuthController@checkNewModifiedUserCredentials');


// FIXME solo debug - poi eliminare
Route::get('/alltracks', 'TrackController@allTracks')->middleware('auth');

Route::get("/feed", function () {
    return redirect("/home");
});
Route::get("/home", "TrackController@userFeed")->middleware('auth')->name('home');
Route::get('/user/{username}', 'TrackController@userProfile');
Route::get('top50', 'TrackController@top50');

Route::get('/track/upload', 'TrackController@upload')->middleware('auth')->name('upload');
Route::post('/track', 'TrackController@store');
Route::post('/checkSongExistence', 'TrackController@checkSongExistence');
