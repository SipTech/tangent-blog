<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\UserController;

/*
|--------------------------------------------------------------------------
| sanctum Routes
|--------------------------------------------------------------------------
|
| Here is where you can register sanctum routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "sanctum" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

/**********************************   Auth Routes   ***********************************************************/
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
/**********************************   Auth Route Ends Here  ***************************************************/

/**********************************   Category Route Starts Here   *******************************************/
Route::get('categories', [CategoryController::class, 'index'])->middleware('auth:sanctum');
Route::post('category/check/title', [CategoryController::class, 'checkTitle'])->middleware('auth:sanctum');
Route::post('category/check/slug',[CategoryController::class, 'checkSlug'])->middleware('auth:santcum');
Route::post('category/store',[CategoryController::class, 'store'])->middleware('auth:sanctum');
Route::get('category/{id}/show',[CategoryController::class, 'show']);
Route::post('category/edit/check/title',[CategoryController::class, 'checkEditTitle'])->middleware('auth:sanctum');
Route::post('category/edit/check/slug',[CategoryController::class, 'checkEditSlug'])->middleware('auth:sanctum');
Route::post('category/update',[CategoryController::class, 'update'])->middleware('auth:sanctum');
Route::post('category/destroy',[CategoryController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('category/{keyword}/search',[CategoryController::class, 'searchCategory']);
/**********************************   Category Route Ends Here   *******************************************/

/**********************************   Post Route Starts Here   *******************************************/
Route::get('posts',[PostController::class, 'index'])->middleware('auth:sanctum');
Route::post('post/check/title',[PostController::class, 'checkTitle'])->middleware('auth:sanctum');
Route::post('post/check/category',[PostController::class, 'checkCategory'])->middleware('auth:sanctum');
Route::post('post/check/body', [PostController::class, 'checkBody'])->middleware('auth:sanctum');
Route::post('post/store', [PostController::class, 'store'])->middleware('auth:sanctum');
Route::get('post/{id}/show', [PostController::class, 'show']);
Route::post('post/update', [PostController::class, 'update'])->middleware('auth:sanctum');
Route::post('post/destroy', [PostController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('post/{keyword}/search', [PostController::class, 'searchPost']);
Route::get('post/{id}/comments', [PostController::class, 'comments']);
/**********************************   Post Route Ends Here   *******************************************/

/**********************************   Comment Route Starts Here   *******************************************/
/*Route::get('comments','CommentController@index')->middleware('auth:sanctum');
Route::post('comment/check/comment','CommentController@checkComment')->middleware('auth:sanctum');
Route::post('comment/check/post','CommentController@checkPost')->middleware('auth:sanctum');
Route::post('comment/store','CommentController@store')->middleware('auth:sanctum');
Route::get('comment/{id}/show','CommentController@show');
Route::post('comment/{id}/update','CommentController@update')->middleware('auth:sanctum');
Route::post('comment/{id}/destroy','CommentController@destroy')->middleware('auth:sanctum');*/
/**********************************   Comment Route Ends Here   *******************************************/

/**********************************   user Route Starts Here   *******************************************/
Route::get('users',[UserController::class, 'index'])->middleware('auth:sanctum');
Route::post('user/check/name',[UserController::class, 'checkName']);
Route::post('user/check/email',[UserController::class, 'checkEmail']);
Route::post('user/check/password', [UserController::class,'checkPassword']);
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::get('user/detail', [UserController::class, 'getUser'])->middleware('auth:sanctum');
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
/**********************************   user Route Ends Here   *******************************************/
