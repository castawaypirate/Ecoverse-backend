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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('posts')->group(function () {
    Route::get('/','PostController@index')->name('posts');
    Route::post('/', 'PostController@store')->name('create');
    Route::delete('/{id}', 'PostController@destroy')->name('delete');
    Route::put('/{id}', 'PostController@update')->name('update');

});

Route::resource('events', 'EventController');
