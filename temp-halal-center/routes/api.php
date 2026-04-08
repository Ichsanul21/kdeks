<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\MapController;
use App\Http\Controllers\API\MentorController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/home', [HomeController::class, 'index']);
Route::get('/map', [MapController::class, 'index']);
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/mentors', [MentorController::class, 'index']);
Route::get('/search', SearchController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
