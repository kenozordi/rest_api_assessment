<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User Routes
Route::prefix('users')->name('users.')->group(function () {
    Route::get('', 'UserController@getAll')->name('getAll');
    Route::post('', 'UserController@store')->name('store');
    Route::post('/toggle/{id}', 'UserController@toggle')->name('toggle');
    Route::get('/{id}', 'UserController@show')->name('show');
    Route::put('/{id}', 'UserController@update')->name('update');

    // Auth Routes
    Route::post('login', 'UserController@login')->name('login');
});
