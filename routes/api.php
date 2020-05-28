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


Route::get('/eventmembers','EventMemberController@index');

Route::post('/neweventmember','EventMemberController@store');

Route::delete('/eventmembers/{id}','EventMemberController@destroy');

Route::get('/teammembers','TeamMemberController@index');

Route::post('/newteammember','TeamMemberController@store');

Route::delete('/team_member/{id}','TeamMemberController@destroy');

Route::put('/team_member/{id}','TeamMemberController@update');

Route::prefix('/users')->group(function () {
    Route::get('/{id}', 'UserController@get');
    Route::post('/create', 'UserController@create');
    Route::post('/login', 'UserController@login');
    Route::put('/{id}', 'UserController@update');
    Route::delete('/{id}', 'UserController@delete');
});
