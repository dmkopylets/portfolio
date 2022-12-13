<?php

use Illuminate\Support\Facades\Route;

Route::prefix('report')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\V1\FlightsController::class,'index']);
    Route::get('/drivers/', [App\Http\Controllers\Api\V1\DriversController::class,'index']);
});
