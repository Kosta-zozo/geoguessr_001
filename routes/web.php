<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/home', function () {
    return view('home');
});

// Route::get('/game', function () {
//     return view('game');
// });

Route::get('/game', 'App\Http\Controllers\DataController@game');
Route::get('/dbtest', 'App\Http\Controllers\DataController@test');