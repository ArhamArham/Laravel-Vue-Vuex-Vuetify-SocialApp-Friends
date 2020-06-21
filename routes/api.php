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

Route::group(['middleware' => ['auth:api'], 'namespace'=>'Api'], function () {
    //Users
    Route::resource('users', 'UserController');
    Route::resource('posts', 'PostController');
    Route::resource('profile', 'ProfileController');
    Route::get('profileposts','PostController@profilePosts');
    Route::resource('follow', 'FollowController');
    Route::post('/profile/updatethumbnail','ProfileController@updatethumbnail');
    Route::get('loggedUser','UserController@loggedUser');
    Route::get('loggedUserPosts','UserController@loggedUserPosts');
    Route::post('/post/comment','PostController@comment')->name('post.comment');
    Route::post('/post/reply','PostController@reply')->name('post.reply');
    Route::resource('comment', 'CommentController');
    Route::resource('reply', 'ReplyController');
    Route::get('verify', 'UserController@verify');
    Route::post('search-results', 'SearchController@search');
    Route::put('/updatesettings/{id}', 'UserController@updatesettings');
    
});

Route::post('email/validate','Api\UserController@verifyEmail');
Route::post('signup','Api\UserController@signup');
Route::post('login','Api\UserController@login');