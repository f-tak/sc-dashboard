<?php

use App\Http\Controllers\Api\ClockController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\TrainController;
use App\Http\Controllers\Api\WeatherController;
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

Route::middleware('api')->namespace('Api')->name('api.')->group(function () {
    Route::get('/clock', [ClockController::class, 'get'])->name('clock');
    Route::get('/train', [TrainController::class, 'get'])->name('train');
    Route::get('/news', [NewsController::class, 'get'])->name('news');
    Route::get('/weather', [WeatherController::class, 'get'])->name('weather');
});
