<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'index']);
Route::prefix('report')->group(function () {
    Route::get('/', [App\Http\Controllers\Frontend\FlightsController::class, 'index'])->name('report');
    Route::get('/drivers/', [App\Http\Controllers\Frontend\DriversController::class, 'index'])->name('drivers');
    Route::get('/drivers/{driverId}', [App\Http\Controllers\Frontend\OneDriverController::class, 'index'])->name('oneDriver');
});
