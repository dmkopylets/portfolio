<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
{
    Route::resource('students', App\Http\Controllers\Api\V1\StudentController::class)->except(['edit','store','update']);
    Route::resource('groups', App\Http\Controllers\Api\V1\GroupController::class)->except(['edit','store','update']);
}
