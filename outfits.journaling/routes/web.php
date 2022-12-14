<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//*************************************************************************************************************
// це буде потрібно для auth-ентифікації через AD
// Route::get('/', [App\Http\Controllers\Ejournal\EjournalController::class,'welcome'])->middleware(['auth']);
// Auth::routes([
//    'reset' => false,
//    'verify' => false,
//    'register' => false,
//   ]);
//Route::get('welcome', [App\Http\Controllers\Ejournal\EjournalController::class,'welcome']);
//*************************************************************************************************************
Route::get('/', [App\Http\Controllers\Ejournal\EjournalController::class,'welcome']);
Route::get('login', [App\Http\Controllers\Ejournal\EjournalController::class,'welcome']);
Route::resource('naryads', App\Http\Controllers\Ejournal\EjournalController::class)->only(['index','edit','create','store']);
Route::get('naryads/precreate', [App\Http\Controllers\Ejournal\EjournalController::class,'precreate']);
Route::post('naryads/{naryad}/editpart2', [App\Http\Controllers\Ejournal\EjournalController::class,'editpart2']);
Route::post('naryads/{naryad}/editpart3', [App\Http\Controllers\Ejournal\EjournalController::class,'editpart3']);
Route::post('naryads/{naryad}/editpart4', [App\Http\Controllers\Ejournal\EjournalController::class,'editpart4']);
Route::post('naryads/{naryad}/editpart5', [App\Http\Controllers\Ejournal\EjournalController::class,'editpart5']);
Route::post('naryads/{naryad}/reedit',    [App\Http\Controllers\Ejournal\EjournalController::class,'reedit']);
Route::post('naryads/{naryad}/reedit2',   [App\Http\Controllers\Ejournal\EjournalController::class,'reedit2']);
Route::post('naryads/{naryad}/reedit3',   [App\Http\Controllers\Ejournal\EjournalController::class,'reedit3']);
Route::post('naryads/{naryad}/reedit4',   [App\Http\Controllers\Ejournal\EjournalController::class,'reedit4']);
Route::get('naryads/{naryad}/pdf', [App\Http\Controllers\Ejournal\EjournalController::class,'pdf']);

Route::prefix('dicts')->group(function () {
    Route::resource('/Branches', \App\Http\Controllers\Ejournal\Dicts\BaseDictController::class)->only(['index','edit','create','store','update']);
    Route::resource('/Units', App\Http\Controllers\Ejournal\DictUnitsController::class)->only(['index','edit','create','store','update']);
    Route::resource('/Wardens', App\Http\Controllers\Ejournal\DictWardensController::class)->only(['index','edit','create','store','update']);
    Route::resource('/Adjusters', App\Http\Controllers\Ejournal\DictAdjustersController::class)->only(['index','edit','create','store','update']);
    Route::resource('/BrigadeMembers', App\Http\Controllers\Ejournal\Dicts\DictBrigadeMembersController::class)->only(['index','edit','create','store','update']);
    Route::resource('/BrigadeEngineers', \App\Http\Controllers\Ejournal\Dicts\DictBrigadeEngineersController::class)->only(['index','edit','create','store','update']);
    Route::resource('/Substations', App\Http\Controllers\Ejournal\DictSubstationsController::class)->only(['index','edit','create','store','update',]);
    Route::resource('/Lines', App\Http\Controllers\Ejournal\DictLinesController::class)->only(['index','edit','create','store','update']);
    Route::resource('/Tasks', \App\Http\Controllers\Ejournal\Dicts\DictTypicalTasksController::class)->only(['index','edit','create','store','update']);
});

Route::get('/clearCache', function () {
  Artisan::call('cache:clear');
  Artisan::call('config:cache');
  Artisan::call('view:clear');
  Artisan::call('route:clear');
  Artisan::call('optimize:clear');

  return "Кеш очищено.";
  });
