<?php

use App\Http\Controllers\api\AuthenticationController;
use App\Http\Controllers\api\BrandController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\RoleController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\outside\FileControl;
use App\Models\User;
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

// Route::middleware('auth:sanctum=')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('auth/register', [AuthenticationController::class, 'register']);
Route::post('auth/login', [AuthenticationController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('auth/profile', [AuthenticationController::class, 'profile']);
    Route::get('logout', [AuthenticationController::class, 'logout']);
});

// API Routes for User middleware
Route::middleware(['auth:api'])->group(function () {
    //!Cors
    //?user Controller
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::get('users/edit/{id}', [UserController::class, 'edit']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    //?Role controller
    Route::get('roles', [RoleController::class, 'index']);
    Route::post('roles', [RoleController::class, 'create']);
    Route::get('roles/{id}', [RoleController::class, 'show']);
    Route::put('roles/{id}', [RoleController::class, 'update']);
    Route::delete('roles/{id}', [RoleController::class, 'delete']);
    //?Brand Controller
    Route::get('brand', [BrandController::class, 'index']);
    Route::post('brand', [BrandController::class, 'create']);
    Route::get('brand/{id}', [BrandController::class, 'show']);
    Route::put('brand/{id}', [BrandController::class, 'update']);
    Route::delete('brand/{id}', [BrandController::class, 'delete']);
    //?Category Controller
    Route::get('category', [CategoryController::class, 'index']);
    Route::post('category', [CategoryController::class, 'create']);
    Route::get('category/{id}', [CategoryController::class, 'show']);
    Route::put('category/{id}', [CategoryController::class, 'update']);
    Route::delete('category/{id}', [CategoryController::class, 'delete']);

    //?Product Controller
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'create']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'delete']);
    //?order Controller
    // Route::get('order', [ProductController::class, 'index']);
    // Route::post('order', [ProductController::class, 'create']);
    // Route::get('order/{id}', [ProductController::class, 'show']);
    // Route::put('order/{id}', [ProductController::class, 'update']);
    // Route::delete('order/{id}', [ProductController::class, 'delete']);

    //!testing multi_fileSystemController
    Route::get('img', [FileControl::class, 'index']);
    Route::post('img', [FileControl::class, 'create']);
});
