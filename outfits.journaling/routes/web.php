<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

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

$branchname='';
$displayName='';
//*************************************************************************************************************
// це буде потрібно для auth-ентифікації через AD
// Route::get('/', [App\Http\Controllers\eNaryad\NaryadController::class,'welcome'])->middleware(['auth']);
// Auth::routes([
//    'reset' => false,
//    'verify' => false,
//    'register' => false,
//   ]);
//Route::get('welcome', [App\Http\Controllers\eNaryad\NaryadController::class,'welcome']);
//*************************************************************************************************************
Route::get('/', [App\Http\Controllers\eNaryad\NaryadController::class,'welcome']);
Route::get('login', [App\Http\Controllers\eNaryad\NaryadController::class,'welcome']);
Route::resource('naryads', App\Http\Controllers\eNaryad\NaryadController::class)->only(['index','edit','create','store']);
Route::get('naryads/precreate', [App\Http\Controllers\eNaryad\NaryadController::class,'precreate']);
Route::post('naryads/{naryad}/editpart2', [App\Http\Controllers\eNaryad\NaryadController::class,'editpart2']);
Route::post('naryads/{naryad}/editpart3', [App\Http\Controllers\eNaryad\NaryadController::class,'editpart3']);
Route::post('naryads/{naryad}/editpart4', [App\Http\Controllers\eNaryad\NaryadController::class,'editpart4']);
Route::post('naryads/{naryad}/editpart5', [App\Http\Controllers\eNaryad\NaryadController::class,'editpart5']);
Route::post('naryads/{naryad}/reedit',    [App\Http\Controllers\eNaryad\NaryadController::class,'reedit']);
Route::post('naryads/{naryad}/reedit2',   [App\Http\Controllers\eNaryad\NaryadController::class,'reedit2']);
Route::post('naryads/{naryad}/reedit3',   [App\Http\Controllers\eNaryad\NaryadController::class,'reedit3']);
Route::post('naryads/{naryad}/reedit4',   [App\Http\Controllers\eNaryad\NaryadController::class,'reedit4']);
Route::get('naryads/{naryad}/pdf', [App\Http\Controllers\eNaryad\NaryadController::class,'pdf']);
Route::resource('dicts/Branches', App\Http\Controllers\eNaryad\DictBranchesController::class)->only(['index','edit','create','store','update']);
Route::resource('dicts/Units', App\Http\Controllers\eNaryad\DictUnitsController::class)->only(['index','edit','create','store','update']);
Route::resource('dicts/Wardens', App\Http\Controllers\eNaryad\DictWardensController::class)->only(['index','edit','create','store','update']);
Route::resource('dicts/Adjusters', App\Http\Controllers\eNaryad\DictAdjustersController::class)->only(['index','edit','create','store','update']);
Route::resource('dicts/BrigadeMembers', App\Http\Controllers\eNaryad\DictBrigadeMembersController::class)->only(['index','edit','create','store','update']);
Route::resource('dicts/BrigadeEngineers', App\Http\Controllers\eNaryad\DictBrigadeEngineersController::class)->only(['index','edit','create','store','update']);
Route::resource('dicts/Substations', App\Http\Controllers\eNaryad\DictSubstationsController::class)->only(['index','edit','create','store','update',]);
Route::resource('dicts/Lines', App\Http\Controllers\eNaryad\DictLinesController::class)->only(['index','edit','create','store','update']);
Route::resource('dicts/Tasks', App\Http\Controllers\eNaryad\DictTypicalTasksController::class)->only(['index','edit','create','store','update']);

Route::get('/clear_cache', function () {
  Artisan::call('cache:clear');
  Artisan::call('config:cache');
  Artisan::call('view:clear');
  Artisan::call('route:clear');

  return "Кеш очищено.";
  });