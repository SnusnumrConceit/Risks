<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->action([RiskController::class, 'index']);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('factors', FactorController::class);
    Route::resource('divisions', DivisionController::class);
    Route::resource('risks', RiskController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('types', TypeController::class);
    Route::resource('users', UserController::class);
});
