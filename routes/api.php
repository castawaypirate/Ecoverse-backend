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
    Route::post('/{id}/add_comment', 'PostController@addComment');
    Route::post('/{id}/handle_like', 'PostController@handleLike');

});

Route::resource('events', 'EventController');

Route::prefix('/events')->group(function () {
    Route::get('/', 'EventController@index')->name('events');
    Route::post('/', 'EventController@store')->name('event');
    Route::put('/{id}', 'EventController@update')->name('update');
    Route::delete('/{id}', 'EventController@destroy')->name('delete');
});


Route::get('/eventmembers','EventMemberController@index');

Route::post('/neweventmember','EventMemberController@store');

Route::delete('/eventmembers/{id}','EventMemberController@destroy');

Route::prefix('/users')->group(function () {
    Route::get('/{id}', 'UserController@getUser');
    Route::get('/data/{id}', 'UserController@getUserData');
    Route::post('/create', 'UserController@create');
    Route::post('/login', 'UserController@login');
    Route::put('/{id}', 'UserController@update');
    Route::delete('/{id}', 'UserController@delete');
});

Route::prefix('/team')->group(function () {
    Route::get('/', 'TeamController@read');
    Route::get('/edit', 'TeamController@editMany');
    Route::get('/{team_id}/edit', 'TeamController@readForEdit');
    Route::post('/create', 'TeamController@store');
    Route::get('/{team_id}', 'TeamController@readOne')->middleware('member.actions:create');
    Route::post('/{team_id}/edit', 'TeamController@update')->middleware('member.actions:edit');
    Route::delete('/{team_id}', 'TeamController@destroy')->middleware('member.actions:delete');
    Route::prefix('/{team_id}/members')->group(function() {
        Route::get('/', 'TeamController@getMembers')->middleware('member.actions:create');
        Route::post('/{user_id}/apply', 'TeamController@teamMemberApply');
        Route::post('/{user_id}/accept_member', 'TeamController@teamMemberAccept')->middleware('member.actions:create');
        Route::post('/createMany', 'TeamController@addMember')->middleware('member.actions:create');
        Route::post('/{member_id}/edit', 'TeamMemberController@update')->middleware('member.actions:create');
        Route::delete('/{member_id}', 'TeamController@deleteMember')->middleware('member.actions:create');
    });
});

Route::prefix('/team_member')->group(function () {
    Route::delete('/{member_id}', 'TeamMemberController@destroy');
});

Route::prefix('/comments')->group(function () {
    Route::get('/{id}/comments', 'CommentController@getAllComments');
    Route::post('/{id}/edit', 'CommentController@edit');
    Route::post('/{id}/createAnswer', 'CommentController@createAnswer');
    Route::delete('/{id}', 'CommentController@destroy');
    Route::post('/{id}/handle_like', 'CommentController@handleLike');
});
