<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

{
    Route::view('/', 'welcome');
    Route::resource('students', App\Http\Controllers\Frontend\StudentController::class);
    Route::get('students/{student}/delete', [App\Http\Controllers\Frontend\StudentController::class, 'destroy']);
    Route::resource('groups', App\Http\Controllers\Frontend\GroupController::class);
    Route::get('groups/{group}/delete', [App\Http\Controllers\Frontend\GroupController::class, 'destroy']);
}
