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

// Home page
Route::get('/', 'BaseController@index')->name('index');

// Login e registrazione e funzionalità correlate
Route::get('/login', 'AuthController@create')->name('login');
Route::post('/login', 'AuthController@login');
Route::get('/register', 'AuthController@create')->name('register');
Route::post('/registration', 'AuthController@registration')->name('registration');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::post('/checkNewUserCredentials', 'AuthController@checkNewUserCredentials');
Route::post('/checkUserCredentials', 'AuthController@checkUserCredentials');

// Modifica profilo utente
Route::get('/modify', 'UserController@edit')->name('modify');
Route::post('/modify', 'UserController@update');
Route::post('/delete', 'UserController@delete');

// Following
Route::post('/follow', 'UserController@follow');
Route::post('/unfollow', 'UserController@unfollow');

// Like
Route::post('/like', 'UserController@toggleLike');

// Pagine con tracce audio
Route::get("/home", "TrackController@userFeed")->middleware('auth')->name('home');
Route::get('/user/{userid}', 'TrackController@userProfile');
Route::get('/top50', 'TrackController@top50')->name('top50');
Route::get("/feed", function () {
    return redirect("/home");
});

// Upload e funzionalità correlate
Route::get('/track/upload', 'TrackController@upload')->middleware('auth')->name('upload');
Route::post('/track', 'TrackController@store');
Route::post('/checkSongExistence', 'TrackController@checkSongExistence');

// Ricerca
Route::get('/search', 'TrackController@search')->name('search');

// Servizio di Spotify
Route::post('/spotify/token', 'SpotifyController@token');

// FIXME solo debug - poi eliminare
Route::get('/alltracks', 'TrackController@allTracks')->middleware('auth');