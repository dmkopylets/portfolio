<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

{
    Route::view('/', 'welcome');
    Route::resource('collections', App\Http\Controllers\Frontend\CollectionController::class);
    Route::get('collections/{collection}/delete', [App\Http\Controllers\Frontend\CollectionController::class, 'destroy']);
    Route::resource('contributors', App\Http\Controllers\Frontend\ContributorController::class);
    Route::get('contributors/{contributor}/delete', [App\Http\Controllers\Frontend\ContributorController::class, 'destroy']);
}
