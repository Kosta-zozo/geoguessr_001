<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/loginform', function () {
    return view('login');
})->name('login');
Route::post('/login','App\Http\Controllers\LoginController@login');
Route::get('/registerform', function () {
    return view('register');
})->name('login');
Route::post('/register','App\Http\Controllers\LoginController@register');
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Route::get('/game', function () {
//     return view('game');
// });

Route::get('/game', 'App\Http\Controllers\DataController@game')->middleware('auth');
Route::get('/resultPreview', 'App\Http\Controllers\DataController@game')->middleware('auth');
Route::post('/submitResult', 'App\Http\Controllers\DataController@submitResult')->middleware('auth');

Route::get('/adminPanel', 'App\Http\Controllers\DataController@adminPanel')->middleware('auth');
Route::post('/addPlace', 'App\Http\Controllers\DataController@addPlace')->middleware('auth');
