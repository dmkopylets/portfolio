<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Admin\HomeController::class,'index']);
Route::prefix('report')->group(function () {
    Route::get('/', [App\Http\Controllers\Racing\FlightsRenderController::class,'index']);
    Route::get('/drivers/', [App\Http\Controllers\Racing\DriversRenderController::class,'index']);
});
