<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/login', 'Auth\AuthController@index')->name('login.index');
    Route::get('/login/google', 'Auth\AuthController@loginGoogle')->name('login.google');
    Route::get('/login/google/done', 'Auth\AuthController@loginGoogleDone');
    Route::get('/logout', 'Auth\AuthController@logout')->name('logout');

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('nation/image/{nation}', 'NationController@image')->name('nation.image');
    Route::resource('nation', 'NationController', [['except' => ['show']]]);
    Route::resource('driver', 'DriverController', [['except' => ['create', 'store', 'destroy']]]);
    Route::resource('points-sequence', 'PointsSequenceController');

    Route::group(['prefix' => 'dirt-rally', 'namespace' => 'DirtRally'], function() {
        include('Routes/dirt-rally.php');
    });

    Route::group(['prefix' => 'assetto-corsa', 'namespace' => 'AssettoCorsa'], function() {
        include('Routes/assetto-corsa.php');
    });

    Route::get('about', function() {
        return view('about');
    })->name('about');
});
