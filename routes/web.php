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

// Route::get('/gameHub', function () {
//     return view('gamehub');
// })->middleware('auth');
Route::get('/gameHub', 'App\Http\Controllers\DataController@gameHub')->middleware('auth');
Route::get('/game', 'App\Http\Controllers\DataController@game')->middleware('auth');
Route::get('/resultPreview', 'App\Http\Controllers\DataController@game')->middleware('auth');
Route::post('/submitResult', 'App\Http\Controllers\DataController@submitResult')->middleware('auth');

Route::get('/{category}/{difficulty}/gameStartSerie', 'App\Http\Controllers\DataController@gameStartSerieDiff')->middleware('auth');
Route::get('/gameStartSerieEasy', 'App\Http\Controllers\DataController@gameStartSerieEasy')->middleware('auth');
Route::get('/gameStartSerieMedium', 'App\Http\Controllers\DataController@gameStartSerieMedium')->middleware('auth');
Route::get('/gameStartSerieHard', 'App\Http\Controllers\DataController@gameStartSerieHard')->middleware('auth');
Route::post('/gameContinueSerie', 'App\Http\Controllers\DataController@gameContinueSerie')->middleware('auth');

Route::get('/adminPanel', function () {
    return view('adminpanel');
})->middleware('auth');
Route::get('/addNewPlace', 'App\Http\Controllers\DataController@addNewPlace')->middleware('auth');
Route::post('/addPlace', 'App\Http\Controllers\DataController@addPlace')->middleware('auth');
Route::get('/placelist', 'App\Http\Controllers\DataController@placelist')->middleware('auth');
Route::get('/{id}/deleteplace', 'App\Http\Controllers\DataController@deleteplace')->middleware('auth');

Route::get('addNewCategory', function () {
    return view('addnewcategory');
})->middleware('auth');
Route::post('/addCategory', 'App\Http\Controllers\DataController@addCategory')->middleware('auth');
Route::get('/categorylist', 'App\Http\Controllers\DataController@categorylist')->middleware('auth');
Route::get('/{id}/deletecategory', 'App\Http\Controllers\DataController@deletecategory')->middleware('auth');
Route::get('/{id}/editcategory', 'App\Http\Controllers\DataController@openEditorCategory')->middleware('auth');
Route::post('/editCategory', 'App\Http\Controllers\DataController@editCategory')->middleware('auth');

Route::delete('/detachPlace/{id}', 'App\Http\Controllers\DataController@detachPlace')->middleware('auth');
Route::delete('/attachPlace/{id}/{category}', 'App\Http\Controllers\DataController@attachPlace')->middleware('auth');
Route::delete('/deletePlaceFromCard/{id}', 'App\Http\Controllers\DataController@deletePlaceFromCard')->middleware('auth');