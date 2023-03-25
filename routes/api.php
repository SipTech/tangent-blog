<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/


/**********************************   Category Route Starts Here   *******************************************/
Route::get('categories','CategoryController@index')->middleware('auth:api');
Route::post('category/check/title','CategoryController@checkTitle')->middleware('auth:api');
Route::post('category/check/slug','CategoryController@checkSlug')->middleware('auth:api');
Route::post('category/store','CategoryController@store')->middleware('auth:api');
Route::get('category/{id}/show','CategoryController@show');
Route::post('category/edit/check/title','CategoryController@checkEditTitle')->middleware('auth:api');
Route::post('category/edit/check/slug','CategoryController@checkEditSlug')->middleware('auth:api');
Route::post('category/update','CategoryController@update')->middleware('auth:api');
Route::post('category/destroy','CategoryController@destroy')->middleware('auth:api');
Route::get('category/{keyword}/search','CategoryController@searchCategory');
/**********************************   Category Route Ends Here   *******************************************/

/**********************************   Post Route Starts Here   *******************************************/
Route::get('posts','PostController@index')->middleware('auth:api');
Route::post('post/check/title','PostController@checkTitle')->middleware('auth:api');
Route::post('post/check/category','PostController@checkCategory')->middleware('auth:api');
Route::post('post/check/body','PostController@checkBody')->middleware('auth:api');
Route::post('post/store','PostController@store')->middleware('auth:api');
Route::get('post/{id}/show','PostController@show');
Route::post('post/update','PostController@update')->middleware('auth:api');
Route::post('post/destroy','PostController@destroy')->middleware('auth:api');
Route::get('post/{keyword}/search','PostController@searchPost');
Route::get('post/{id}/comments','PostController@comments');
/**********************************   Post Route Ends Here   *******************************************/

/**********************************   Comment Route Starts Here   *******************************************/
Route::get('comments','CommentController@index')->middleware('auth:api');
Route::post('comment/check/comment','CommentController@checkComment')->middleware('auth:api');
Route::post('comment/check/post','CommentController@checkPost')->middleware('auth:api');
Route::post('comment/store','CommentController@store')->middleware('auth:api');
Route::get('comment/{id}/show','CommentController@show');
Route::post('comment/{id}/update','CommentController@update')->middleware('auth:api');
Route::post('comment/{id}/destroy','CommentController@destroy')->middleware('auth:api');
/**********************************   Comment Route Ends Here   *******************************************/

/**********************************   user Route Starts Here   *******************************************/
Route::get('users','UserController@index')->middleware('auth:api');
Route::post('user/check/name','UserController@checkName');
Route::post('user/check/email','UserController@checkEmail');
Route::post('user/check/password','UserController@checkPassword');
Route::post('register','UserController@register');
Route::post('login','UserController@login');
Route::get('user/detail','UserController@getUser')->middleware('auth:api');
Route::post('logout','UserController@logout')->middleware('auth:api');
/**********************************   user Route Ends Here   *******************************************/
