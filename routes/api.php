<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ─── Public Routes (Read Only - via HomeController) ───
Route::get('/discovery', [HomeController::class, 'index']);
// Route::get('/banners', [HomeController::class, 'banners']);
Route::get('/categories', [HomeController::class, 'categories']);
Route::get('/categories/{category}', [HomeController::class, 'category']);
Route::get('/recipes', [HomeController::class, 'recipes']);
Route::get('/recipes/{recipe}', [HomeController::class, 'recipe']);

// ─── Auth ───
Route::post('/login', [AuthController::class, 'login']);

// ─── Protected Routes (Memerlukan Token) ───
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {

    // Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin CRUD (Create, Update, Delete)
    // index() & show() in these controllers are kept but public access is via HomeController
    Route::resource('banners', BannerController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('recipes', RecipeController::class);

});
