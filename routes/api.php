<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Middleware\Api\V1\ApiRequestLogging;

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
Route::post('/auth/register', [AuthController::class, 'createUser'])->middleware([ApiRequestLogging::class]);
Route::post('/auth/login', [AuthController::class, 'loginUser'])->middleware([ApiRequestLogging::class]);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware([ApiRequestLogging::class]);
/**********************************   Auth Route Ends Here  ***************************************************/

/**********************************   Category Route Starts Here   *******************************************/
Route::get('categories', [CategoryController::class, 'index'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::post('category/store',[CategoryController::class, 'store'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::get('category/{id}/show',[CategoryController::class, 'show'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::post('category/{id}/update',[CategoryController::class, 'update'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::delete('category/{id}/destroy',[CategoryController::class, 'destroy'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::get('category/{keyword}/search',[CategoryController::class, 'searchCategory'])->middleware([ApiRequestLogging::class]);
/**********************************   Category Route Ends Here   *******************************************/

/**********************************   Post Route Starts Here   *******************************************/
Route::get('posts',[PostController::class, 'index'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::post('post/store', [PostController::class, 'store'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::get('post/{id}/show', [PostController::class, 'show'])->middleware([ApiRequestLogging::class]);
Route::post('post/{id}/update', [PostController::class, 'update'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::delete('post/{id}/destroy', [PostController::class, 'destroy'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::get('post/{keyword}/search', [PostController::class, 'searchPost'])->middleware([ApiRequestLogging::class]);
Route::get('post/{id}/comments', [PostController::class, 'getPostComments'])->middleware([ApiRequestLogging::class]);
/**********************************   Post Route Ends Here   *******************************************/

/**********************************   Comment Route Starts Here   *******************************************/
Route::get('comments',[CommentController::class, 'index'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::post('comment/store', [CommentController::class, 'store'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::get('comment/{id}/show', [CommentController::class, 'show'])->middleware([ApiRequestLogging::class]);
Route::post('comment/{id}/update', [CommentController::class, 'update'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::post('comment/{id}/destroy', [CommentController::class, 'destroy'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
/**********************************   Comment Route Ends Here   *******************************************/

/**********************************   user Route Starts Here   *******************************************/
Route::get('users',[UserController::class, 'index'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
Route::get('user/{id}/show', [UserController::class, 'show'])->middleware(['auth:sanctum', ApiRequestLogging::class]);
/**********************************   user Route Ends Here   *******************************************/
