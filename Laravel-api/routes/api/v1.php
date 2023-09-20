<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
{
    Route::resource('collections', App\Http\Controllers\Api\V1\CollectionController::class)->except(['create']);
    Route::post('collections/create', [App\Http\Controllers\Api\V1\CollectionController::class, 'create']);
    Route::resource('contributors', App\Http\Controllers\Api\V1\ContributorController::class)->except(['create']);
    Route::post('contributors/create', [App\Http\Controllers\Api\V1\ContributorController::class, 'create']);
}
