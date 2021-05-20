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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::resource('factors', FactorController::class);
    Route::resource('divisions', DivisionController::class);
    Route::resource('risks', RiskController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('types', TypeController::class);
    Route::resource('users', UserController::class);
    Route::get('/metrics', 'MetricController@index')->name('metrics.index');
    Route::get('/reports', 'ReportController@index')->name('reports.index');
    Route::post('/reports/export', 'ReportController@export')->name('reports.export');
});
