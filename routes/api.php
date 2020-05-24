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

Route::post('/usercreate','UserController@create');

Route::post('/userlogin','UserController@login');

Route::put('/userupdate/{id}','UserController@update');

Route::delete('/userdelete/{id}','UserController@delete');

Route::get('/userget/{id}','UserController@get');

Route::post('/userdata','UserDataController@create');

