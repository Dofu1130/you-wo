<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ArticleController;

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

Route::controller(ArticleController::class)->group(function () {
    Route::post('article', 'store');
    Route::get('articles/{id}', 'show')->where('id', '[0-9]+');
    Route::get('articles', 'showMore');
    Route::delete('articles/{id}', 'delete')->where('id', '[0-9]+');
    Route::post('articles/{id}', 'update')->where('id', '[0-9]+');
});
