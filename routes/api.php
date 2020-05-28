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
Route::prefix('/events')->group(function () {

    Route::get('/', 'EventController@index')->name('events');
    Route::post('/', 'EventController@store')->name('event');
    Route::put('/{id}', 'EventController@update')->name('update');
    Route::delete('/{id}', 'EventController@destroy')->name('delete');

});
