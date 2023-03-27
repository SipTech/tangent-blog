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
Route::post('/auth/logout', [AuthController::class, 'logout']);
/**********************************   Auth Route Ends Here  ***************************************************/

/**********************************   Category Route Starts Here   *******************************************/
Route::get('categories', [CategoryController::class, 'index'])->middleware('auth:sanctum');
Route::post('category/store',[CategoryController::class, 'store'])->middleware('auth:sanctum');
Route::get('category/{id}/show',[CategoryController::class, 'show'])->middleware('auth:sanctum');
Route::post('category/{id}/update',[CategoryController::class, 'update'])->middleware('auth:sanctum');
Route::post('category/{id}/destroy',[CategoryController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('category/{keyword}/search',[CategoryController::class, 'searchCategory']);
/**********************************   Category Route Ends Here   *******************************************/

/**********************************   Post Route Starts Here   *******************************************/
Route::get('posts',[PostController::class, 'index'])->middleware('auth:sanctum');
Route::post('post/store', [PostController::class, 'store'])->middleware('auth:sanctum');
Route::get('post/{id}/show', [PostController::class, 'show']);
Route::post('post/{id}/update', [PostController::class, 'update'])->middleware('auth:sanctum');
Route::post('post/{id}/destroy', [PostController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('post/{keyword}/search', [PostController::class, 'searchPost']);
Route::get('post/{id}/comments', [PostController::class, 'getPostComments']);
/**********************************   Post Route Ends Here   *******************************************/

/**********************************   Comment Route Starts Here   *******************************************/
Route::get('comments',[CommentController::class, 'index'])->middleware('auth:sanctum');
Route::post('comment/store', [CommentController::class, 'store'])->middleware('auth:sanctum');
Route::get('comment/{id}/show', [CommentController::class, 'show']);
Route::post('comment/{id}/update', [CommentController::class, 'update'])->middleware('auth:sanctum');
Route::post('comment/{id}/destroy', [CommentController::class, 'destroy'])->middleware('auth:sanctum');
/**********************************   Comment Route Ends Here   *******************************************/

/**********************************   user Route Starts Here   *******************************************/
Route::get('users',[UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('user/{id}/show', [UserController::class, 'show'])->middleware('auth:sanctum');
/**********************************   user Route Ends Here   *******************************************/
